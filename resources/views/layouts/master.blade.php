<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#27415a" />
    <meta name="description"
        content="Discover E POS, your comprehensive POS system designed for supermarkets, retail stores, and more. Streamline transactions, manage inventory, and enhance customer experiences with our intuitive solution." />
    <title>E POS</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/img/epos-logo-192.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/pretty-checkbox/pretty-checkbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/favicon.png') }}" />


    <style>
        .date-time {
            font-family: "Nunito", "Segoe UI", arial;
            font-size: 15px;
            font-weight: 700;
            background-color: #27415a !important;
            padding: 6px 12px;
            border-radius: 10px;
            transition: ease-in-out 0.5s;
            text-shadow: 2px 2px 2px #21384e;
        }

        .date-time:hover {
            box-shadow: inset 2px 5px 10px #21384e !important;
        }

        .nav-back-btn {
            /* background-color: #21384e !important; */
            background: linear-gradient(150deg, #557996 0%, #243b50 100%) !important;
            border-radius: 8px !important;
            color: #FFF !important;
            padding: 8px 10px !important;
            font-size: 14px !important;
            border: none !important;
            transition: background ease 0.5s;
        }

        .nav-back-btn:hover {
            background: linear-gradient(170deg, #557996 0%, #243b50 100%) !important;
        }
    </style>
    {{--
    <link rel="stylesheet" href="{{ asset('assets/bundles/fullcalendar/fullcalendar.min.css') }}"> --}}
    @livewireStyles
</head>

<body>
    {{-- header --}}
    <?php $access = session()->get('Controls'); ?>

    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li>
                            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                                <i data-feather="sidebar"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::previous() }}" class="btn nav-back-btn ml-3">
                                <i data-feather="arrow-left"></i>&nbsp;Back
                            </a>
                        </li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">
                    {{-- <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i>
                            <span class="badge headerBadge1">
                                6 </span> </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                            <div class="dropdown-header">
                                NEWS
                                <div class="float-right">
                                    <a href="#">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-message">
                                <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar
                                              text-white">
                                        <img alt="image" src="{{ asset('assets/img/users/user-1.png') }}"
                                            class="rounded-circle">
                                    </span> <span class="dropdown-item-desc"> <span class="message-user">John
                                            Deo</span>
                                        <span class="time messege-text">Please check your mail !!</span>
                                        <span class="time">2 Min Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
                                        <img alt="image" src="{{ asset('assets/img/users/user-2.png') }}"
                                            class="rounded-circle">
                                    </span> <span class="dropdown-item-desc"> <span class="message-user">Sarah
                                            Smith</span> <span class="time messege-text">Request for leave
                                            application</span>
                                        <span class="time">5 Min Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
                                        <img alt="image" src="{{ asset('assets/img/users/user-5.png') }}"
                                            class="rounded-circle">
                                    </span> <span class="dropdown-item-desc"> <span class="message-user">Jacob
                                            Ryan</span> <span class="time messege-text">Your payment invoice is
                                            generated.</span> <span class="time">12 Min Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
                                        <img alt="image" src="{{ asset('assets/img/users/user-4.png') }}"
                                            class="rounded-circle">
                                    </span> <span class="dropdown-item-desc"> <span class="message-user">Lina
                                            Smith</span> <span class="time messege-text">hii John, I have upload
                                            doc
                                            related to task.</span> <span class="time">30
                                            Min Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
                                        <img alt="image" src="{{ asset('assets/img/users/user-3.png') }}"
                                            class="rounded-circle">
                                    </span> <span class="dropdown-item-desc"> <span class="message-user">Jalpa
                                            Joshi</span> <span class="time messege-text">Please do as specify.
                                            Let me
                                            know if you have any query.</span> <span class="time">1
                                            Days Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
                                        <img alt="image" src="{{ asset('assets/img/users/user-2.png') }}"
                                            class="rounded-circle">
                                    </span> <span class="dropdown-item-desc"> <span class="message-user">Sarah
                                            Smith</span> <span class="time messege-text">Client Requirements</span>
                                        <span class="time">2 Days Ago</span>
                                    </span>
                                </a>
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                            <div class="dropdown-header">
                                NOTIFICATION
                                <div class="float-right">
                                    <a href="#">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                <a href="#" class="dropdown-item dropdown-item-unread"> <span
                                        class="dropdown-item-icon bg-primary text-white"> <i class="fas
                                                  fa-code"></i>
                                    </span> <span class="dropdown-item-desc"> Template update is
                                        available now! <span class="time">2 Min
                                            Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span
                                        class="dropdown-item-icon bg-info text-white"> <i class="far
                                                  fa-user"></i>
                                    </span> <span class="dropdown-item-desc"> <b>You</b> and <b>Dedik
                                            Sugiharto</b> are now friends <span class="time">10 Hours
                                            Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span
                                        class="dropdown-item-icon bg-success text-white"> <i class="fas
                                                  fa-check"></i>
                                    </span> <span class="dropdown-item-desc"> <b>Kusnaedi</b> has
                                        moved task <b>Fix bug header</b> to <b>Done</b> <span class="time">12
                                            Hours
                                            Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span
                                        class="dropdown-item-icon bg-danger text-white"> <i
                                            class="fas fa-exclamation-triangle"></i>
                                    </span> <span class="dropdown-item-desc"> Low disk space. Let's
                                        clean it! <span class="time">17 Hours Ago</span>
                                    </span>
                                </a> <a href="#" class="dropdown-item"> <span
                                        class="dropdown-item-icon bg-info text-white"> <i class="fas
                                                  fa-bell"></i>
                                    </span> <span class="dropdown-item-desc"> Welcome to Otika
                                        template! <span class="time">Yesterday</span>
                                    </span>
                                </a>
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li> --}}
                    <li class="mr-2">
                        @livewire('active-counter')
                    </li>
                    <li class="mr-2" style="margin:auto;">
                        <div class="text-light-gray date-time" id="datetime"></div>
                    </li>

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="{{ asset('assets/img/user.png') }}" class="user-img-radious-style"> <span
                                class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title text-sm">
                                {{ Auth::user()->name }}

                            </div>

                            <div class="dropdown-divider mt-0"></div>
                            <a href="/logout" class="dropdown-item user-dropdown has-icon text-danger"> <i
                                    class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="/dash-board"> <img alt="image" src="{{ asset('assets/img/epos_icon.png') }}"
                                class="header-logo" />
                            <span>
                                <img alt="image" src="{{ asset('assets/img/epos-text_logo light.png') }}"
                                    class="logo-name" />
                            </span>
                        </a>
                    </div>

                    <ul class="sidebar-menu">
                        <li class="menu-header">Home</li>
                        @if (in_array('dash-board', $access))
                        <li class="@stack('dashboard')">
                            <a class="nav-link" href="/dash-board">
                                <i data-feather="monitor"></i><span>Dashboard</span>
                            </a>
                        </li>
                        @endif

                        @if (in_array('category', $access) ||
                        in_array('brand', $access) ||
                        in_array('measurement', $access) ||
                        in_array('items', $access))
                        <li class="menu-header">Main</li>
                        <li class="dropdown @stack('category') @stack('brand') @stack('measurement') @stack('items')">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i data-feather="plus-circle"></i><span>New</span>
                            </a>
                            <ul class="dropdown-menu">
                                @if (in_array('category', $access))
                                <li class="@stack('category')"><a class="nav-link" href="/category">Category</a>
                                </li>
                                @endif
                                @if (in_array('brand', $access))
                                <li class="@stack('brand')"><a class="nav-link" href="/brand">Brand</a></li>
                                @endif
                                @if (in_array('measurement', $access))
                                <li class=" @stack('measurement')"><a class="nav-link"
                                        href="/measurement">Measurement</a></li>
                                @endif
                                @if (in_array('item', $access))
                                <li class="@stack('items')"><a class="nav-link" href="/item">Item(s)</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if (in_array('company', $access) || in_array('dealer', $access) || in_array('purchase-invoice',
                        $access) || in_array('dealer-credit',$access))

                        <li
                            class="dropdown @stack('company') @stack('dealer') @stack('purchace-invoice') @stack('dealer-credit')">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i data-feather="truck"></i><span>Purchase</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('company', $access))
                                <li class="@stack('company')"><a class="nav-link" href="/company">Company</a>
                                </li>
                                @endif
                                @if (in_array('dealer', $access))
                                <li class="@stack('dealer')"><a class="nav-link" href="/dealer">Dealer</a>
                                </li>
                                @endif
                                @if (in_array('purchase-invoice', $access))
                                <li class="@stack('purchace-invoice')"><a class="nav-link"
                                        href="/purchase-invoice">Invoice & Stock</a></li>
                                @endif
                                @if (in_array('dealer-credit', $access))
                                <li class="@stack('dealer-credit')"><a class="nav-link" href="/dealer-credit">Dealer
                                        Credit</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if (in_array('branch-stock', $access) || in_array('products', $access))
                        <li class="dropdown @stack('stock-transfer') @stack('branch-stock') @stack('products')">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i data-feather="box"></i><span>Stock</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('products', $access))
                                <li class="@stack('products')"><a class="nav-link" href="/products">Products
                                    </a></li>
                                @endif
                                @if (in_array('branch-stock', $access))
                                <li class="@stack('branch-stock')"><a class="nav-link" href="/branch-stock">Stock Report
                                    </a></li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if (in_array('customer', $access) || in_array('shop', $access))
                        <li class="menu-header">CUSTOMER</li>
                        <li class="dropdown @stack('customer') @stack('shop')">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i data-feather="user"></i><span>Customers</span>
                            </a>
                            <ul class="dropdown-menu">
                                @if (in_array('customer', $access))
                                <li class="@stack('customer')"><a class="nav-link" href="/customer">Customer</a></li>
                                @endif
                                @if (in_array('shop', $access))
                                <li class="@stack('shop')"><a class="nav-link" href="/shop">Shop</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if (in_array('sale', $access) || in_array('sales-summary', $access) || in_array('sales-view',
                        $access))
                        <li class="dropdown @stack('sales') @stack('sales-view') @stack('sales-summary')">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i data-feather="shopping-bag"></i><span>Sales</span>
                            </a>
                            <ul class="dropdown-menu">
                                @if (in_array('sale', $access) && in_array('sales-view', $access))
                                <li class="@stack('sales') @stack('sales-view')"><a class="nav-link"
                                        href="/sales-view">Sales</a>
                                </li>
                                @endif
                                {{-- <li><a class="nav-link" href="#">Return</a></li> --}}
                                @if (in_array('sales-summary', $access))
                                <li class="@stack('sales-summary')"><a class="nav-link" href="/sales-summary">Sales
                                        Summary</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif


                        @if (in_array('bank', $access) || in_array('expence-type', $access) || in_array('expence',
                        $access) || in_array('income-type', $access) || in_array('income', $access) || in_array('ledger', $access))
                        <li class="menu-header">Accounts</li>
                        <li
                            class="dropdown @stack('bank') @stack('expence-type') @stack('expence') @stack('income-type') @stack('income') @stack('ledger')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="dollar-sign"></i><span>Account</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('bank', $access))
                                <li class="@stack('bank')"><a class="nav-link" href="/bank">Bank</a>
                                </li>
                                @endif
                                @if (in_array('income-type', $access))
                                <li class="@stack('income-type')"><a class="nav-link" href="/income-type">Income
                                        Type</a>
                                </li>
                                @endif
                                @if (in_array('income', $access))
                                <li class="@stack('income')"><a class="nav-link" href="/income">Income</a>
                                </li>
                                @endif
                                @if (in_array('expence-type', $access))
                                <li class="@stack('expence-type')"><a class="nav-link" href="/expence-type">Expence
                                        Type</a></li>
                                @endif
                                @if (in_array('expence', $access))
                                <li class="@stack('expence')"><a class="nav-link" href="/expence">Expence</a></li>
                                @endif
                                @if (in_array('ledger', $access))
                                <li class="@stack('ledger')"><a class="nav-link" href="/ledger">Ledger</a></li>
                                @endif
                                {{-- @if (in_array('expence-report', $access)) <li><a class="nav-link"
                                        href="">Cheque</a></li>@endif --}}
                            </ul>
                        </li>
                        @endif


                        @if (in_array('expence-report', $access) || in_array('sales-report', $access) ||
                        in_array('return-report', $access))
                        <li class="dropdown @stack('expence-report') @stack('sales-report') @stack('return-report')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="file"></i><span>Reports</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('expence-report', $access))
                                <li class="@stack('expence-report')"><a class="nav-link" href="/expence-report">Expence
                                        Report</a></li>
                                @endif
                                @if (in_array('sales-report', $access))
                                <li class="@stack('sales-report')"><a class="nav-link" href="/sales-report">Sales
                                        Report</a></li>
                                @endif
                                @if (in_array('return-report', $access))
                                <li class="@stack('return-report')"><a class="nav-link" href="/return-report">Return
                                        Report</a></li>
                                @endif
                                {{-- <li><a class="nav-link" href="/#">Report 2</a></li>
                                <li><a class="nav-link" href="/#">Report 3</a></li> --}}
                            </ul>
                        </li>
                        @endif

                        @if (in_array('property', $access) || in_array('counter', $access))
                        <li class="menu-header">Property</li>
                        <li class="dropdown @stack('property') @stack('counter')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="home"></i><span>Property</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('property', $access))
                                <li class="@stack('property')">
                                    <a class="nav-link" href="/property">
                                        Property
                                    </a>
                                </li>
                                @endif
                                @if (in_array('counter', $access))
                                <li class="@stack('counter')">
                                    <a class="nav-link" href="/counter">
                                        Counter
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        {{-- admin --}}
                        @if (in_array('access-model', $access) || in_array('user-type', $access) || in_array('users',
                        $access))
                        <li class="menu-header">ADMINSTRITION</li>
                        <li class="dropdown @stack('access-model') @stack('user-type') @stack('user')">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i data-feather="user-check"></i><span>Auth</span>
                            </a>
                            <ul class="dropdown-menu">
                                @if (in_array('access-model', $access))
                                <li class="@stack('access-model')"><a href="/access-model">Access-Model</a></li>
                                @endif

                                @if (in_array('user-type', $access))
                                <li class="@stack('user-type')"><a href="/user-type">User-Types</a></li>
                                @endif

                                @if (in_array('users', $access))
                                <li class="@stack('user')"><a href="/users">User</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif

                    </ul>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                {{ $slot }}
            </div>

            {{-- <div class="settingSidebar">
                <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
                </a>
                <div class="settingSidebar-body ps-container ps-theme-default">
                    <div class=" fade show active">
                        <div class="setting-panel-header">Setting Panel
                        </div>
                        <div class="p-15 border-bottom">
                            <h6 class="font-medium m-b-10">Select Layout</h6>
                            <div class="selectgroup layout-color w-50">
                                <label class="selectgroup-item">
                                    <input type="radio" name="value" value="1"
                                        class="selectgroup-input-radio select-layout" checked>
                                    <span class="selectgroup-button">Light</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="value" value="2"
                                        class="selectgroup-input-radio select-layout">
                                    <span class="selectgroup-button">Dark</span>
                                </label>
                            </div>
                        </div>
                        <div class="p-15 border-bottom">
                            <h6 class="font-medium m-b-10">Sidebar Color</h6>
                            <div class="selectgroup selectgroup-pills sidebar-color">
                                <label class="selectgroup-item">
                                    <input type="radio" name="icon-input" value="1"
                                        class="selectgroup-input select-sidebar">
                                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                        data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="icon-input" value="2"
                                        class="selectgroup-input select-sidebar" checked>
                                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                        data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                                </label>
                            </div>
                        </div>
                        <div class="p-15 border-bottom">
                            <h6 class="font-medium m-b-10">Color Theme</h6>
                            <div class="theme-setting-options">
                                <ul class="choose-theme list-unstyled mb-0">
                                    <li title="white" class="active">
                                        <div class="white"></div>
                                    </li>
                                    <li title="cyan">
                                        <div class="cyan"></div>
                                    </li>
                                    <li title="black">
                                        <div class="black"></div>
                                    </li>
                                    <li title="purple">
                                        <div class="purple"></div>
                                    </li>
                                    <li title="orange">
                                        <div class="orange"></div>
                                    </li>
                                    <li title="green">
                                        <div class="green"></div>
                                    </li>
                                    <li title="red">
                                        <div class="red"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="p-15 border-bottom">
                            <div class="theme-setting-options">
                                <label class="m-b-0">
                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                        id="mini_sidebar_setting">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="control-label p-l-10">Mini Sidebar</span>
                                </label>
                            </div>
                        </div>
                        <div class="p-15 border-bottom">
                            <div class="theme-setting-options">
                                <label class="m-b-0">
                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                        id="sticky_header_setting">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="control-label p-l-10">Sticky Header</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                            <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                                <i class="fas fa-undo"></i> Restore Default
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <footer class="main-footer">
            <div class="footer-left">
                <a href="#" class="footer-text d-flex align-items-center">
                    <img class="footer-logo" src="{{ asset('assets/img/epos_icon.png') }}" alt="">
                    <i class="fa fa-copyright align-middle"></i>&nbsp;
                    <span id="currentYear"></span>&nbsp;- E POS
                </a>
            </div>
            <div class="footer-right">
            </div>
        </footer>
        @yield('model')
    </div>
    </div>

    @livewireScripts
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        // // this is for insert
        // window.addEventListener('insert-show-form', event => {
        //     $('#insert-model').modal('show');
        // });
        // window.addEventListener('insert-hide-form', event => {
        //     $('#insert-model').modal('hide');
        // });

        // this is for delete
        // window.addEventListener('delete-show-form', event => {
        //     $('#delete-model').modal('show');
        // });

        // window.addEventListener('delete-hide-form', event => {
        //     $('#delete-model').modal('hide');
        // });

        // // this is for view
        // window.addEventListener('view-show-form', event => {
        //     $('#view-model').modal('show');
        // });
        // window.addEventListener('view-hide-form', event => {
        //     $('#view-model').modal('hide');
        // });

        // // this is for return model
        // window.addEventListener('return-show-form', event => {
        //     $('#return-model').modal('show');
        // });
        // window.addEventListener('return-hide-form', event => {
        //     $('#return-model').modal('hide');
        // });

        window.addEventListener('change-focus-other-field', event => {
            $('#search_bar').focus();
        });

        window.addEventListener('focus-on-add-to-bill-field', event => {
            $("#quantity_field").focus();
        });

        window.addEventListener('focus-on-customer-field', event => {
            $("#customer_field").focus();
        });

        window.addEventListener('focus-on-payment-field', event => {
            $("#payment_field").focus();
        });
    </script>

    <script>
        document.getElementById("currentYear").textContent = new Date().getFullYear();
    </script>

    <script>
        function displayDateTime() {
        var currentDateTime = new Date();

        var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        var day = days[currentDateTime.getDay()];

        var dayOfMonth = currentDateTime.getDate();
        var month = currentDateTime.getMonth() + 1;
        var year = currentDateTime.getFullYear();
        var hours = currentDateTime.getHours();
        var minutes = currentDateTime.getMinutes();
        var seconds = currentDateTime.getSeconds();
        var ampm = hours >= 12 ? 'PM' : 'AM';

        dayOfMonth = ("0" + dayOfMonth).slice(-2);
        month = ("0" + month).slice(-2);
        hours = ("0" + (hours % 12 || 12)).slice(-2);
        minutes = ("0" + minutes).slice(-2);
        seconds = ("0" + seconds).slice(-2);

        var dateTimeString =dayOfMonth + "." + month + "." + year + " <span class='text-light-green'>" + day + "</span> | " + hours + ":" + minutes + ":" + seconds + " " + "<span class='text-light-green'>" + ampm +  "</span>";

        document.getElementById("datetime").innerHTML = dateTimeString;
    }

    displayDateTime();

    setInterval(displayDateTime, 1000);
    </script>
</body>

</html>
