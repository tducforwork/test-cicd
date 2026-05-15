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
                                    <th>@lang('STT')</th>
                                    <th>@lang('Tên Tag')</th>
                                    <th>@lang('Loại hiển thị')</th>
                                    <th>@lang('Preview')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $tag)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ __($tag->name) }}</td>
                                        <td><span class="badge badge--primary">{{ strtoupper($tag->type) }}</span></td>
                                        <td>
                                            <div class="p-tag {{ $tag->type }}">{{ __($tag->name) }}</div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                data-id="{{ $tag->id }}" 
                                                data-name="{{ $tag->name }}"
                                                data-type="{{ $tag->type }}">
                                                <i class="la la-pencil"></i> @lang('Sửa')
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="{{ route('admin.tag.delete', $tag->id) }}"
                                                data-question="@lang('Bạn có chắc chắn muốn xóa tag này?')">
                                                <i class="la la-trash"></i> @lang('Xóa')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('Không tìm thấy tag nào')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($tags->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($tags) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <div id="tagModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Tên Tag')</label>
                            <input type="text" class="form-control" name="name" required placeholder="Ví dụ: Rẻ vô đối">
                        </div>
                        <div class="form-group">
                            <label>@lang('Loại hiển thị')</label>
                            <select name="type" class="form-control" required>
                                <option value="orange">Orange (Cam)</option>
                                <option value="green">Green (Xanh lá)</option>
                                <option value="red">Red (Đỏ)</option>
                                <option value="purple">Purple (Tím)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Preview')</label>
                            <div id="tag-preview-container" class="d-flex justify-content-center py-3 border rounded bg-light">
                                <div class="p-tag orange" id="tag-preview">Rẻ vô đối</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Lưu lại')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i>@lang('Thêm mới')</button>
@endpush

@push('style')
<style>
    /* Bê nguyên CSS p-tag vào admin để preview */
    .p-tag {
        font-size: 9.5px;
        font-weight: 800;
        color: white;
        padding: 5px 10px;
        text-transform: uppercase;
        width: fit-content;
        border-radius: 6px 0px 6px 0px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        background-size: 300% 300% !important;
        position: relative;
        overflow: hidden;
        animation: bgMove 4s ease infinite, tagGlow 3s ease-in-out infinite;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        letter-spacing: 0.5px;
        line-height: 1;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
    }
    @keyframes tagGlow { 0%, 100% { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); } 50% { box-shadow: 0 4px 20px rgba(255, 255, 255, 0.4), 0 0 10px rgba(255, 255, 255, 0.2); } }
    .p-tag::after { content: ""; position: absolute; top: -50%; left: -100%; width: 50%; height: 200%; background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.6), transparent); transform: rotate(25deg); animation: tagShine 3s cubic-bezier(0.4, 0, 0.2, 1) infinite; }
    @keyframes bgMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    @keyframes tagShine { 0% { left: -100%; } 20% { left: 150%; } 100% { left: 150%; } }
    .p-tag.orange { background: linear-gradient(-45deg, #f59e0b, #fbbf24, #f97316, #fbbf24); }
    .p-tag.green { background: linear-gradient(-45deg, #00bfa5, #26e2c6, #10b981, #26e2c6); }
    .p-tag.red { background: linear-gradient(-45deg, #ef4444, #f87171, #dc2626, #f87171); }
    .p-tag.purple { background: linear-gradient(-45deg, #8b5cf6, #a78bfa, #7c3aed, #a78bfa); }
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        const modal = $('#tagModal');
        const action = `{{ route('admin.tag.store') }}`;

        function updatePreview() {
            const name = modal.find('[name=name]').val() || 'Preview';
            const type = modal.find('[name=type]').val();
            $('#tag-preview').text(name).attr('class', `p-tag ${type}`);
        }

        modal.find('[name=name], [name=type]').on('input change', updatePreview);

        $('.addBtn').on('click', function() {
            modal.find('.modal-title').text("Thêm Tag mới");
            modal.find('form').attr('action', `${action}/0`);
            modal.find('form')[0].reset();
            updatePreview();
            modal.modal('show');
        });

        $('.editBtn').on('click', function() {
            let data = $(this).data();
            modal.find('.modal-title').text("Cập nhật Tag");
            modal.find('form').attr('action', `${action}/${data.id}`);
            modal.find('[name=name]').val(data.name);
            modal.find('[name=type]').val(data.type);
            updatePreview();
            modal.modal('show');
        });

    })(jQuery);
</script>
@endpush
