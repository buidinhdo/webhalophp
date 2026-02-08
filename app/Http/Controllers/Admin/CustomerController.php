<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Lấy khách hàng từ bảng users
        $query = User::withCount('orders');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->latest()->paginate(20);
        
        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::with('orders')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        // Loại bỏ password nếu không nhập
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Thông tin khách hàng đã được cập nhật!');
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        // Kiểm tra xem có phải admin không
        if ($customer->email === 'admin@haloshop.com') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Không thể xóa tài khoản admin!');
        }
        
        // Xóa khách hàng (các đơn hàng sẽ giữ lại với user_id = null)
        $customer->orders()->update(['user_id' => null]);
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Khách hàng đã được xóa thành công!');
    }
}
