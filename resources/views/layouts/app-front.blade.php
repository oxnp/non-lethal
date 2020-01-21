<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (isset($meta_title))
        <title>{{$meta_title}}</title>
    @endif
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,500,600,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600 ,700&display=swap" rel="stylesheet">
    <link href="/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/owl.carousel.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/front.css"/>
    <script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="/js/popper.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script src="https://cdn.paddle.com/paddle/paddle.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/base.min.js"></script>
    <script src="https://kit.fontawesome.com/7dfad927b2.js"></script>
    <script type="text/javascript" src="/js/functions.js"></script>
</head>
<body>
@include('layouts.app-front-header')
@yield('app-front-content')
@include('layouts.app-front-footer')
<?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/public/images/svgs.svg');?>

<script>
    Paddle.Setup({
        vendor: 14688
    });
    jQuery(document).ready(function () {
        /*Prices append*/
        jQuery('.upgrade_select').each(function () {
            jQuery(this).find('option').each(function () {
                var $option = $(this);
                var paddleUpgradePID = parseInt($option.val());
                if (!isNaN(paddleUpgradePID) && paddleUpgradePID > 0) {
                    Paddle.Product.Prices(paddleUpgradePID, function (prices) {
                        $option.append(' \u00A0\u00A0\u00A0 ' + prices.price.net);
                    });
                }
            })
        });
        /*Cancel subscription*/
        jQuery('#license-tables .btn_subscription_queue_cancel').click(function (event) {
            var confirmed = confirm('Please confirm the cancellation of this subscription.');
            if (!confirmed) {
                event.preventDefault();
            }
        });
        /*?????????????*/
        jQuery('#license-tables .btn_subscription_cancel').click(function (event) {
            event.preventDefault();
            var confirmed = confirm('Please confirm the cancellation of this subscription.');
            if (!confirmed)
                return;
            Paddle.Spinner.show();
            var that = jQuery(this);
            jQuery.ajax({
                url: 'index.php?option=com_jappactivation&task=user.getToken',
                dataType: 'json'
            }).done(function (response) {
                var formToken = response.formToken;
                if (formToken) {
                    var cancellationData = {};
                    cancellationData[formToken] = 1;
                    cancellationData['paddle_sid'] = that.data('paddle_sid');
                    jQuery.ajax({
                        url: 'index.php?option=com_jappactivation&task=user.cancelPaddleSubscriptionAJAX',
                        method: 'POST',
                        data: cancellationData
                    }).done(function (response) {
                        var decodedResponse = jQuery.parseJSON(response);
                        if (decodedResponse.success == true) {
                            setTimeout(function () {
                                location.reload();
                            }, 7000);
                        } else {
                            alert('Cancellation failed, please try again later or contact us for assistance.');
                        }
                    }).fail(function () {
                        Paddle.Spinner.hide();
                        alert('Cancellation failed, please try again later or contact us for assistance.');
                    });
                } else {
                    Paddle.Spinner.hide();
                    alert('Cancellation failed, please try again later or contact us for assistance.');
                }
            }).fail(function () {
                Paddle.Spinner.hide();
                alert('Cancellation failed, please try again later or contact us for assistance.');
            });
        });


        /*Upgrade select*/
        jQuery('#license-tables .upgrade_select').change(function () {
            var licenseActive = jQuery(this).closest('tr').first('td').find('code').hasClass('active');
            if (licenseActive) {
                alert("License currently in use, please open app preferences, switch to the licensing tab and click 'Revoke'.");
                jQuery(this).val(0);
                return;
            }
            var selectedOption = jQuery('option:selected', this);
            var upgradeSerial = jQuery(this).data('upgradeserial');
            var upgradeiLok = jQuery(this).data('upgradeilok');
            var upgradeWithCode = selectedOption.val() === 'code';
            if (upgradeWithCode) {
                jQuery('#activation').modal('show');
                return;
            }
            var paddleProductID = jQuery(this).val();
            if (paddleProductID > 0) {
                var email = 'andrii.kondratiev.v@gmail.com';
                var postcode = '';
                var passthroughData = {};
                passthroughData['upgradeiLok'] = upgradeiLok;
                passthroughData['upgradeSerial'] = upgradeSerial;
                passthroughData['email'] = email;
                var passthroughDataJSON = JSON.stringify(passthroughData);
                var passthroughDataB64 = $.base64.encode(passthroughDataJSON);
                console.log('Will call paddle with data:');
                console.log(passthroughData);
                console.log('productid: ' + paddleProductID)
                console.log('passthrough: ' + passthroughDataB64);
                var paddleOptions = {
                    product: paddleProductID,
                    email: email,
                    passthrough: passthroughDataB64,
                    postcode: postcode,
                    successCallback: checkoutSuccess,
                    closeCallback: finishCheckout,
                    allowQuantity: false
                };
                var isSubscription = selectedOption.data('subscription');
                if (isSubscription) {
                    var modalTerms = jQuery('[data-remodal-id=subscription]').remodal();
                    modalTerms.open();
                    jQuery(document).one('closed', '.remodal.subscription', function (e) {
                        if (e.reason == 'confirmation') {
                            Paddle.Checkout.open(paddleOptions);
                        } else {
                            finishCheckout();
                        }
                    });
                } else {
                    Paddle.Checkout.open(paddleOptions);
                }
            }
        });

        function checkoutSuccess() {
            Paddle.Spinner.show();
            setTimeout(function () {
                location.reload();
            }, 7000);
        }

        function finishCheckout() {
            jQuery('select').val(0);
        }
    });
