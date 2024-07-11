@push('dashboard','active')
<div class='overflow-hidden position-relative'>
    <?php $access = session()->get('Controls'); ?>

    <link rel="stylesheet" href="{{ asset('assets/packages/aos/aos.css') }}">
    <img src="{{asset('assets/img/epos_icon.png')}}" class="epos-logo" alt="">

    <style>
        .dash-welcome-area {
            background: linear-gradient(200deg, #587a96 0%, #3f5a70 100%) !important;
            border-radius: 15px;
            padding: 20px;
            outline: #48b438 1px solid;
            box-shadow: inset #387cb41e 0px 0px 25px;
            outline-offset: 2px;
            position: relative;
        }

        .dash-welcome-area .dash-header-watermark {
            position: absolute;
            right: 20px;
            max-width: 80%;
            max-height: 80%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.07;
            filter: grayscale(1)
        }

        .property-name-area {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .property-name-area img {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
            /* mix-blend-mode: darken; */
            border: 2px solid #9dd55a;
        }
    </style>

    <div class="pr-md-4 pl-md-4 pt-md-0 pb-md-4">
        <div class="d-flex mt-4">
            <h4 wire:ignore.self data-aos="fade-right" data-aos-delay="100" class="table-title bg-light">Dashboard</h4>
        </div>

        @if ((sizeOf($propertyData) != 0) && ($propertyId != 1))
        <div class="dash-welcome-area overflow-hidden mt-4">
            <img src="{{asset('assets/img/epos_icon.png')}}" class="dash-header-watermark" alt="">
            <div class="property-name-area">
                <img wire:ignore.self data-aos="fade" data-aos-delay="100"
                    src="{{ url('images/property/' . $propertyData[0]->logo) }}" alt="">
                <div>
                    <h4 wire:ignore.self data-aos="fade-down" data-aos-delay="100"
                        class="text-light-gray welcome-text mb-1">
                        Welcome to...</h4>
                    <h3 wire:ignore.self data-aos="fade-left" data-aos-delay="100" class="text-light-green mb-0">
                        {{$propertyData[0]->property_name}}</h3>
                </div>
            </div>
            @if ($userName)
            <div class="dash-greetings mt-4">
                <h4 wire:ignore.self data-aos="fade-up" data-aos-delay="100" class="text-light-gray mb-0">👋 Hi, <span
                        class="text-light-green">{{$userId === 2 ? 'Admin' : $userName}}</span>
                    {{$greeting}}
                </h4>
            </div>
            @endif
        </div>
        @endif


        <h5 wire:ignore.self data-aos="fade-right" data-aos-delay="200" class="text-blue mt-5">Quick Links <i
                class="fas fa-external-link-alt text-green" aria-hidden="true"></i>
        </h5>
        <div class="row mt-4">
            @if (in_array('sales-view', $access))
            <div wire:ignore.self data-aos="fade-up" data-aos-delay="300" class="col-xl-3 col-lg-4 col-md-6">
                <a href="/sales-view" class="text-decoration-none">
                    <div class="card pt-4 pb-4 pr-3 pl-3 billing-card">
                        <div class="d-flex justify-content-between">
                            <h3 class="tile-tiltle">Billing&nbsp;<i class="fa fa-chevron-right font-20 dash-lable-icon"
                                    aria-hidden="true"></i></h3>
                            <img src="assets/img/dashboard/invoice.png" class="dash-img float-right" alt="Billing">
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if (in_array('products', $access))
            <div wire:ignore.self data-aos="fade-up" data-aos-delay="400" class="col-xl-3 col-lg-4 col-md-6">
                <a href="/products" class="text-decoration-none">
                    <div class="card pt-4 pb-4 pr-3 pl-3 billing-card">
                        <div class="d-flex justify-content-between">
                            <h3 class="tile-tiltle">Stocks&nbsp;<i class="fa fa-chevron-right font-20 dash-lable-icon"
                                    aria-hidden="true"></i></h3>
                            <img src="assets/img/dashboard/stock.png" class="dash-img float-right" alt="Stocks">
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if (in_array('customer', $access))
            <div wire:ignore.self data-aos="fade-up" data-aos-delay="500" class="col-xl-3 col-lg-4 col-md-6">
                <a href="/customer" class="text-decoration-none">
                    <div class="card pt-4 pb-4 pr-3 pl-3 billing-card">
                        <div class="d-flex justify-content-between">
                            <h3 class="tile-tiltle">Customers&nbsp;<i
                                    class="fa fa-chevron-right font-20 dash-lable-icon" aria-hidden="true"></i></h3>
                            <img src="assets/img/dashboard/customers.png" class="dash-img float-right" alt="Customers">
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if (in_array('expence', $access))
            <div wire:ignore.self data-aos="fade-up" data-aos-delay="600" class="col-xl-3 col-lg-4 col-md-6">
                <a href="/expence" class="text-decoration-none">
                    <div class="card pt-4 pb-4 pr-3 pl-3 billing-card">
                        <div class="d-flex justify-content-between">
                            <h3 class="tile-tiltle">Expences&nbsp;<i class="fa fa-chevron-right font-20 dash-lable-icon"
                                    aria-hidden="true"></i></h3>
                            <img src="assets/img/dashboard/expence.png" class="dash-img float-right" alt="Expence">
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>

        @if (in_array('sales-summary', $access) && ($propertyId != 1))
        <hr>
        <div
            class="dash-title-btn-area justify-content-between align-items-md-center align-items-start flex-md-row flex-column mt-4">
            <div class='d-flex align-items-center gap-10'>
                <h5 wire:ignore.self data-aos="fade-right" data-aos-delay="200" class="text-blue mb-0">Sales Summary
                </h5>

                <a href="/sales-summary" wire:ignore.self data-aos="fade-left" data-aos-delay="200"
                    class="btn btn-sm show-btn">
                    <i class="fa fa-arrow-right pt-1 pb-1" ria-hidden="true"></i>
                </a>
            </div>

            @if ($userTypeId === 2)
            <div class="flex-grp" wire:ignore.self data-aos="fade-right" data-aos-delay="200">
                <label class="text-blue custom-label" for="category">Select Cashier</label>
                <select class="custom-form-input1 form-control text-center" wire:model="select_user">
                    <option value="0">All</option>
                    @foreach ($userData as $user)
                    <option value="{{ $user->id }}">
                        {{ ($user->id === 2) ? "Admin" : $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

        </div>

        <div class="row mt-4">
            <div wire:ignore.self data-aos="fade-up" data-aos-delay="300" class="col-12 col-xl-3 col-lg-4 col-md-6">
                <div class="sales-summary-area">
                    <div class="sales-sum-flex">
                        <h6 class="text-blue mb-0">Today Sales</h6>
                        <h6 class="text-blue mb-0">
                            {{ $today_sales !=0 ? number_format($today_sales, 2) : 0.00 }}
                        </h6>
                    </div>
                    <hr class="cus-hr">
                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Cash</div>
                        <div class="sub-sales-sum">
                            {{ $today_cash_sales !=0 ? number_format($today_cash_sales, 2) : 0.00 }}
                        </div>
                    </div>

                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Card</div>
                        <div class="sub-sales-sum">
                            {{ $today_card_sales !=0 ? number_format($today_card_sales, 2) : 0.00 }}
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self data-aos="fade-up" data-aos-delay="400" class="col-12 col-xl-3 col-lg-4 col-md-6">
                <div class="sales-summary-area">
                    <div class="sales-sum-flex">
                        <h6 class="text-blue mb-0">Today Bills</h6>
                        <h6 class="text-blue mb-0">
                            {{ $today_bills !=0 ? $today_bills : 0 }}
                        </h6>
                    </div>
                    <hr class="cus-hr">
                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Cash</div>
                        <div class="sub-sales-sum">
                            {{ $today_cash_bills !=0 ? $today_cash_bills : 0 }}
                        </div>
                    </div>

                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Card</div>
                        <div class="sub-sales-sum">
                            {{ $today_card_bills !=0 ? $today_card_bills : 0 }}
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self data-aos="fade-up" data-aos-delay="500" class="col-12 col-xl-3 col-lg-4 col-md-6">
                <div class="sales-summary-area">
                    <div class="sales-sum-flex">
                        <h6 class="text-blue mb-0">Total Sales</h6>
                        <h6 class="text-blue mb-0">
                            {{ $total_sales !=0 ? number_format($total_sales, 2) : 0.00 }}
                        </h6>
                    </div>
                    <hr class="cus-hr">
                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Cash</div>
                        <div class="sub-sales-sum">
                            {{ $total_cash_sales !=0 ? number_format($total_cash_sales, 2) : 0.00 }}
                        </div>
                    </div>

                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Card</div>
                        <div class="sub-sales-sum">
                            {{ $total_card_sales !=0 ? number_format($total_card_sales, 2) : 0.00 }}
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self data-aos="fade-up" data-aos-delay="600" class="col-12 col-xl-3 col-lg-4 col-md-6">
                <div class="sales-summary-area">
                    <div class="sales-sum-flex">
                        <h6 class="text-blue mb-0">Total Bills</h6>
                        <h6 class="text-blue mb-0">
                            {{ $total_bills !=0 ? $total_bills : 0 }}
                        </h6>
                    </div>
                    <hr class="cus-hr">
                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Cash</div>
                        <div class="sub-sales-sum">
                            {{ $total_cash_bills !=0 ? $total_cash_bills : 0 }}
                        </div>
                    </div>

                    <div class="sales-sum-flex">
                        <div class="sub-sales-sum-head">Card</div>
                        <div class="sub-sales-sum">
                            {{ $total_card_bills !=0 ? $total_card_bills : 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif


        <hr>
        <style>
            .dash-title-btn-area {
                display: flex;
                align-items: center;
                gap: 20px;
            }

            .show-btn {
                background-color: #2c5370;
                border-bottom: #4baf3b 2px solid;
                border-radius: 50px;
                padding: 2px 15px !important;
                color: #FFF !important;
                transition: ease-in-out 1s !important;
            }

            .show-btn:hover,
            .show-btn:focus {
                background-color: #4baf3b !important;
                border-bottom: #2c5370 2px solid !important;
            }

            .filter-top-area {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
            }

            .dash-stock-filter-area {
                overflow: hidden;
                border-radius: 10px;
                transition: max-height 0.8s ease !important;
            }

            .dash-stock-filter-sub-area {
                border-radius: 10px;
                box-shadow: 2px 5px 10px #00000016;
                border: 2px solid #FFF;
                padding: 30px 20px 10px 20px;
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
            }

            .table-transition {
                transition: all 0.5s ease-in-out;
            }

            .short-cut {
                position: absolute;
                right: 78px;
                top: 33%;
            }

            .short-cut .short-cut-key {
                color: #b0b0b0;
                font-size: 10px;
                font-weight: 700;
                border: 1px solid #b0b0b0;
                border-radius: 4px;
                padding: 1.5px 4px;
            }
        </style>
        <div class="dash-title-btn-area mt-4">
            <h5 wire:ignore.self data-aos="fade-right" data-aos-delay="200" class="text-blue mb-0">Stock Summary</h5>

            <button wire:ignore.self data-aos="fade-left" data-aos-delay="200" class="btn btn-sm show-btn"
                wire:click="toggleFilterArea">
                <i class="fa {{ $showFilterArea ? 'fa-eye' : 'fa-eye-slash' }} mr-1" ria-hidden="true"></i>
                <span>{{ $showFilterArea ? 'Hide' : 'Show' }}</span>
            </button>
        </div>

        <div class="dash-stock-filter-area mt-4" style="max-height: {{ $showFilterArea ? '100vh' : '0' }};">
            <div class="dash-stock-filter-sub-area">
                <div class="filter-top-area">
                    <h6 class="filter-text text-blue">
                        <i class="fa fa-filter mr-1" aria-hidden="true"></i>
                        Filters :
                    </h6>
                    @if ($select_category || $select_brand || $searchKey)
                    <button class="btn btn-sm clear-btn" title="Clear all selection" wire:click="clearSelection">
                        &times;
                        Clear
                    </button>
                    @endif
                </div>

                <div class="row mt-4">
                    <div class="col-12 col-md-3 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <select class="custom-form-input2 form-control text-center" wire:model="select_category">
                                <option value="0">-- Filter by Category --</option>
                                @foreach ($filter_categories as $cat_filter)
                                <option value="{{ $cat_filter->id }}">
                                    {{ $cat_filter->category }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <select class="custom-form-input2 form-control text-center" wire:model="select_brand">
                                <option value="0">-- Filter by Brand --</option>
                                @foreach ($filter_brands as $br_filter)
                                <option value="{{ $br_filter->id }}">
                                    {{ $br_filter->brand }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <div class="input-group position-relative">
                                <input type="search" id="searchInput"
                                    class="form-control custom-form-input2 position-relative" wire:model="searchKey"
                                    placeholder="Search by item name or barcode here..." aria-label="">
                                <div class="short-cut">
                                    <span class="short-cut-key">Alt + S</span>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-search">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($select_category || $select_brand || $searchKey)
                <div class="card card-tpos-secondary table-transition mt-3 mb-3">
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0">
                                <tr style="background-color: #d0e8e4">
                                    <th class="text-d-blue">No.</th>
                                    <th class="text-d-blue">Item Name</th>
                                    <th class="text-d-blue text-right">Sell Price</th>
                                    <th class="text-d-blue text-center">Main Store Qty.</th>
                                    <th class="text-d-blue text-center">Branch Store Qty.</th>
                                    {{-- <th class="text-d-blue text-center">Balance Qty.</th> --}}
                                </tr>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td class="text-blue">{{ $x }}.</td>
                                    <td class="text-blue">
                                        {{ $row->item_name }}
                                        <span class="text-dark">({{ $row->barcode }})</span>
                                    </td>
                                    <td class="text-blue text-right">{{ number_format($row->sell_price, 2) }}</td>
                                    <td class="text-blue text-center">{{ $row->main_store_qty }}</td>
                                    <td class="text-blue text-center">{{ $row->stock_qty }}</td>
                                    {{-- <td class="text-blue text-center">{{ ($row->main_store_qty) - ($row->stock_qty)
                                        }}</td> --}}
                                </tr>
                                @php($x++)
                                @endforeach
                                @if(count($list_data) == 0)
                                <tr>
                                    <td colspan="5" class="text-center text-secondary">
                                        No records found...!
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center">
                    <h6 class="text-secondary pt-3 pb-3">Please select or search an item...!</h6>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/packages/aos/aos.js') }}"></script>
<script>
    AOS.init({
        duration: 800,
        easing: "ease-in-out",
        once: true,
        mirror: false
    });
</script>
<script>
    document.addEventListener('keydown', function (event) {
        if (event.key === 's' && event.altKey) {
            event.preventDefault();
            const inputElement = document.getElementById('searchInput');
            if (inputElement) {
                inputElement.focus();
            }
        }
    });
</script>