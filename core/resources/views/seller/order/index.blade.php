@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include('seller.partials.sidebar')
            </aside>

            <div class="flex flex-col gap-6 flex-1 min-w-0">
                <!-- Title -->
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <h1 class="font-semibold text-[#272343] text-2xl">
                        @lang('Manage Orders')
                    </h1>
                </div>

                <!-- Stats grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-[24px]">
                    <!-- Pending -->
                    <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center p-[10px] bg-[#E6FFE4] rounded-[7px]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <path opacity="0.2" d="M3.54657 7.99316C3.46893 8.12651 3.42825 8.27815 3.42871 8.43245V18.9967C3.42953 19.1493 3.47058 19.299 3.5477 19.4307C3.62482 19.5624 3.7353 19.6714 3.868 19.7467L13.2966 25.0503C13.4237 25.1229 13.568 25.16 13.7144 25.1575L13.8109 13.7146L3.54657 7.99316Z" fill="#2DB324"/>
                                    <path d="M24.0001 18.9964V8.43214C23.9993 8.27954 23.9583 8.12986 23.8812 7.99818C23.804 7.86651 23.6935 7.75749 23.5609 7.68214L14.1323 2.37857C14.0052 2.30522 13.8611 2.2666 13.7144 2.2666C13.5677 2.2666 13.4236 2.30522 13.2966 2.37857L3.868 7.68214C3.7353 7.75749 3.62482 7.86651 3.5477 7.99818C3.47057 8.12986 3.42953 8.27954 3.42871 8.43214V18.9964C3.42953 19.149 3.47057 19.2987 3.5477 19.4304C3.62482 19.5621 3.7353 19.6711 3.868 19.7464L13.2966 25.05C13.4236 25.1233 13.5677 25.162 13.7144 25.162C13.8611 25.162 14.0052 25.1233 14.1323 25.05L23.5609 19.7464C23.6935 19.6711 23.804 19.5621 23.8812 19.4304C23.9583 19.2987 23.9993 19.149 24.0001 18.9964V18.9964Z" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.9641 16.3397V10.7683L8.57129 5.03613" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M23.8826 7.99316L13.8112 13.7146L3.54688 7.99316" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M13.8103 13.7139L13.7139 25.1567" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Pending')</span>
                        </div>
                        <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['pending'] }}</span>
                    </div>
                    <!-- Processing -->
                    <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center p-[10px] bg-[#D0E1FF] rounded-[7px]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="26" viewBox="0 0 36 26" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M29.6 6.4H25.6V3.2C25.6 1.44 24.16 0 22.4 0H3.2C1.44 0 0 1.44 0 3.2V17.6C0 19.36 1.44 20.8 3.2 20.8C3.2 23.456 5.344 25.6 8 25.6C10.656 25.6 12.8 23.456 12.8 20.8H22.4C22.4 23.456 24.544 25.6 27.2 25.6C29.856 25.6 32 23.456 32 20.8H33.6C34.48 20.8 35.2 20.08 35.2 19.2V13.872C35.2 13.184 34.976 12.512 34.56 11.952L30.88 7.04C30.576 6.64 30.096 6.4 29.6 6.4ZM8.00039 22.3992C7.12039 22.3992 6.40039 21.6792 6.40039 20.7992C6.40039 19.9192 7.12039 19.1992 8.00039 19.1992C8.88039 19.1992 9.60039 19.9192 9.60039 20.7992C9.60039 21.6792 8.88039 22.3992 8.00039 22.3992ZM29.5996 8.80078L32.7356 12.8008H25.5996V8.80078H29.5996ZM27.1996 22.3992C26.3196 22.3992 25.5996 21.6792 25.5996 20.7992C25.5996 19.9192 26.3196 19.1992 27.1996 19.1992C28.0796 19.1992 28.7996 19.9192 28.7996 20.7992C28.7996 21.6792 28.0796 22.3992 27.1996 22.3992Z" fill="#1F38DB"/>
                                </svg>
                            </div>
                            <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Processing')</span>
                        </div>
                        <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['processing'] }}</span>
                    </div>
                    <!-- Ready to Pickup -->
                    <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center p-[10px] bg-[#D1FFE1] rounded-[7px]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                                    <path d="M25.8004 21.6004H17.4004V8.40039H23.7604C24.3004 8.40039 24.7204 8.76039 24.9004 9.24039L27.0004 15.6004V20.4004C27.0004 21.0604 26.4604 21.6004 25.8004 21.6004Z" fill="#8BC34A"/>
                                    <path d="M17.4008 21.6012H3.00078C2.34078 21.6012 1.80078 21.0612 1.80078 20.4012V5.40117C1.80078 4.74117 2.34078 4.20117 3.00078 4.20117H16.2008C16.8608 4.20117 17.4008 4.74117 17.4008 5.40117V21.6012Z" fill="#388E3C"/>
                                    <path d="M22.2002 24.5996C23.857 24.5996 25.2002 23.2565 25.2002 21.5996C25.2002 19.9428 23.857 18.5996 22.2002 18.5996C20.5433 18.5996 19.2002 19.9428 19.2002 21.5996C19.2002 23.2565 20.5433 24.5996 22.2002 24.5996Z" fill="#37474F"/>
                                    <path d="M7.7998 24.5996C9.45666 24.5996 10.7998 23.2565 10.7998 21.5996C10.7998 19.9428 9.45666 18.5996 7.7998 18.5996C6.14295 18.5996 4.7998 19.9428 4.7998 21.5996C4.7998 23.2565 6.14295 24.5996 7.7998 24.5996Z" fill="#37474F"/>
                                    <path d="M22.2 22.8004C22.8627 22.8004 23.4 22.2631 23.4 21.6004C23.4 20.9376 22.8627 20.4004 22.2 20.4004C21.5373 20.4004 21 20.9376 21 21.6004C21 22.2631 21.5373 22.8004 22.2 22.8004Z" fill="#78909C"/>
                                    <path d="M7.80059 22.8004C8.46333 22.8004 9.00059 22.2631 9.00059 21.6004C9.00059 20.9376 8.46333 20.4004 7.80059 20.4004C7.13784 20.4004 6.60059 20.9376 6.60059 21.6004C6.60059 22.2631 7.13784 22.8004 7.80059 22.8004Z" fill="#78909C"/>
                                    <path d="M24.6008 14.9996H20.4008C20.0408 14.9996 19.8008 14.7596 19.8008 14.3996V10.1996C19.8008 9.83961 20.0408 9.59961 20.4008 9.59961H23.5808C23.8208 9.59961 24.0608 9.77961 24.1208 10.0196L25.1408 13.1396C25.1408 13.1996 25.2008 13.2596 25.2008 13.3196V14.3996C25.2008 14.7596 24.9608 14.9996 24.6008 14.9996Z" fill="#37474F"/>
                                    <path d="M13.0808 8.28125L8.34078 13.0213L6.12078 10.7412L4.80078 12.0612L8.34078 15.6012L14.4008 9.54125L13.0808 8.28125Z" fill="#DCEDC8"/>
                                </svg>
                            </div>
                            <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Ready to Pickup')</span>
                        </div>
                        <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['readyToPickup'] }}</span>
                    </div>
                    <!-- Delivered -->
                    <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center p-[10px] bg-[#D1FFE1] rounded-[7px]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M10.4622 11.7822L15.9867 6.25778L14.6178 4.84L10.4622 8.99555L8.36 6.94222L6.99111 8.31111L10.4622 11.7822ZM5.86667 15.6444C5.32889 15.6444 4.86852 15.453 4.48556 15.07C4.10259 14.687 3.91111 14.2267 3.91111 13.6889V1.95556C3.91111 1.41778 4.10259 0.957407 4.48556 0.574444C4.86852 0.191482 5.32889 0 5.86667 0H17.6C18.1378 0 18.5981 0.191482 18.9811 0.574444C19.3641 0.957407 19.5556 1.41778 19.5556 1.95556V13.6889C19.5556 14.2267 19.3641 14.687 18.9811 15.07C18.5981 15.453 18.1378 15.6444 17.6 15.6444H5.86667ZM1.95556 19.5556C1.41778 19.5556 0.957407 19.3641 0.574444 18.9811C0.191482 18.5981 0 18.1378 0 17.6V3.91111H1.95556V17.6H15.6444V19.5556H1.95556Z" fill="#39C568"/>
                                </svg>
                            </div>
                            <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Delivered')</span>
                        </div>
                        <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['delivered'] }}</span>
                    </div>
                    <!-- Rejected -->
                    <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center p-[10px] bg-[#FFDEDE] rounded-[7px]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M5.68889 13.3333L8.88889 10.1333L12.0889 13.3333L13.3333 12.0889L10.1333 8.88889L13.3333 5.68889L12.0889 4.44444L8.88889 7.64445L5.68889 4.44444L4.44444 5.68889L7.64445 8.88889L4.44444 12.0889L5.68889 13.3333ZM8.88889 17.7778C7.65926 17.7778 6.5037 17.5444 5.42222 17.0778C4.34074 16.6111 3.4 15.9778 2.6 15.1778C1.8 14.3778 1.16667 13.437 0.7 12.3556C0.233333 11.2741 0 10.1185 0 8.88889C0 7.65926 0.233333 6.5037 0.7 5.42222C1.16667 4.34074 1.8 3.4 2.6 2.6C3.4 1.8 4.34074 1.16667 5.42222 0.7C6.5037 0.233333 7.65926 0 8.88889 0C10.1185 0 11.2741 0.233333 12.3556 0.7C13.437 1.16667 14.3778 1.8 15.1778 2.6C15.9778 3.4 16.6111 4.34074 17.0778 5.42222C17.5444 6.5037 17.7778 7.65926 17.7778 8.88889C17.7778 10.1185 17.5444 11.2741 17.0778 12.3556C16.6111 13.437 15.9778 14.3778 15.1778 15.1778C14.3778 15.9778 13.437 16.6111 12.3556 17.0778C11.2741 17.5444 10.1185 17.7778 8.88889 17.7778Z" fill="#DF4C4C"/>
                                </svg>
                            </div>
                            <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Rejected')</span>
                        </div>
                        <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['rejected'] }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-[8px] p-4 md:p-6">

                    <!-- Desktop Header -->
                    <div class="hidden md:flex items-center px-5 py-3 bg-[#f8f9fa] rounded-xl mb-4 text-sm font-bold text-[#8a8a8a]">
                        <div class="w-[25%]">@lang('Order ID')</div>
                        <div class="w-[20%] text-center">@lang('Customer')</div>
                        <div class="w-[20%] text-center">@lang('Total Amount')</div>
                        <div class="w-[20%] text-center">@lang('Status')</div>
                        <div class="text-right">@lang('Actions')</div>
                    </div>

                    <!-- Orders List -->
                    <div class="flex flex-col gap-3">
                        @foreach($orders as $item)
                        <!-- Desktop Row -->
                        <div class="hidden md:flex items-center justify-between p-5 border rounded-[12px] border-[#E6E6E6] hover:bg-gray-50 transition-colors bg-white">
                            <div class="w-[25%]">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-[#333]">#{{ $item->order_number }}</span>
                                    <span class="text-sm text-[#666]">@lang('Order')</span>
                                </div>
                            </div>
                            <div class="w-[20%] text-center">
                                <span class="text-sm text-[#333]">{{ @$item->order?->user?->fullname }}</span>
                            </div>
                            <div class="w-[20%] text-center">
                                <span class="text-sm font-bold text-[#FF6F0F]">{{ showAmount($item->total_amount) }}</span>
                            </div>
                            <div class="w-[20%] text-center">
                                @php echo $item->statusBadge @endphp
                            </div>
                            <div class="text-right">
                                <a href="{{ route('seller.order.details', $item->id) }}" class="text-[#2563EB] font-bold text-sm hover:text-[#FF6F0F] transition-colors">@lang('Details')</a>
                            </div>
                        </div>

                        <!-- Mobile Card -->
                        <div class="md:hidden p-4 border border-[#E6E6E6] rounded-[12px] bg-white hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="block text-sm font-bold text-[#333]">#{{ $item->order_number }}</span>
                                    <span class="text-[14px] text-[#666]">@lang('Order')</span>
                                </div>
                                @php echo $item->statusBadge @endphp
                            </div>
                            <div class="space-y-1 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">@lang('Customer'):</span>
                                    <span class="font-medium">{{ @$item->order?->user?->fullname }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">@lang('Total Amount'):</span>
                                    <span class="font-bold text-[#FF6F0F]">{{ showAmount($item->total_amount) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('seller.order.details', $item->id) }}" class="block text-center w-full py-2 bg-[#eaf4ff] text-[#2563EB] font-bold text-sm rounded-lg hover:bg-[#FF6F0F] hover:text-white transition-colors">@lang('Details')</a>
                        </div>
                        @endforeach
                        
                        @if($orders->isEmpty())
                        <div class="py-12 text-center text-gray-500 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            @lang('No orders found')
                        </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if ($orders->hasPages())
                        <div class="mt-6">
                            {{ paginateLinks($orders) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

<x-confirmation-modal />

@endsection
