@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('Name')</th>
                                    <th>@lang('Products')</th>
                                    <th>@lang('Home')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr class="parent-row cursor-pointer" data-id="{{ $category->id }}">
                                        <td class="text-start">
                                            <span class="fw-bold"><i class="las la-chevron-right toggle-icon"></i> {{ __($category->name) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge--primary">{{ $category->total_products_count }}</span>
                                        </td>

                                        <td>
                                            @if($category->show_on_home)
                                                <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.category.status', $category->id) }}"
                                                    data-question="@lang('Are you sure to remove this category from homepage?')">
                                                    <i class="la la-eye"></i> @lang('Show')
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.category.status', $category->id) }}"
                                                    data-question="@lang('Are you sure to show this category on homepage?')">
                                                    <i class="la la-eye-slash"></i> @lang('Hide')
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                data-slug="{{ $category->slug }}" data-parent_id="{{ $category->parent_id }}">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="{{ route('admin.category.delete', $category->id) }}"
                                                data-question="@lang('Are you sure to delete this category?')">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </td>
                                    </tr>
                                    @foreach ($category->allSubcategories as $subcategory)
                                        <tr class="child-row child-of-{{ $category->id }} d-none">
                                            <td class="text-start">
                                                <span class="name text-muted ps-4"> <i class="las la-level-up-alt la-rotate-90"></i> {{ __($subcategory->name) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge--info">{{ $subcategory->total_products_count }}</span>
                                            </td>

                                            <td>
                                                @if($subcategory->show_on_home)
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.category.status', $subcategory->id) }}"
                                                        data-question="@lang('Are you sure to remove this category from homepage?')">
                                                        <i class="la la-eye"></i> @lang('Show')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.category.status', $subcategory->id) }}"
                                                        data-question="@lang('Are you sure to show this category on homepage?')">
                                                        <i class="la la-eye-slash"></i> @lang('Hide')
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                    data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->name }}"
                                                    data-slug="{{ $subcategory->slug }}" data-parent_id="{{ $subcategory->parent_id }}">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.category.delete', $subcategory->id) }}"
                                                    data-question="@lang('Are you sure to delete this category?')">
                                                    <i class="la la-trash"></i> @lang('Delete')
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'No category found') }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <div id="categoryModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Biểu tượng (Ảnh/SVG)')</label>
                            <x-image-uploader name="image" type="category" :required="false" />
                        </div>
                        <div class="form-group">
                            <label>@lang('Parent Category')</label>
                            <select name="parent_id" class="form-control">
                                <option value="">@lang('None')</option>
                                @foreach ($allCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ __($cat->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Slug')</label>
                            <input type="text" class="form-control" name="slug">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Icon') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control iconPicker icon" autocomplete="off" name="icon" required>
                                <span class="input-group-text  input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Icon Color') }}</label>
                            <div class="input-group">
                                <span class="input-group-text p-0 border-0">
                                    <input type='text' class="form-control colorPicker" value="">
                                </span>
                                <input type="text" class="form-control colorCode" name="icon_color" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Background Color') }}</label>
                            <div class="input-group">
                                <span class="input-group-text p-0 border-0">
                                    <input type='text' class="form-control colorPicker" value="">
                                </span>
                                <input type="text" class="form-control colorCode" name="bg_color" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const modal = $('#categoryModal');
            const action = `{{ route('admin.category.store') }}`;

            $('.addBtn').on('click', function() {
                modal.find('.modal-title').text("@lang('Add Category')");
                modal.find('form').attr('action', `${action}/0`);
                modal.find('form')[0].reset();
                modal.find('[name=parent_id]').val('');
                modal.find('.image-upload-preview').css('background-image', 'none');
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                let data = $(this).data();
                modal.find('.modal-title').text("@lang('Update Category')");
                modal.find('form').attr('action', `${action}/${data.id}`);
                modal.find('[name=name]').val(data.name);
                modal.find('[name=slug]').val(data.slug);
                modal.find('[name=parent_id]').val(data.parent_id);

                $.get(`{{ route('admin.category.get.single', '') }}/${data.id}`, function(response) {
                    if (response.category) {
                        modal.find('.image-upload-preview').css('background-image',
                            `url(${response.category.image_path})`);
                        modal.find('[name=icon]').val(response.category.icon);
                        modal.find('[name=icon_color]').val(response.category.icon_color);
                        modal.find('[name=bg_color]').val(response.category.bg_color);

                        modal.find('.iconpicker-container .iconpicker-item i').attr('class', response.category.icon);
                        
                        modal.find('[name=icon_color]').parents('.input-group').find('.colorPicker').spectrum({
                            color: response.category.icon_color,
                        });
                        modal.find('[name=bg_color]').parents('.input-group').find('.colorPicker').spectrum({
                            color: response.category.bg_color,
                        });
                    }
                });

                modal.modal('show');
            });

            // Toggle subcategories
            $('.parent-row').on('click', function(e) {
                if ($(e.target).closest('button').length) return; // Don't toggle if clicking buttons

                let id = $(this).data('id');
                let icon = $(this).find('.toggle-icon');
                $(`.child-of-${id}`).toggleClass('d-none');
                
                if ($(`.child-of-${id}`).hasClass('d-none')) {
                    icon.css('transform', 'rotate(0deg)');
                } else {
                    icon.css('transform', 'rotate(90deg)');
                }
            });

            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`${e.iconpickerValue}`);
            });

            $('.colorPicker').each(function() {
                var $picker = $(this);
                var $input = $picker.closest('.input-group').find('.colorCode');
                $picker.spectrum({
                    color: $picker.val(),
                    change: function(color) {
                        $input.val(color.toHexString());
                    },
                    move: function(color) {
                        $input.val(color.toHexString());
                    }
                });
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

        })(jQuery);
    </script>
@endpush

@push('style')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .toggle-icon {
        transition: transform 0.3s;
        display: inline-block;
        width: 20px;
    }
    .colorPicker {
        width: 50px !important;
        height: 45px !important;
        padding: 0 !important;
        border: none !important;
    }
</style>
@endpush
