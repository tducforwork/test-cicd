<script>
    (function($) {
        "use strict";
        var product_id = 0;

        /*
        ==========TRIGGER NECESSARY FUNCTIONS==========
         */
        background();
        backgroundColor();
        triggerOwl();
        getCompareData();
        getCartData();
        getCartTotal();
        getCartTotal();
        getWishlistTotal();

        $(".selectLanguage").on("change", function() {
            window.location.href = "{{ route('home') }}/change/" + $(this).val();
        });

        /*
        ==========PRODUCT QUICK VIEW ON MODAL==========
         */
        $(document).on('click', '.quick-view-btn', function() {

            var modal = $('#quickView');
            var product_id = $(this).data('product');
            $.ajax({
                url: "{{ route('quick.view') }}",
                method: "get",
                data: {
                    id: $(this).data('product')
                },
                success: function(response) {
                    modal.find('.modal-body').html(response);
                    background();
                    backgroundColor();
                    triggerOwl();
                }
            });
            modal.modal('show');
        });

        /*
        ==========QUANTITY BUTTONS FUNCTIONALITIES==========
        */
        $(document).on("click", ".qtybutton", function() {
            var $button = $(this);
            $button.parent().find('.qtybutton').removeClass('active')
            $button.addClass('active');
            var oldValue = $button.parent().find("input").val();
            if ($button.hasClass('inc')) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }
            $button.parent().find("input").val(newVal);
        });

        /*
        ==========FUNCTIONALITIES BEFORE ADD TO CART==========
        */

        /*------VARIANT FUNCTIONALITIES-----*/
        $(document).on('click', '.attribute-btn', function() {
            var btn = $(this);
            var ti = btn.data('ti'); // Track Inventory
            var count_total = btn.data('attr_count');
            var discount = btn.data('discount');
            var product_id = btn.data('product_id');
            var attr_data_size = btn.data('size');
            var attr_data_color = btn.data('bg');
            var attr_arr = [];
            var base_price = parseFloat(btn.data('base_price'));
            var extra_price = 0;
            btn.parents('.attr-area:first').find('.attribute-btn').removeClass('active');
            btn.addClass('active');

            if (btn.data('type') == 2 || btn.data('type') == 3) {
                $.ajax({
                    url: "{{ route('product.variant.image') }}",
                    method: "GET",
                    data: {
                        'id': btn.data('id')
                    },
                    success: function(data) {
                        if (!data.error) {
                            btn.parents('.product-details-wrapper').find('.variant-images').html(data);
                            initZoom($('.variant-images').find('.zoom_img'));
                            triggerOwl();
                        }
                    }
                });
            }

            if ($(document).find('.attribute-btn.active').length == count_total) {
                var activeAttributes = $(document).find('attribute-btn.active');

                $(document).find('.attribute-btn.active').each(function(key, attr) {
                    extra_price += parseFloat($(attr).data('price'));
                    var id = $(attr).data('id');
                    attr_arr.push(id.toString());
                });

                var selectedAttributes = JSON.stringify(attr_arr.sort());
                if (ti == 1) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('product.variant.stock') }}",
                        data: {
                            product_id: product_id,
                            attr_id: selectedAttributes
                        },
                        dataType: "json",
                        success: function(response) {
                            $('.product-sku').text(`${response.sku}`);
                            $('.stock-qty').text(`${response.quantity}`);
                            if (response.quantity == 0) {
                                $('.stock-status').addClass('badge--danger').removeClass('badge--success');
                                notify('error', 'Out of stock');
                                $('.cart-add-btn').attr('disabled', true);
                            } else {
                                $('.stock-status').addClass('badge--success').removeClass('badge--danger');
                                $('.cart-add-btn').attr('disabled', false);
                            }
                        }
                    });
                } else {
                    $('.stock-status').addClass('d-none');
                }
            }

            if (extra_price > 0) {
                base_price += extra_price;
                $('.price-data').text(base_price.toFixed(2));
                $('.special_price').text(base_price.toFixed(2) - discount);

            } else {
                $('.price-data').text(base_price.toFixed(2));
                $('.special_price').text(base_price.toFixed(2) - discount);
            }

        });


        /*
        ==========FUNCTIONALITIES AFTER ADD TO CART==========
        */

        /*------ADD TO CART (GLOBAL)-----*/
        $(document).on('click', '.btn-add-to-cart', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const productId = $btn.data('id');
            // Lấy số lượng từ input nếu có (ở trang chi tiết), nếu không mặc định là 1 (ở trang danh mục)
            let quantity = $('#quantity-input').val();
            if(!quantity) quantity = 1;

            if ($btn.is(':disabled')) return;
            $btn.prop('disabled', true);

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                url: "{{ route('add-to-cart') }}",
                method: "POST",
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        getCartData(true); // Truyền true để tự động mở Sidebar
                        getCartTotal();
                        notify('success', response.success);
                    } else {
                        notify('error', response);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    notify('error', 'Something went wrong');
                }
            });
        });

        /*------BUY NOW (GLOBAL)-----*/
        $(document).on('click', '.btn-buy-now', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const productId = $btn.data('id');
            let quantity = $('#quantity-input').val();
            if(!quantity) quantity = 1;

            if ($btn.is(':disabled')) return;
            $btn.prop('disabled', true);

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                url: "{{ route('add-to-cart') }}",
                method: "POST",
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        window.location.href = "{{ route('shopping-cart') }}";
                    } else {
                        notify('error', response.error || response);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    notify('error', "@lang('Something went wrong')");
                }
            });
        });

        /*------QUANTITY PLUS/MINUS (GLOBAL)-----*/
        $(document).on('click', '.btn-plus', function () {
            var input = $('#quantity-input');
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                input.val(currentVal + 1);
            }
        });

        $(document).on('click', '.btn-minus', function () {
            var input = $('#quantity-input');
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                input.val(currentVal - 1);
            }
        });

        /*------REMOVE PRODUCTS FROM CART-----*/
        $(document).on('click', '.remove-cart-item', function(e) {
            e.preventDefault();
            var btn = $(this);
            var id = btn.data('id');


            var url = `{{ route('remove-cart-item', '') }}/${id}`;
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                url: url,
                method: "POST",
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                        getCartData();
                        getCartTotal();
                    } else {
                        notify('error', response.error);
                    }
                },
                error: function() {
                    $('#side-cart-overlay').removeClass('loading-active');
                }
            });
        });


        /*------REMOVE APPLIED COUPON FROM CART-----*/
        $(document).on('click', '.remove-coupon', function(e) {
            var btn = $(this);
            var url = '{{ route('removeCoupon', '') }}';
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                url: url,
                method: "POST",
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                        getCartData();

                        $('.coupon-amount-total').hide('slow');
                        $('input[name=coupon_code]').val('')
                    }
                }
            });
        });

        /*
        ==========WISHLIST FUNCTIONALITIES==========
        */

        /* ADD TO WISHLIST */
        $(document).on('click', '.add-to-wish-list', function() {
            const $btn = $(this);
            const productId = $btn.data('id');

            $.ajax({
                url: "{{ route('wishlist.add') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId
                },
                success: function(response) {
                    if (response.status) {
                        $(`.add-to-wish-list[data-id="${productId}"]`).addClass('active');
                        getWishlistTotal();
                        notify('success', response.message);
                    } else {
                        notify('error', response.message);
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 401) {
                        notify('error', 'Please login to add to wishlist');
                    } else {
                        notify('error', 'Something went wrong');
                    }
                }
            });
        });

        //ADD TO Compare
        $(document).on('click', '.add-to-compare', function() {
            var product_id = $(this).data('id');
            var products = $(`.add-to-compare[data-id="${product_id}"]`);

            var data = {
                product_id: product_id
            }

            if ($(this).hasClass('active')) {
                notify('error', 'Already in the comparison list');
            } else {
                $.ajax({
                    url: "{{ route('addToCompare') }}",
                    method: "get",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            getCompareData();
                            $.each(products, function(i, v) {
                                if (!$(v).hasClass('active')) {
                                    $(v).addClass('active');
                                }
                            });
                            notify('success', response.success);
                        } else {
                            notify('error', response.error);
                        }
                    }
                });
            }
        });

        /* COPY URL TO CLIPBOARD */
        $(document).on('click', '.btn-share-url', function() {
            var url = window.location.href;
            var tempInput = document.createElement("input");
            tempInput.value = url;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            notify('success', "@lang('URL copied to clipboard')");
        });

    })(jQuery);


    function getWishlistTotal() {
        $.ajax({
            url: "{{ route('get-wishlist-total') }}",
            method: "get",
            success: function(response) {
                $('.wishlist-count').text(response);
            }
        });
    }

    function getCartTotal() {
        $.ajax({
            url: "{{ route('get-cart-total') }}",
            method: "get",
            success: function(response) {
                $('.cart-count').text(response);
            }
        });
    }

    function getCartData(showSidebar = false) {

        $.ajax({
            url: "{{ route('get-cart-data') }}",
            method: "get",
            success: function(response) {
                // Cập nhật nội dung sidebar
                $('#side-cart-items').html(response);
                
                // Cập nhật tổng tiền và số lượng từ các hidden inputs trong partial
                const subtotal = $('#ajax-cart-subtotal').val();
                const count = $('#ajax-cart-count').val();
                
                if(subtotal !== undefined) $('#side-cart-subtotal').text(subtotal);
                if(count !== undefined) $('#side-cart-count').text(count);


                // Tự động mở sidebar nếu được yêu cầu
                if(showSidebar) {
                    if(typeof toggleSideCart === 'function') {
                        toggleSideCart(true);
                    }
                }
            },
            error: function() {
                 $('#side-cart-overlay').removeClass('loading-active');
            }
        });
    }

    function backgroundColor() {
        var customBg2 = $('.product-single-color');
        customBg2.css('background', function() {
            var bg = ('#' + $(this).data('bg'));
            return bg;
        });
    }

    function background() {
        var img = $('.bg_img');
        img.css('background-image', function() {
            var bg = ('url(' + $(this).data('background') + ')');
            return bg;
        });
    }

    function getCompareData() {
        $.ajax({
            url: "{{ route('get-compare-data') }}",
            method: "get",
            success: function(response) {
                $('.compare-count').text(response.total);
            }
        });
    }
</script>