</script>
@if(!Auth::guest())
    <script>
        var selectedProduct = null;
        var userData = null;
        var isSubscription = false;
        var checkoutData = null;

        jQuery(document).ready(function () {

            var formToken = '{{csrf_token()}}';
            if (formToken) {
                var data = {};
                data[formToken] = 1;
            }

            jQuery('a[data-product]').on('click', function () {
                var that = jQuery(this);

                Paddle.Spinner.show();

                selectedProduct = jQuery(this).data('product');
                isSubscription = jQuery(this).data('subscription');

                var formToken = '{{csrf_token()}}';
                if (formToken) {
                    // Append form token as login credential
                    var data = {};
                    data['_token'] = '{{csrf_token()}}';
                    data['paddle_pid'] = selectedProduct;

                    jQuery.ajax({
                        url: '{{route('getProductPublishedState')}}',
                        method: 'post',
                        data: data,
                        dataType: 'json'
                    })
                        .done(function (response) {
                            if (!response.isPublished) {
                                alert(response.message);
                                return;
                            }
                            startCheckout();
                        })
                        .fail(function () {
                            alert('This purchase is temporary not available, please try again later');
                        })
                        .always(function () {
                            Paddle.Spinner.hide();
                        });
                } else {
                    Paddle.Spinner.hide();
                    alert('This purchase is temporary not available, please try again later');
                }
            });
        });

        function startCheckout() {
            // Proceed to checkout immediately if user is logged in
            var checkoutData = {};

            checkoutData['email'] = '{{Auth::user()->email}}';
            checkoutData['firstname'] = '';
            checkoutData['lastname'] = '';
            callPaddle(checkoutData);
            return;

        }

        function callPaddle(data) {
            var checkoutEmail = '{{Auth::user()->email}}';
            var enableQuantity = false;
            if (!isSubscription) {
                enableQuantity = true
            }

            var passthroughData = JSON.stringify(data);
            passthroughData = $.base64.encode(passthroughData);
            var checkoutOptions = {
                product: selectedProduct,
                passthrough: passthroughData,
                email: checkoutEmail,
                successCallback: checkoutSuccess,
                closeCallback: finishCheckout,
                allowQuantity: enableQuantity
            };
            Paddle.Checkout.open(checkoutOptions);
        }

        function checkoutSuccess() {
            finishCheckout();
        }

        function finishCheckout() {
            Paddle.Spinner.hide();
        }
    </script>
