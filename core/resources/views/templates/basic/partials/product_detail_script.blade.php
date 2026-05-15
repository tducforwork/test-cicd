<script>
    (function ($) {
        "use strict";

        Fancybox.bind("[data-fancybox]", {
            // Fancybox options
        });

        // Swiper Gallery Logic
        const swiperGallery = new Swiper('#productGallerySwiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            on: {
                slideChange: function() {
                    const activeIndex = this.activeIndex;
                    const totalSlides = this.slides.length;
                    $('#galleryCountText').text(`${activeIndex + 1}/${totalSlides}`);
                }
            }
        });

        // View All Fancybox Trigger
        $('#viewAllBtn').on('click', function() {
            const activeSlide = swiperGallery.slides[swiperGallery.activeIndex];
            const img = activeSlide.querySelector('img');
            if (img) {
                img.click();
            }
        });

        // Check Description Height and Initialize Toggle
        const $wrapper = $('#descWrapper');
        const $inner = $wrapper.find('.prose');
        const $toggleBtn = $('#toggleDescBtn');
        
        // Function to handle description height check
        const checkDescHeight = () => {
            const contentHeight = $inner.outerHeight();
            if (contentHeight <= 400) {
                $wrapper.css('max-height', 'none');
                $wrapper.find('.description-overlay').hide();
                $toggleBtn.parent().hide();
            } else {
                $wrapper.css('max-height', '400px');
                $wrapper.find('.description-overlay').show();
                $toggleBtn.parent().show();
            }
        };

        // Run on initial load
        checkDescHeight();
        
        // Also run when images are fully loaded to get accurate height
        $(window).on('load', checkDescHeight);

        $toggleBtn.on('click', function() {
            $wrapper.toggleClass('expanded');
            
            if ($wrapper.hasClass('expanded')) {
                $wrapper.css('max-height', 'none');
                $(this).html('@lang('Thu gọn') <i class="fa-solid fa-chevron-up"></i>');
            } else {
                $wrapper.css('max-height', '400px');
                $(this).html('@lang('Xem thêm') <i class="fa-solid fa-chevron-down"></i>');
                $('html, body').animate({
                    scrollTop: $wrapper.offset().top - 100
                }, 500);
            }
        });

    })(jQuery);
</script>
