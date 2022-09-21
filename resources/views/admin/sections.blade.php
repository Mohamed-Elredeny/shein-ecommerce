<?php $guard = '' ?>
<li>
    <a href="">
        <div class="parent-icon">
            <ion-icon name="home-sharp"></ion-icon>
        </div>
        <div class="menu-title">{{__('لوحة التحكم')}} </div>

    </a>
</li>


<li>
    <a href="javascript: void(0);">
        <div class="parent-icon">
            <ion-icon name="people-outline"></ion-icon>
        </div>
        <div class="menu-title">أدارة المستخدمين</div>

    </a>
    <ul>
        <li>
            <a href="{{route('users.index')}}">
                <div class="parent-icon">
                    <ion-icon name="eye-outline"></ion-icon>
                </div>
                <div class="menu-title">{{__('عرض الكل')}}</div>

            </a>
        </li>
        <li>
            <a href="{{route('users.create')}}">
                <div class="parent-icon">
                    <ion-icon name="add-outline"></ion-icon>
                </div>
                <div class="menu-title">{{__('أضافة مستخدم جديد')}}</div>

            </a>
        </li>


    </ul>
</li>

<li>
    <a href="javascript: void(0);">
        <div class="parent-icon">
            <ion-icon name="copy-outline"></ion-icon>
        </div>
        <div class="menu-title">{{__('التصنيفات')}} </div>

    </a>
    <ul>

        <li>
            <a href="{{route('categories.index')}}">
                <div class="parent-icon">
                    <ion-icon name="eye-outline"></ion-icon>
                </div>
                <div class="menu-title">{{__('عرض الكل')}}</div>

            </a>
        </li>
        <li>
            <a href="{{route('categories.create')}}">
                <div class="parent-icon">
                    <ion-icon name="add-outline"></ion-icon>
                </div>
                <div class="menu-title">{{__('أضافة تصنيف جديد')}}</div>

            </a>
        </li>
    </ul>
</li>

<li>
    <a href="javascript: void(0);">
        <div class="parent-icon">
            <ion-icon name="storefront-outline"></ion-icon>
        </div>
        <div class="menu-title">{{__('المنتجات')}} </div>

    </a>
    <ul>

        <li>
            <a href="{{route('products.index')}}">
                <div class="parent-icon">
                    <ion-icon name="eye-outline"></ion-icon>
                </div>
                <div class="menu-title">{{__('عرض الكل')}}</div>

            </a>
        </li>
        <li>
            <a href="{{route('products.create')}}">
                <div class="parent-icon">
                    <ion-icon name="add-outline"></ion-icon>
                </div>
                <div class="menu-title">{{__('أضافة منتج جديد')}}</div>

            </a>
        </li>
    </ul>
</li>

<li>
    <a href="{{route('orders.index')}}">
        <div class="parent-icon">
            <ion-icon name="apps-outline"></ion-icon>
        </div>
        <div class="menu-title">{{__('الطلبيات')}} </div>

    </a>

</li>
<li>
    <a href="{{route('transactions.index')}}">
        <div class="parent-icon">
            <ion-icon name="wallet-outline"></ion-icon>
        </div>
        <div class="menu-title">{{__('المعاملات المالية')}} </div>

    </a>

</li>

@if(Auth::guard($guard)->check())

    @if(Auth::guard('admin')->user()->is_super_admin)

        <li>
            <a href="javascript: void(0);">
                <div class="parent-icon">
                    <ion-icon name="earth-outline"></ion-icon>
                </div>
                <div class="menu-title">{{__('Countries')}} </div>

            </a>
            <ul>

                <li>
                    <a href="{{route('countries.index')}}">
                        <div class="parent-icon">
                            <ion-icon name="eye-outline"></ion-icon>
                        </div>
                        <div class="menu-title">{{__('View All')}}</div>

                    </a>
                </li>
                <li>
                    <a href="{{route('countries.create')}}">
                        <div class="parent-icon">
                            <ion-icon name="add-outline"></ion-icon>
                        </div>
                        <div class="menu-title">{{__('Add New')}}</div>

                    </a>
                </li>
            </ul>
        </li>
    @endif
@endif
@if(Auth::guard($guard)->user())

    <li>
        <a href="javascript:;">
            <div class="parent-icon">
                <ion-icon name="settings-outline"></ion-icon>
            </div>
            <div class="menu-title">Settings</div>

        </a>
        <ul>

            <li>
                <a href="{{route('admin.orders.settings')}}">
                    <div class="parent-icon">
                        <ion-icon name="cube-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{__(' Order Settings ')}}</div>
                </a>
            </li>

        </ul>
    </li>
@endif
