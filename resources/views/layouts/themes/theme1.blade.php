<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> {{env('APP_NAME')}} </title>
    <meta name="description" content=" {{env('APP_DESCRIPTION')}}">
    @include('includes.themes.theme1.heading')
    @if(App::getlocale() == 'ar')
        <style>
            * {
                font-family: 'cairo';
                direction: rtl;
            }
        </style>
    @endif
</head>

<body>
<div class="wrapper-area">

    <!-- Header Area Start Here -->
@include('includes.themes.theme1.nav')
<!-- Header Area End Here -->

@yield('content')

<!-- Footer Area Start Here -->
@include('includes.themes.theme1.footer')
<!-- Footer Area End Here -->
</div>
<!-- Modal Dialog Box Start Here-->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-body">
            <button type="button" class="close myclose" data-dismiss="modal">&times;</button>
            <div class="product-details1-area">
                <div class="product-details-info-area">
                    <div class="row">

                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <div class="inner-product-details-right text-right">
                                <h3>81218-1 / 51p</h3>

                                <p class="price">$59.00</p>
                                <p>
                                    تفاصيل تفاصيل تفاصيل تفاصيل تفاصيل تفاصيل تفاصيل تفاصيل تفاصيل تفاصيل تفاصيل
                                </p>
                                <ul class="product-details-social" dir="rtl">
                                    <li>شارك علي</li>
                                    <li><a href="#"><i aria-hidden="true" class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fa fa-linkedin"></i></a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fa fa-pinterest"></i></a></li>
                                </ul>
                                <ul class="inner-product-details-cart">
                                    <li><a href="#">أضف الي السلة</a></li>
                                    <li>
                                        <div class="input-group quantity-holder" id="quantity-holder">
                                            <input type="text" placeholder="1" value="1"
                                                   class="form-control quantity-input" name="quantity">
                                            <div class="input-group-btn-vertical">
                                                <button type="button" class="btn btn-default quantity-plus"><i
                                                        aria-hidden="true" class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-default quantity-minus"><i
                                                        aria-hidden="true" class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                    </li>
                                    <li><a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="inner-product-details-left">
                                <div class="tab-content">
                                    <div id="metro-related1" class="tab-pane fade active in">
                                        <a href="#"><img class="img-responsive" src="{{asset('assets/themes/theme' . $active_theme . '/img/product/3.PNG')}}"
                                                         alt="single"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn-services-shop-now" data-dismiss="modal">غلق</a>
        </div>
    </div>
</div>
<!-- Modal Dialog Box End Here-->
<!-- Preloader Start Here -->
<div id="preloader"></div>
<!-- Preloader End Here -->
<!-- jquery-->
@include('includes.themes.theme1.script')

</body>

</html>
