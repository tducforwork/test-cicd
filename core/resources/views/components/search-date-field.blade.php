<div class="input-group w-auto flex-fill">
    <input name="date" type="text" class="datepicker-here form-control bg--white pe-2 date-range"
        placeholder="@lang('Ngày bắt đầu - Ngày kết thúc')" autocomplete="off" value="{{ request()->date }}" readonly
        style="width:250px">
    <button class="btn btn--primary input-group-text date-range-trigger" type="button"><i
            class="la la-calendar"></i></button>
</div>

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush
@push('script')
    <script>
        (function ($) {
            "use strict"

            const datePicker = $('.date-range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                showDropdowns: true,
                maxDate: moment()
            });
            const changeDatePickerText = (event, startDate, endDate) => {
                $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
            }


            $('.date-range').on('apply.daterangepicker', (event, picker) => {
                changeDatePickerText(event, picker.startDate, picker.endDate);
                $(event.target).closest('form').submit();
            });


            $('.date-range-trigger').on('click', function () {
                $(this).siblings('.date-range').click();
            });

            if ($('.date-range').val()) {
                let dateRange = $('.date-range').val().split(' - ');
                $('.date-range').data('daterangepicker').setStartDate(new Date(dateRange[0]));
                $('.date-range').data('daterangepicker').setEndDate(new Date(dateRange[1]));
            }

        })(jQuery)
    </script>
@endpush