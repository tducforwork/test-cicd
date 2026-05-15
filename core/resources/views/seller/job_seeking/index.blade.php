@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include('seller.partials.sidebar')
            </aside>

            <!-- Main Content -->
            <section class="flex-1 min-w-0">
            <div class="bg-white rounded-[12px] p-6 border border-gray-100">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-[#272343]">@lang('Hồ sơ tìm việc của tôi')</h1>
        <a href="{{ route('seller.jobs.create', ['type' => 2]) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#FF6F0F] text-white font-bold text-sm rounded-[12px] shadow-[inset_0_1px_2px_0_rgba(255,255,255,0.40),inset_0_-1px_2px_0_rgba(0,0,0,0.24),0_1px_2px_0_rgba(0,0,0,0.08)] hover:bg-orange-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            @lang('Tạo hồ sơ mới')
        </a>
    </div>

    <!-- Search -->
    <form action="{{ route('seller.jobs.index', ['type' => 2]) }}" method="GET" class="mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex-1 w-full relative">
                <input type="text" name="search" value="{{ request()->search }}" placeholder="@lang('Tìm kiếm hồ sơ...')" 
                    class="w-full h-[49px] pl-12 pr-4 rounded-[12px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-[#FF6F0F]">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="w-full sm:w-auto h-[49px] px-6 bg-[#272343] text-white font-bold rounded-[12px] hover:opacity-90 transition-opacity">
                @lang('Tìm kiếm')
            </button>
        </div>
    </form>

    <!-- Job List -->
    <div class="bg-white rounded-[12px] overflow-hidden">
        <!-- Table Header -->
        <div class="hidden lg:flex items-center px-4 py-3 bg-[#f1f1f1] rounded-[12px] mb-2">
            <div class="flex-1">
                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Thông tin hồ sơ / Vị trí mong muốn')</span>
            </div>
            <div class="w-40 text-center">
                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Mức lương')</span>
            </div>
            <div class="w-40 text-center">
                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Hạn nộp')</span>
            </div>
            <div class="w-24 text-center">
                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Trạng thái')</span>
            </div>
            <div class="w-20"></div>
        </div>

        <!-- Jobs -->
        @forelse($jobs as $job)
        <div class="flex flex-col lg:flex-row lg:items-center gap-4 px-4 py-4 lg:py-3 border-b lg:border-none last:border-none lg:hover:bg-gray-50 lg:rounded-[12px] mb-1 group relative">
            <!-- Job Info -->
            <div class="flex-1 flex items-center gap-3">
                <div class="flex items-center gap-3 min-w-0 w-full">
                    <div class="w-12 h-12 shrink-0 rounded-lg bg-gray-100 flex items-center justify-center text-kviet-orange">
                        <i class="las la-user-tie text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('seller.jobs.edit', ['id' => $job->id, 'type' => 2]) }}" class="text-[15px] lg:text-sm font-bold lg:font-medium text-[#272343] hover:text-[#FF6F0F] transition-colors block lg:truncate">
                            {{ $job->title }}
                        </a>
                        <span class="text-xs text-muted block mt-0.5">
                            <i class="la la-user"></i> {{ $job->company_name }} | 
                            <i class="la la-map-marker"></i> {{ @$job->province->name ?? @$job->work_location }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap lg:contents gap-y-3">
                <!-- Salary -->
                <div class="w-1/2 lg:w-40 lg:text-center shrink-0">
                    <div class="lg:hidden text-[10px] uppercase text-muted mb-1">@lang('Mức lương')</div>
                    <span class="inline-flex items-center px-3 py-1 bg-[#eaf4ff] rounded-lg text-sm font-semibold text-[#303030]">
                        {{ $job->salary_display }}
                    </span>
                </div>

                <!-- Deadline -->
                <div class="w-1/2 lg:w-40 lg:text-center shrink-0">
                    <div class="lg:hidden text-[10px] uppercase text-muted mb-1">@lang('Hạn nộp')</div>
                    @if($job->application_deadline)
                    <span class="text-sm font-semibold lg:font-normal {{ $job->application_deadline < now() ? 'text-danger' : 'text-[#303030]' }}">
                        {{ showDateTime($job->application_deadline, 'd/m/Y') }}
                    </span>
                    @else
                    <span class="text-sm text-muted">@lang('Không giới hạn')</span>
                    @endif
                </div>

                <!-- Status -->
                <div class="w-1/2 lg:w-24 lg:text-center shrink-0">
                    <div class="lg:hidden text-[10px] uppercase text-muted mb-1">@lang('Trạng thái')</div>
                    @if ($job->status == 1)
                    <span class="inline-flex items-center justify-center px-2 py-1 bg-[#e3ffed] rounded-md text-xs font-semibold text-[#32a06e]">
                        @lang('Hiển thị')
                    </span>
                    @else
                    <span class="inline-flex items-center justify-center px-2 py-1 bg-[#fed3d1] rounded-md text-xs font-semibold text-[#ef4d2f]">
                        @lang('Ẩn')
                    </span>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="w-full lg:w-20 mt-2 lg:mt-0 pt-3 lg:pt-0 border-t lg:border-none border-dashed">
                <div class="flex items-center lg:justify-center gap-2">
                    <a href="{{ route('seller.jobs.edit', ['id' => $job->id, 'type' => 2]) }}" class="flex-1 lg:flex-none w-9 h-9 flex items-center justify-center bg-gray-100 rounded-lg hover:bg-[#FF6F0F] hover:text-white transition-colors gap-2 lg:gap-0" title="@lang('Sửa')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="lg:hidden text-sm font-medium">@lang('Chỉnh sửa')</span>
                    </a>
                    <button type="button" class="flex-1 lg:flex-none w-9 h-9 flex items-center justify-center bg-gray-100 rounded-lg hover:bg-red-500 hover:text-white transition-colors confirmationBtn gap-2 lg:gap-0" 
                        data-question="@lang('Bạn có chắc chắn muốn xóa hồ sơ này?')" 
                        data-action="{{ route('seller.jobs.delete', $job->id) }}"
                        title="@lang('Xóa')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="lg:hidden text-sm font-medium">@lang('Xóa bỏ')</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <p class="text-gray-500 text-lg">{{ __($emptyMessage ?? 'Chưa có hồ sơ tìm việc nào') }}</p>
            <a href="{{ route('seller.jobs.create', ['type' => 2]) }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-[#FF6F0F] text-white font-bold text-sm rounded-[12px] hover:bg-orange-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                @lang('Tạo hồ sơ đầu tiên')
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($jobs->hasPages())
    <div class="mt-6">
        {{ paginateLinks($jobs) }}
    </div>
    @endif
            </section>
        </div>
    </main>
</div>

<x-confirmation-modal />

@endsection

@push('script')
<script>
    'use strict';
    (function($) {
        // Keyboard shortcut (N for New)
        $(document).keypress(function(e) {
            var unicode = e.charCode ? e.charCode : e.keyCode;
            if (unicode == 78 || unicode == 110) {
                window.location = "{{ route('seller.jobs.create') }}";
            }
        });
    })(jQuery);
</script>
@endpush
