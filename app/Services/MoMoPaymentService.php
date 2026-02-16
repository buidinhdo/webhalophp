<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MoMoPaymentService
{
    protected $endpoint;
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;
    protected $returnUrl;
    protected $notifyUrl;

    public function __construct()
    {
        $this->endpoint = config('momo.endpoint');
        $this->partnerCode = config('momo.partner_code');
        $this->accessKey = config('momo.access_key');
        $this->secretKey = config('momo.secret_key');
        $this->returnUrl = config('momo.return_url');
        $this->notifyUrl = config('momo.notify_url');
    }

    /**
     * Tạo yêu cầu thanh toán MoMo
     *
     * @param Order $order
     * @return array
     */
    public function createPayment(Order $order)
    {
        $orderId = $order->order_number;
        $amount = (string) $order->total_amount;
        $orderInfo = "Thanh toán đơn hàng #{$order->order_number}";
        $requestId = time() . "";
        $extraData = base64_encode(json_encode([
            'order_id' => $order->id,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
        ]));

        // Tạo chuỗi raw signature
        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . $amount .
                   "&extraData=" . $extraData .
                   "&ipnUrl=" . $this->notifyUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $this->partnerCode .
                   "&redirectUrl=" . $this->returnUrl .
                   "&requestId=" . $requestId .
                   "&requestType=" . config('momo.request_type');

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => config('momo.store_name'),
            'storeId' => $this->partnerCode,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => config('momo.request_type'),
            'signature' => $signature
        ];

        try {
            $result = $this->execPostRequest($this->endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            Log::info('MoMo Payment Request', [
                'order_id' => $order->id,
                'request' => $data,
                'response' => $jsonResult
            ]);

            return $jsonResult;
        } catch (\Exception $e) {
            Log::error('MoMo Payment Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'resultCode' => 999,
                'message' => 'Có lỗi xảy ra khi kết nối đến MoMo'
            ];
        }
    }

    /**
     * Xác thực callback từ MoMo
     *
     * @param array $data
     * @return bool
     */
    public function verifySignature($data)
    {
        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . $data['amount'] .
                   "&extraData=" . $data['extraData'] .
                   "&message=" . $data['message'] .
                   "&orderId=" . $data['orderId'] .
                   "&orderInfo=" . $data['orderInfo'] .
                   "&orderType=" . $data['orderType'] .
                   "&partnerCode=" . $data['partnerCode'] .
                   "&payType=" . $data['payType'] .
                   "&requestId=" . $data['requestId'] .
                   "&responseTime=" . $data['responseTime'] .
                   "&resultCode=" . $data['resultCode'] .
                   "&transId=" . $data['transId'];

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        return $signature === $data['signature'];
    }

    /**
     * Thực hiện POST request
     *
     * @param string $url
     * @param string $data
     * @return string
     */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        // Disable SSL verification for local development
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Lấy thông tin trạng thái giao dịch
     *
     * @param string $orderId
     * @param string $requestId
     * @return array
     */
    public function queryTransaction($orderId, $requestId)
    {
        $rawHash = "accessKey=" . $this->accessKey .
                   "&orderId=" . $orderId .
                   "&partnerCode=" . $this->partnerCode .
                   "&requestId=" . $requestId;

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'requestId' => $requestId,
            'orderId' => $orderId,
            'signature' => $signature,
            'lang' => 'vi'
        ];

        $endpoint = str_replace('/create', '/query', $this->endpoint);

        try {
            $result = $this->execPostRequest($endpoint, json_encode($data));
            return json_decode($result, true);
        } catch (\Exception $e) {
            Log::error('MoMo Query Transaction Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return [
                'resultCode' => 999,
                'message' => 'Có lỗi xảy ra khi truy vấn giao dịch'
            ];
        }
    }
}
