@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="breadcrumb-section" style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
        <div class="container">
            <a href="{{ route('home') }}" style="color: #666; font-size: 14px">@lang('Trang chủ')</a>
            <span style="margin: 0 10px; color: #ccc">/</span>
            <a href="{{ route('seller.home') }}" style="color: #666; font-size: 14px">@lang('Kênh người bán')</a>
            <span style="margin: 0 10px; color: #ccc">/</span>
            <span style="color: var(--primary); font-weight: 600; font-size: 14px">@lang('Quản lý sản phẩm')</span>
        </div>
    </div>

    <!-- MAIN SECTION -->
    <section class="profile-section py-lg-5 py-4">
        <div class="container">
            <div class="profile-container">
                <!-- Sidebar -->
                <aside class="profile-sidebar">
                    @include('seller.partials.sidebar')
                </aside>

                <!-- Main Content -->
                <main class="profile-main-content">
                    <div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2>@lang('Quản lý sản phẩm')</h2>
                            <p style="color: var(--text-muted); font-size: 14px;">
                                @lang('Danh sách sản phẩm của cửa hàng bạn')
                            </p>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <button class="btn-excel" onclick="triggerImport()">
                                <i class="fa-solid fa-file-excel" style="margin-right: 8px;"></i> NHẬP TỪ EXCEL
                            </button>
                            <a href="{{ route('seller.products.create') }}" class="btn btn-primary"
                                style="padding: 10px 24px; background: var(--primary); border: none; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-plus"></i> @lang('THÊM SẢN PHẨM MỚI')
                            </a>
                        </div>
                    </div>

                    <!-- Toolbar & Filters -->
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3 flex-wrap gap-3">
                        <form action="{{ route('seller.products.all') }}" method="GET" class="search-box-custom">
                            <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8;"></i>
                            <input type="text" name="search" value="{{ request()->search }}"
                                placeholder="@lang('Tìm theo tên sản phẩm...')">
                        </form>
                         <div class="d-flex gap-3 align-items-center">
                            <select class="form-select" style="font-size: 13px; width: 180px; border-radius: 10px;">
                                <option selected>Tất cả trạng thái</option>
                                <option>Đang bán (Đã duyệt)</option>
                                <option>Chờ phê duyệt</option>
                                <option>Bị từ chối</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="product-list-card premium-card">
                        <div class="order-table-wrapper">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">@lang('Ảnh')</th>
                                        <th>@lang('Tên sản phẩm')</th>
                                        <th>@lang('Giá bán')</th>
                                        <th>@lang('Kho')</th>
                                        <th>@lang('Trạng thái')</th>
                                        <th style="text-align: right; width: 100px;">@lang('Thao tác')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$product->main_image, getFileSize('product')) }}"
                                                    class="product-img-td"
                                                    style="width: 70px; height: 70px; border-radius: 8px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <div style="font-weight: 600; color: var(--primary); line-clamp: 2;">
                                                    {{ __($product->name) }}
                                                </div>
                                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
                                                    @lang('Thương hiệu'): {{ __($product->brand->name ?? 'N/A') }} |
                                                    @lang('Danh mục'): {{ __($product->category->name ?? 'N/A') }}
                                                </div>
                                            </td>
                                            <td style="font-weight: 700; color: var(--accent);">
                                                {{ showAmount($product->base_price, 0, true, false, false) }}
                                            </td>
                                            <td style="font-weight: 600;">
                                                @if ($product->track_inventory)
                                                    {{ optional($product->stocks)->sum('quantity') ?? 0 }}
                                                @else
                                                    ∞
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->status == 1)
                                                    <span class="badge-status badge-approved">@lang('Đang bán')</span>
                                                @else
                                                    <span class="badge-status badge-pending">@lang('Chờ duyệt')</span>
                                                @endif
                                            </td>
                                            <td style="text-align: right; overflow: visible;">
                                                <div class="dropdown" style="position: relative; display: inline-block;">
                                                    <button class="btn-action-menu" type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        style="background: none; border: none; font-size: 16px; padding: 5px 10px; color: #64748b; cursor: pointer;">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                                        <li>
                                                            <a class="dropdown-item-custom"
                                                                href="{{ route('seller.products.edit', $product->id) }}">
                                                                <i class="fa-solid fa-pen-to-square"></i> @lang('Sửa sản phẩm')
                                                            </a>
                                                        </li>
                                                        @if ($product->track_inventory)
                                                            <li>
                                                                <a class="dropdown-item-custom"
                                                                    href="{{ route('seller.products.stock.create', $product->id) }}">
                                                                    <i class="fa-solid fa-cubes"></i> @lang('Quản lý kho')
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <button class="dropdown-item-custom btn-status-toggle" type="button"
                                                                data-id="{{ $product->id }}">
                                                                @if($product->status == 1)
                                                                    <i class="fa-solid fa-eye-slash"></i> @lang('Ẩn sản phẩm')
                                                                @else
                                                                    <i class="fa-solid fa-eye"></i> @lang('Hiện sản phẩm')
                                                                @endif
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider"
                                                                style="margin: 4px 0; border-color: #f1f5f9;">
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item-custom text-danger confirmationBtn"
                                                                type="button"
                                                                data-question="@lang('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này?')"
                                                                data-action="{{ route('seller.products.delete', $product->id) }}">
                                                                <i class="fa-solid fa-trash-can"></i> @lang('Xóa sản phẩm')
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fa-regular fa-folder-open"
                                                    style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                                                @lang('Không tìm thấy sản phẩm nào.')
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="pagination-container d-flex justify-content-center align-items-center px-4 py-3">
                                {{ paginateLinks($products) }}
                            </div>
                        @endif
                    </div>
                </main>
            </div>
        </div>
    </section>

    <x-confirmation-modal />

@endsection

@push('script')
    <script>
        (function ($) {
            'use strict';

            // Toggle Status AJAX
            $('.btn-status-toggle').on('click', function (e) {
                e.preventDefault();
                let btn = $(this);
                let id = btn.data('id');

                btn.prop('disabled', true).css('opacity', '0.5');

                $.ajax({
                    url: "{{ route('seller.products.status', '') }}/" + id,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            notify('success', response.message || 'Cập nhật trạng thái thành công');
                            setTimeout(function () {
                                location.reload();
                            }, 800);
                        } else {
                            notify('error', response.message || 'Có lỗi xảy ra');
                            btn.prop('disabled', false).css('opacity', '1');
                        }
                    },
                    error: function () {
                        notify('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                        btn.prop('disabled', false).css('opacity', '1');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush