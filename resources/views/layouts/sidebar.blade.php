<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ url('index') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="30">
            </span>
        </a>

        <a href="{{ url('index') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="30">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Меню</li>

                <li>
                    <a href="{{ url('/') }}">
                        <i class="uil-home-alt"></i>
                        <span>Главная</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/orders') }}">
                        <i class="uil-map-pin-alt"></i>
                        <span>Заказы</span>
                    </a>
                </li>
                <li class="menu-title">Пользователи</li>
                <li>
                    <a href="{{ url('/drivers') }}">
                        <i class="mdi mdi-taxi"></i>
                        <span>Водители</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/moderation') }}">
                        <i class="bx bx-list-check"></i>
                        <span>Модерация</span>
                    </a>
                </li>
                @if (auth()->user()->role == 'ADM')
                    <li>
                        <a href="{{ url('/access') }}">
                            <i class="bx bx-shield-quarter"></i>
                            <span>Доступ</span>
                        </a>
                    </li>
                @endif
                <li class="menu-title">Система</li>
                <li>
                    <a href="{{ url('/prices') }}">
                        <i class="uil uil-coins"></i>
                        <span>Ценообразование</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/car_classes') }}">
                        <i class="bx bx-grid-alt"></i>
                        <span>Классы поездок</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/shifts') }}">
                        <i class="uil uil-pump"></i>
                        <span>Смены водителей</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/driver_levels') }}">
                        <i class="bx bx-trophy"></i>
                        <span>Грейды водителей</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/cities') }}">
                        <i class="uil uil-map"></i>
                        <span>База городов</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/cars') }}">
                        <i class="bx bx-star"></i>
                        <span>База автомобилей</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/translations') }}">
                        <i class="mdi mdi-translate"></i>
                        <span>Локализация</span>
                    </a>
                </li>


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
