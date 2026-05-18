<div class="user">
    <span class="side-sidebar-close-btn"><i class="las la-times"></i></span>

    <div class="thumb">
        <a href="{{ route('seller.profile') }}">
            <img src="{{ getAvatar(getFilePath('sellerProfile') . '/' . seller()->image, seller()->fullname ?? seller()->username) }}" alt="@lang('seller')">
        </a>
    </div>
    <div class="content">
        <h6 class="title"><a class="text--base cl-white" href="javascript:void(0)">{{ seller()->fullname }}</a></h6>
    </div>
</div>