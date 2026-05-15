@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.sidebar')
            </aside>

            <!-- Main Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-6">
                    <h1 class="text-2xl font-bold text-[#272343]">{{ __($pageTitle) }}</h1>
                </div>
                <!-- Profile Section -->
                <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                    <form method="POST" action="{{ route('user.data.submit') }}" class="flex flex-col gap-6" id="userDataForm">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                placeholder="Nhập họ và tên của bạn" required>
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Số điện thoại <span class="text-red-500">*</span></label>
                            <div>
                                <input type="number" name="mobile" value="{{ old('mobile') }}"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    placeholder="Nhập số điện thoại" required>
                            </div>
                        </div>

                        <!-- Province / Ward -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Tỉnh/Thành phố <span class="text-red-500">*</span></label>
                                <select name="province_id"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none select2"
                                    required>
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ __($province->full_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Phường/Xã <span class="text-red-500">*</span></label>
                                <select name="ward_id"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none select2"
                                    required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Địa chỉ cụ thể <span class="text-red-500">*</span></label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none resize-none"
                                placeholder="Nhập địa chỉ cụ thể của bạn" required>
                        </div>

                        <!-- Submit -->
                        <div>
                            <button type="submit"
                                class="text-[#FFF] hover:opacity-90 transition-opacity text-[16px] font-semibold leading-[24px] rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] flex px-[18px] py-[10px] justify-center items-center gap-[8px] w-full md:w-auto">
                                Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>

            </div>{{-- end main content --}}
        </div>{{-- end flex row --}}
    </main>
</div>
@endsection

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
<style>
    .select2-container--default .select2-selection--single {
        height: 52px;
        border-color: #E6E6E6;
        border-radius: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-left: 16px;
        color: #272343;
        height: 52px !important;
        display: flex;
        align-items: center;
        font-size: 16px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 52px;
        right: 12px;
    }

    label.error {
        color: #ef4444;
        font-size: 14px;
        margin-top: 4px;
        display: block;
    }

    input.error {
        border-color: #ef4444 !important;
    }
</style>
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endpush

@push('script')
<script>
    "use strict";
    (function($) {

        $('.select2').select2({
            dropdownParent: $('.card-body').length ? $('.card-body') : $(document.body)
        });

        $('select[name=province_id]').on('change', function() {
            var provinceId = $(this).val();
            var wardSelect = $('select[name=ward_id]');
            wardSelect.empty().append('<option value="">Chọn Phường/Xã</option>');
            if (provinceId) {
                $.get('{{ route("user.get.wards", "") }}/' + provinceId, function(data) {
                    $.each(data, function(index, ward) {
                        wardSelect.append('<option value="' + ward.id + '">' + ward.full_name + '</option>');
                    });
                    wardSelect.trigger('change');
                });
            }
        });

        $('#userDataForm').validate({
            rules: {
                name: "required",
                mobile: "required",
                province_id: "required",
                ward_id: "required",
                address: "required",
            },
            messages: {
                name: "Vui lòng nhập họ tên",
                mobile: "Vui lòng nhập số điện thoại",
                province_id: "Vui lòng chọn Tỉnh/Thành phố",
                ward_id: "Vui lòng chọn Phường/Xã",
                address: "Vui lòng nhập địa chỉ cụ thể",
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

    })(jQuery);
</script>
@endpush