@else
    <script>
        var selectedProduct = null;
        var userData = null;
        var isSubscription = false;
        var checkoutData = null;

        var formToken = '{{csrf_token()}}';
        if (formToken) {
            var data = {};
            data[formToken] = 1;
        }

        $('a[data-product]').each(function () {
            $(this).click(function () {
                $('#prodlog').modal('show');
                selectedProduct = jQuery(this).data('product');
                isSubscription = jQuery(this).data('subscription');
            })
        })
    </script>
    <script>
        jQuery('form#prlogin').submit(function (e) {
            e.preventDefault();
            jQuery.ajax({
                url: '{{route('login')}}',
                method: 'post',
                data: jQuery(this).serialize(),
                dataType: 'json'
            })
                .fail(function (data) {
                    if (JSON.parse(data.responseText).errors.username != undefined) {
                        $('form#prlogin button[type="submit"]').after('<div class="err">' + JSON.parse(data.responseText).errors.username[0] + '</div>');
                    } else if (JSON.parse(data.responseText).errors.email != undefined) {
                        $('form#prlogin button[type="submit"]').after('<div class="err">' + JSON.parse(data.responseText).errors.email[0] + '</div>');
                    }
                })
                .done(function (data) {

                    $('a#logbut').attr('href','{{ route("my-licenses") }}')

                    $('#prodlog').modal('hide');
                    let usermail = data.email;

                    Paddle.Spinner.show();
                    var formToken = '{{csrf_token()}}';
                    if (formToken) {
                        // Append form token as login credential
                        var data = {};
                        data['_token'] = '{{csrf_token()}}';
                        data['paddle_pid'] = selectedProduct;

                        console.log(data['_token']);

                        jQuery.ajax({
                            url: '{{route('getProductPublishedState')}}',
                            method: 'post',
                            data: data,
                            dataType: 'json'
                        })
                            .done(function (response) {
                                if (!response.isPublished) {
                                    alert(response.message);
                                    return;
                                }
                                startCheckout();
                            })
                            .fail(function () {
                                alert('This purchase is temporary not available, please try again later');
                            })
                            .always(function () {
                                Paddle.Spinner.hide();
                            });
                    } else {
                        Paddle.Spinner.hide();
                        alert('This purchase is temporary not available, please try again later');
                    }

                    function startCheckout() {
                        // Proceed to checkout immediately if user is logged in
                        var checkoutData = {};

                        checkoutData['email'] = usermail;
                        checkoutData['firstname'] = '';
                        checkoutData['lastname'] = '';
                        callPaddle(checkoutData);
                        return;

                    }

                    function callPaddle(data) {
                        var checkoutEmail = usermail;
                        var enableQuantity = false;
                        if (!isSubscription) {
                            enableQuantity = true
                        }

                        var passthroughData = JSON.stringify(data);
                        passthroughData = $.base64.encode(passthroughData);
                        var checkoutOptions = {
                            product: selectedProduct,
                            passthrough: passthroughData,
                            email: checkoutEmail,
                            successCallback: checkoutSuccess,
                            closeCallback: finishCheckout,
                            allowQuantity: enableQuantity
                        };
                        Paddle.Checkout.open(checkoutOptions);
                    }

                    function checkoutSuccess() {
                        finishCheckout();
                    }

                    function finishCheckout() {
                        Paddle.Spinner.hide();
                    }
                });
        });
    </script>
@endif

<script>
    $('.gbg a.buy_but').click(function (e) {
        e.preventDefault();
        var $container = $("html,body");
        var $scrollTo = $('.pricing');
        $container.animate({scrollTop: $scrollTo.offset().top, scrollLeft: 0}, 300);
    })
</script>
<script>
    jQuery('form[name="newsletter"]').submit(function (e) {
        e.preventDefault();
        jQuery.ajax({
            url: '{{route('newsletterSendFront')}}',
            method: 'post',
            data: jQuery(this).serialize(),
            dataType: 'json'
        })
            .done(function (data) {
                if (data == true) {
                    jQuery('form[name="newsletter"]').append('<div class="alert alert-success" role="alert">\n' +
                        ' {{trans("main.newsletter_confirmation")}}\n' +
                        '</div>');
                } else {
                    jQuery('form[name="newsletter"]').append('<div class="alert alert-warning" role="alert">\n' +
                        '  {{trans("main.already_subscribed")}}\n' +
                        '</div>');
                }
                setTimeout(function () {
                    jQuery('form[name="newsletter"] .alert').fadeOut();
                }, 3000)
            })
    })
</script>
</body>
</html>
