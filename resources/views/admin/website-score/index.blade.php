@extends('admin.layouts.app')

@section('title', 'Điểm Chất Lượng Website')
@section('page-title', 'Điểm Chất Lượng Website')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Điểm Website</li>
@endsection

@push('styles')
<style>
    .score-circle {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-weight: 700;
        border: 8px solid;
    }
    .score-circle .score-number {
        font-size: 3rem;
        line-height: 1;
    }
    .score-circle .score-label {
        font-size: 0.85rem;
        opacity: 0.8;
    }
    .score-excellent { border-color: #28a745; color: #28a745; }
    .score-good      { border-color: #17a2b8; color: #17a2b8; }
    .score-average   { border-color: #ffc107; color: #ffc107; }
    .score-poor      { border-color: #dc3545; color: #dc3545; }

    .category-score-bar { height: 18px; border-radius: 4px; }
    .detail-item { border-left: 3px solid #dee2e6; padding-left: 10px; margin-bottom: 8px; }
    .detail-item.passed  { border-left-color: #28a745; }
    .detail-item.failed  { border-left-color: #dc3545; }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Overall Score Card -->
    <div class="col-12 col-lg-4">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-trophy mr-1"></i> Tổng điểm</h3>
            </div>
            <div class="card-body text-center py-4">
                @php
                    $pct = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;
                    if ($pct >= 80)      { $cls = 'score-excellent'; $grade = 'Xuất sắc'; }
                    elseif ($pct >= 60)  { $cls = 'score-good';      $grade = 'Tốt'; }
                    elseif ($pct >= 40)  { $cls = 'score-average';   $grade = 'Trung bình'; }
                    else                 { $cls = 'score-poor';       $grade = 'Cần cải thiện'; }
                @endphp
                <div class="score-circle {{ $cls }} mb-3">
                    <span class="score-number">{{ $totalScore }}</span>
                    <span class="score-label">/ {{ $maxScore }}</span>
                </div>
                <h4 class="mb-1">{{ $grade }}</h4>
                <p class="text-muted mb-0">Điểm tổng: {{ $pct }}%</p>
            </div>
        </div>

        <!-- Category summary -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Phân bổ điểm</h3>
            </div>
            <div class="card-body">
                @foreach ($criteria as $key => $cat)
                @php $catPct = $cat['max'] > 0 ? round(($cat['earned'] / $cat['max']) * 100) : 0; @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small><i class="{{ $cat['icon'] }} text-{{ $cat['color'] }} mr-1"></i> {{ $cat['label'] }}</small>
                        <small class="font-weight-bold">{{ $cat['earned'] }} / {{ $cat['max'] }}</small>
                    </div>
                    <div class="progress" style="height:12px;">
                        <div class="progress-bar bg-{{ $cat['color'] }} category-score-bar"
                             role="progressbar"
                             style="width: {{ $catPct }}%"
                             aria-valuenow="{{ $catPct }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detailed criteria -->
    <div class="col-12 col-lg-8">
        @foreach ($criteria as $key => $cat)
        <div class="card card-{{ $cat['color'] }} card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="{{ $cat['icon'] }} mr-1"></i>
                    {{ $cat['label'] }}
                </h3>
                <div class="card-tools">
                    <span class="badge badge-{{ $cat['color'] }} badge-pill" style="font-size:0.9rem;">
                        {{ $cat['earned'] }} / {{ $cat['max'] }} điểm
                    </span>
                </div>
            </div>
            <div class="card-body">
                @foreach ($cat['details'] as $detail)
                <div class="detail-item {{ $detail['passed'] ? 'passed' : 'failed' }} d-flex align-items-start">
                    <div class="mr-3 mt-1">
                        @if ($detail['passed'])
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-times-circle text-danger"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $detail['name'] }}</strong>
                            <span class="text-muted">{{ $detail['value'] }}</span>
                        </div>
                        @if (!$detail['passed'])
                        <small class="text-muted"><i class="fas fa-lightbulb mr-1 text-warning"></i>{{ $detail['hint'] }}</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Tips -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Về điểm chất lượng website</h3>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    Điểm chất lượng website được tính dựa trên 4 nhóm tiêu chí, mỗi nhóm tối đa <strong>25 điểm</strong>:
                </p>
                <ul class="mb-0">
                    <li><strong>Chất lượng nội dung</strong>: sản phẩm có ảnh, mô tả đầy đủ.</li>
                    <li><strong>SEO &amp; Cấu trúc</strong>: URL thân thiện, danh mục được kích hoạt.</li>
                    <li><strong>Tương tác khách hàng</strong>: đánh giá, đơn hàng hoàn thành, tài khoản.</li>
                    <li><strong>Thiết lập cửa hàng</strong>: banner, sản phẩm nổi bật, tồn kho.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
