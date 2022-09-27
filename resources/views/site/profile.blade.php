<?php $active_theme = 1; ?>
@extends('layouts.themes.theme' . $active_theme)
@section('content')

    <div class="my-account-page-area">
        <div class="container">
            <div class="woocommerce">
                <div class="row">

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="tab-content">
                            <div class="tab-pane fade  active in" id="details">
                                <div class="woocommerce-MyAccount-content">

                                    <form class="row woocommerce-EditAccountForm edit-account" action="{{route('profile.update')}}" method="post">
                                        @csrf
                                        <div
                                            class="col-md-12 col-sm-12 woocommerce-form-row woocommerce-form-row--first form-row form-row-first row">
                                            <div class="col-sm-6">
                                                <img src="{{asset('assets/images/users/' . $user->image)}}" alt=""
                                                     style="width:170px;height:170px;position:relative;left: 0">
                                            </div>
                                            <div class="col-sm-6">
                                               <span>
                                           الصورة الشخصيه
                                       </span>
                                                <input type="file"
                                                       class="woocommerce-Input woocommerce-Input--text input-text"
                                                       name="account_first_name" id="account_first_name"
                                                       value="{{$user->name}}"
                                                       placeholder="User Name">
                                            </div>

                                        </div>

                                        <p class="col-md-12 col-sm-12 woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                                       <span>
                                           اسم المستخدم
                                       </span>
                                            <input type="text"
                                                   class="woocommerce-Input woocommerce-Input--text input-text"
                                                   name="account_first_name" id="account_first_name"
                                                   value="{{$user->name}}"
                                                   placeholder="User Name">
                                        </p>


                                        <p class="col-xs-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                       <span>
                                           البريد الالكتروني
                                       </span>
                                            <input type="email"
                                                   class="woocommerce-Input woocommerce-Input--email input-text"
                                                   name="account_email" id="account_email" value="{{$user->email}}"
                                                   placeholder="mohamed@test.com">
                                        </p>
                                        <p class="col-xs-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                       <span>
                                          رقم الهاتف
                                       </span>
                                            <input type="text"
                                                   class="woocommerce-Input woocommerce-Input--email input-text"
                                                   name="account_email" id="account_email" value="{{$user->phone}}"
                                                   placeholder="010682989">
                                        </p>
                                        <fieldset class="col-xs-12">
                                            <legend>تغيير كلمة المرور</legend>
                                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                                <label for="password_current">كلمة المرور الجديدة (
                                                    اتركها فارغه في حالة عدم الرغبه في تغيير كلمة المرور)</label>
                                                <input type="password"
                                                       class="woocommerce-Input woocommerce-Input--password input-text"
                                                       name="password" id="password_current">
                                            </p>

                                        </fieldset>
                                        <div class="clear"></div>
                                        <p class="col-xs-12">
                                            <input type="hidden" id="_wpnonce" name="_wpnonce" value="96df2c51c6"><input
                                                type="hidden" name="_wp_http_referer" value="/my-account/edit-account/">
                                            <input type="submit" class="woocommerce-Button button btn-shop-now-fill"
                                                   name="save_account_details" value="حفظ التعديلات">
                                            <input type="hidden" name="action" value="save_account_details">
                                        </p>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="orders">
                                <div class="woocommerce-message"><a class="woocommerce-Button button" href="#">Go
                                        shop</a>No order has been made yet.
                                </div>
                            </div>
                            <div class="tab-pane fade" id="downloads">
                                <div class="woocommerce-info"><a class="woocommerce-Button button" href="#">Go shop</a>No
                                    downloads available yet.
                                </div>
                            </div>
                            <div class="tab-pane fade" id="addresses">
                                <div class="woocommerce-MyAccount-content wd-myaccount-content-wrapper">
                                    <p>
                                        The following addresses will be used on the checkout page by default.</p>
                                    <div class="u-columns woocommerce-Addresses addresses">
                                        <div class="woocommerce-Address">
                                            <header class="woocommerce-Address-title title">
                                                <h3>Billing address</h3>
                                                <a href="#" class="edit">Edit</a>
                                            </header>
                                            <address>
                                                You have not set up this type of address yet.
                                            </address>
                                        </div>
                                        <div class="woocommerce-Address">
                                            <header class="woocommerce-Address-title title">
                                                <h3>Shipping address</h3>
                                                <a href="#" class="edit">Edit</a>
                                            </header>
                                            <address>
                                                You have not set up this type of address yet.
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="logout">
                                <div class="woocommerce-message">Are you sure you want to log out? <a href="#">Confirm
                                        and log out</a></div>
                                <div class="woocommerce-MyAccount-content wd-myaccount-content-wrapper">
                                    <p>Hello <strong>user-name</strong> (not <strong>user-name</strong>? <a href="#">Log
                                            out</a>)</p>
                                    <p>From your account dashboard you can view your <a href="#">recent orders</a>,
                                        manage your <a href="#">shipping and billing addresses</a> and <a href="#">edit
                                            your password and account details</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <nav class="woocommerce-MyAccount-navigation">
                            <ul>


                                <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard active">
                                    <a href="#details" data-toggle="tab"
                                       aria-expanded="false">{{__('general.accountDetails')}}</a></li>

                                <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard">
                                    <a href="#orders" data-toggle="tab"
                                       aria-expanded="false">{{__('general.orders')}}</a></li>
                                <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard">
                                    <a href="#downloads" data-toggle="tab"
                                       aria-expanded="false">{{__('general.favorite')}}</a></li>

                                <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard">
                                    <a href="#logout" data-toggle="tab" aria-expanded="false">{{__('auth.logout')}}</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
