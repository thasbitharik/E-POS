@push('sales-summary', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fa fa-shopping-bag"></i>Sales</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Sales Summary</li>
        </ol>
    </nav>

    <div class="row">
        @if (Auth::user()->user_type_id != 5)
        <div class="col-md-12 col-lg-6 col-sm-12">
            <div class="card card-tpos-primary2 p-3 sales-summary-card">
                <div class="pb-2 text-center">
                    <h4 class="text-light-green report-title">Sales Dashboard</h4>
                </div>

                <div>
                    <div class="transfer-summery-area">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12 col-border custom-padding2">
                                <div class="transfer-summery-sub-area">
                                    <div>
                                        <h6>Today Sales</h6>
                                        <div class="sub-sales-head">Cash</div>
                                        <div class="sub-sales-head">Card</div>
                                    </div>
                                    <div>
                                        <h6 class="main-sales">
                                            {{ $today_sales != 0 ? number_format($today_sales, 2) : 0.00 }}
                                        </h6>
                                        <div class="sub-sales">
                                            {{ $today_cash_sales != 0 ? number_format($today_cash_sales, 2) : 0.00 }}
                                        </div>
                                        <div class="sub-sales">
                                            {{ $today_card_sales != 0 ? number_format($today_card_sales, 2) : 0.00 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 custom-padding1 custom-padding2">
                                <div class="transfer-summery-sub-area">
                                    <div>
                                        <h6>Total Sales</h6>
                                        <div class="sub-sales-head">Cash</div>
                                        <div class="sub-sales-head">Card</div>
                                    </div>
                                    <div>
                                        <h6 class="main-sales">
                                            {{ $total_sales !=0 ? number_format($total_sales, 2) : 0.00 }}
                                        </h6>
                                        <div class="sub-sales">
                                            {{ $total_cash_sales !=0 ? number_format($total_cash_sales, 2) : 0.00 }}
                                        </div>
                                        <div class="sub-sales">
                                            {{ $total_card_sales !=0 ? number_format($total_card_sales, 2) : 0.00 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 col-border custom-padding">
                                <div class="transfer-summery-sub-area">
                                    <div>
                                        <h6>Today Bills</h6>
                                        <div class="sub-sales-head">Cash</div>
                                        <div class="sub-sales-head">Card</div>
                                    </div>
                                    <div>
                                        <h6 class="main-sales">
                                            {{ $today_bills !=0 ? $today_bills : 0 }}
                                        </h6>
                                        <div class="sub-sales">
                                            {{ $today_cash_bills !=0 ? $today_cash_bills : 0 }}
                                        </div>
                                        <div class="sub-sales">
                                            {{ $today_card_bills !=0 ? $today_card_bills : 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 custom-padding">
                                <div class="transfer-summery-sub-area">
                                    <div>
                                        <h6>Total Bills</h6>
                                        <div class="sub-sales-head">Cash</div>
                                        <div class="sub-sales-head">Card</div>
                                    </div>
                                    <div>
                                        <h6 class="main-sales">
                                            {{ $total_bills !=0 ? $total_bills : 0 }}
                                        </h6>
                                        <div class="sub-sales">
                                            {{ $total_cash_bills !=0 ? $total_cash_bills : 0 }}
                                        </div>
                                        <div class="sub-sales">
                                            {{ $total_card_bills !=0 ? $total_card_bills : 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-12 col-lg-6 col-sm-12">
            <div class="card card-tpos-primary2 p-3 sales-summary-card">
                <div class="pb-2 text-center">
                    <h4 class="text-light-green report-title">Select Date
                        <small class="text-white">(For Sales Report)</small>
                    </h4>
                </div>
                <div class="sub-card summary-area p-3">
                    <div class="row">
                        @if ($propertyId == 1)
                        <div class="col-12 col-md-4">
                            <div class="form-group mb-4 mb-md-0">
                                <label class="text-green">Property</label>
                                <select class="form-control custom-form-input1" wire:model="select_property">
                                    <option value="">-- Select Property --</option>
                                    @foreach ($filter_properties as $properties)
                                    <option value="{{$properties->id}}">{{$properties->property_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-12 {{ $propertyId == 1 ? " col-md-4" : "col-md-6" }}">
                            <div class="form-group mb-4 mb-md-0">
                                <label class="text-green">Start Date</label>
                                <input type="date" class="form-control custom-form-input1" wire:model="start_date">
                                @error('start_date')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 {{ $propertyId == 1 ? " col-md-4" : "col-md-6" }}">
                            <div class="form-group">
                                <label class="text-green">End Date</label>
                                <input type="date" class="form-control custom-form-input1" wire:model="end_date">
                                @error('end_date')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="custom-hr"></div>

                    <div class="">
                        <div class="text-right">
                            <button class="btn btn-for-print print-btn" id="print" onclick="printContent();"
                                type="button">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn btn-for-excel print-btn" id="print" onclick="ExportToExcel('xlsx');"
                                type="button">
                                <i class="fa fa-file-excel"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 {{ Auth::user()->user_type_id == 5 ? " col-lg-6" : "col-lg-12" }} col-sm-12">
            <div class="card card-tpos-primary2 p-3 sales-summary-card">
                <div class="report-filter-head mb-3">
                    <h4 class="text-light-green mb-0">
                        Filters
                    </h4>
                    <button class="btn btn-sm clear-btn only-border" title="Clear filter" wire:click="clearFilter">
                        &times;
                        Clear
                    </button>
                </div>
                <div class="sub-card summary-area overflow-hidden p-3">
                    <div class="row">
                        @if (Auth::user()->user_type_id == 2)
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group mb-4 mb-lg-0">
                                <label class="text-green">Select Counter</label>
                                <select class="form-control text-center custom-form-input1" wire:model="select_counter">
                                    <option value="0">All</option>
                                    @foreach ($counterData as $counter)
                                    <option value="{{ $counter->id }}">
                                        {{ $counter->counter }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group mb-4 mb-lg-0">
                                <label class="text-green">Select Cashier</label>
                                <select class="form-control text-center custom-form-input1" wire:model="select_user">
                                    <option value="0">All</option>
                                    @foreach ($cashiersData as $cashier)
                                    <option value="{{ $cashier->id }}">
                                        {{ ($cashier->id === 2) ? "Admin" : $cashier->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="col-12 col-md-6 {{ Auth::user()->user_type_id == 5 ? " col-lg-6" : "col-lg-3" }}">
                            <div class="form-group mb-4 mb-lg-0">
                                <label class="text-green">Select Payment Type</label>
                                <select class="form-control text-center custom-form-input1"
                                    wire:model="select_payment_type">
                                    <option value="">All</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                    <option value="Credit">Credit</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 {{ Auth::user()->user_type_id == 5 ? " col-lg-6" : "col-lg-3" }}">
                            <div class="form-group mb-0">
                                <label class="text-green">Select Customer</label>
                                <select class="form-control text-center custom-form-input1" wire:model="select_customer">
                                    <option value="0">All</option>
                                    @foreach ($customerData as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->customer_name }} - {{ $customer->tp }}
                                    </option>
                                    @endforeach
                                </select>

                                <div class="pretty p-switch p-fill mt-2">
                                    <input type="checkbox" wire:model="show_credit_customer" />
                                    <div class="state p-success">
                                        <label class="{{$show_credit_customer == true ? " text-blue" : "text-d-blue"
                                            }}">
                                            <b>Show credit customers only.</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card p-3 card-tpos-primary2 sales-summary-card card-tpos-secondary">
                <div class="pt-3 pr-3 pl-3 report-title">
                    <h4 align="center" class="text-light-green " style="color:rgb(0, 120, 200);">
                        Sales Summary</h4>

                    @if ($select_property != null)
                    <h4 class="text-blue text-center mt-3 mb-2" style="color: #4886ea; text-align:center;">
                        {{$property_name}}
                    </h4>
                    @endif

                    @if ($start_date)
                    <p class="text-center text-white mb-0" style="text-align: center"><b>From</b>
                        {{ \Carbon\Carbon::parse($start_date)->format('Y F j') }}
                        <b>to</b>
                        {{ \Carbon\Carbon::parse($end_date)->format('Y F j') }}
                    </p>
                    @endif
                </div>
                <div class="pt-3">
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg custom-border-white mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-d-blue">No.</th>
                                        <th class="text-d-blue">Invoice No.</th>
                                        <th class="text-d-blue">Date & Time</th>
                                        <th class="text-d-blue">Payment Type</th>
                                        <th class="text-d-blue text-center">View Bill</th>
                                        <th class="text-d-blue text-right">Amount</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @php($total = 0)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td># {{ str_pad($row->id, 8, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}
                                        <span class="text-info">|</span>
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('h:i A') }}
                                    </td>
                                    <td>{{ $row->payment_type }}</td>
                                    <td class="text-center">
                                        @if (in_array('View', $page_action))
                                        <a href="##" class="text-cyan"
                                            wire:click="openViewModel({{ $row->id }}, {{ $row->property_id }}, {{ $row->customer_id != null ? $row->customer_id : 0}})">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($row->amount, 2) }}</td>
                                </tr>
                                @php($x++)
                                @php($total = $total + $row->amount)
                                @endforeach
                                @if(count($list_data) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-secondary">
                                        No records found...!
                                    </td>
                                </tr>
                                @endif
                                @if (Auth::user()->user_type_id != 5)
                                <tr style="background-color: #cbe0e5; border-top: 2px solid #fff;">
                                    <td class="text-d-blue text-center" colspan="5">
                                        <b>Total :</b>
                                    </td>
                                    <td class="text-success text-right">
                                        <div class="total-text">
                                            {{ number_format($total, 2) }}
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <div id="printDocument" class="d-none">
                    <div class="pr-3 pl-3 pt-3">
                        <h3 class="d-none" style="text-align:center;color: #4886ea">
                            {{$property_name}}
                        </h3>

                        <h4 align="center" class="d-none" style="color:#14216a;">
                            Sales Report
                        </h4>

                        @if ($start_date)
                        <p class="text-center" style="text-align: center"><b>From</b>
                            {{ \Carbon\Carbon::parse($start_date)->format('Y F j') }}
                            <b>to</b>
                            {{ \Carbon\Carbon::parse($end_date)->format('Y F j') }}
                        </p>
                        @endif

                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive" style="border-radius: 0px;">
                            <table class="table table-striped table-bordered table-hover p-2"
                                style="border:solid rgba(0, 0, 0, 0.658); border-width:1px 0px 0px 1px; width:100%"
                                id="xlstable">
                                <tr align="center">
                                    <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; height:40px; width:20px;">
                                        #
                                    </th>
                                    <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                        Invoice No.
                                    </th>
                                    <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                        Date
                                    </th>
                                    <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                        Payment Type
                                    </th>
                                    <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                        Amount
                                    </th>

                                </tr>
                                @php($x = 1)
                                @php($total = 0)
                                @foreach ($list_data as $row)
                                <tr align="center">
                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0; height:40px;">{{ $x }}.</td>

                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                        # {{ str_pad($row->id, 8, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                        {{ $row->date }}
                                    </td>

                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                        {{ $row->payment_type }}
                                    </td>

                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0; text-align: right; padding:3px; color: #4daf80;"
                                        class="font-weight-bold text-right">
                                        {{ number_format($row->amount, 2) }}
                                    </td>

                                </tr>
                                <?php $x++;
                                $total = $total + $row->amount; ?>
                                @endforeach
                                @if (Auth::user()->user_type_id != 5)
                                <tr style="border-bottom: solid 3px; border-top: 2px solid;">
                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: center; height:40px;" colspan="4"
                                        class="text-center font-weight-bold">
                                        Total
                                    </td>
                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: right; color: #4daf80;"
                                        class="font-weight-bold text-right pr-1">
                                        <b>{{ number_format($total, 2) }}</b>
                                    </td>
                                </tr>
                                @endif
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- view model here --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg" id="view-model" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content card-tpos-primary2">
                <div class="modal-header">
                    <h5 class="modal-title text-light-green" id="formModal">Bill Information</h5>
                    <a href="##" type="button" class="close custom-close" wire:click="viewCloseModel"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    @if (sizeOf($property_data) != 0)
                    <h5 class="bill-top-sub text-light-green text-center">{{ $property_data[0]->property_name }}</h5>
                    @endif

                    @if (sizeOf($bill_data) != 0)
                    <div class="billview-top pt-2 pb-2">
                        <div>
                            <h6 class="text-white bill-top-sub">Invoice No. :&nbsp;
                                <span class="text-light-green">#
                                    {{ str_pad($bill_data[0]->invoice_number, 8, '0', STR_PAD_LEFT) }}
                                </span>
                            </h6>
                        </div>
                        <div>
                            <h6 class="text-white bill-top-sub">Date :&nbsp;
                                <span class="text-light-green">{{ $bill_data[0]->date }}</span>
                            </h6>
                        </div>
                    </div>

                    <div id="return" class="filter-selection-data-area warning {{$visibleReturnArea == false ? "
                        hide-this" : "show-this" }} p-3">
                        <h5 class="text-warning-custom"></h5>
                        <a href="##" type="button" class="close custom-close return-close" wire:click="closeReturnArea">
                            <span aria-hidden="true">&times;</span>
                        </a>
                        @if (sizeOf($return_item_data) != 0)
                        <div class='d-flex align-items-center flex-wrap gap-10'>
                            <h6 class="text-warning-custom mb-0">Return Item :
                                <span class="text-dark">{{$return_item_data[0]->item_name}}</span>
                            </h6>
                            <span class="text-secondary">|</span>
                            <h6 class="text-warning-custom mb-0">Purchased Quantity :
                                <span class="text-dark">{{$return_item_data[0]->bill_item_quantity}}</span>
                            </h6>
                        </div>
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col-12 col-lg-3 my-auto">
                                <div class="form-group mb-0 mb-lg-2">
                                    <label class="text-warning-custom">Return Quantity :</label>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="form-group mb-lg-2">
                                    <input type="number" class="form-control @if ($showError) @error('return_quantity') is-invalid @enderror @endif
                                    custom-form-input-warning" wire:model="return_quantity" id="quantity_field"
                                        wire:keydown.enter.prevent="return" placeholder="Enter return quantity">
                                    @if ($showError)
                                    @error('return_quantity')
                                    <small class="text-danger text-sm">{{ $message }}</small>
                                    @enderror
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-3 my-auto">
                                <div class="form-group mb-0 mb-lg-2">
                                    <label class="text-warning-custom">Return Reason :</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-9">
                                <div class="form-group mb-1 mb-lg-2">
                                    <textarea class="form-control @if ($showError) @error('return_reason') is-invalid @enderror @endif
                                    custom-form-input-warning" wire:model="return_reason"
                                        wire:keydown.enter.prevent="return"
                                        placeholder="Enter the reason for the return...">
                                    </textarea>
                                    @if ($showError)
                                    @error('return_reason')
                                    <small class="text-danger text-sm">{{ $message }}</small>
                                    @enderror
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-right pb-2">
                            <button type="button" wire:click="closeReturnArea"
                                class="btn btn-danger m-t-15 waves-effect">Cancel</button>
                            <button type="button" wire:click="return"
                                class="btn btn-success m-t-15 waves-effect">Return</button>
                        </div>
                    </div>

                    @if (session()->has('return_msg'))
                    <div class="alert alert-success"
                        style="width: 100%; margin:0 auto 15px auto; text-align:center; padding: 10px; border-radius:10px">
                        {{ session('return_msg') }}
                    </div>

                    <script>
                        $(function() {
                            setTimeout(function() {
                                $('.alert-success').slideUp();
                            }, 3000);
                        });
                    </script>
                    @endif

                    <div class="sub-card">
                        <div class="table-responsive bill-table">
                            <table class="table table-bordered table-hover table-bg mb-0">
                                <tr style="background-color: #c8dfe5; border-bottom: 2px solid #fff;">
                                    <th class="text-d-blue">No.</th>
                                    <th class="text-d-blue">Item</th>
                                    <th class="text-d-blue text-center">Quantity</th>
                                    <th class="text-d-blue text-right">Unit Price</th>
                                    @if (in_array('Update', $page_action))
                                    <th class="text-d-blue text-center">Return</th>
                                    @endif
                                    <th class="text-d-blue text-right">Amount</th>
                                </tr>
                                @php($y = 1)
                                @php($bill_total = 0)
                                @if (sizeOf($bill_item_data) != 0)
                                @foreach ($bill_item_data as $rowx)
                                <tr class="{{$return_id == $rowx->id ? " bg-success" : "" }} {{$rowx->is_returned == 1 ?
                                    " bg-warning-light" : "" }}">
                                    <td>{{ $y }}.</td>
                                    <td>{{ $rowx->item_name }}</td>
                                    <td class="text-center {{$rowx->quantity == 0 ? " text-danger font-weight-bold" : ""
                                        }}">{{ $rowx->quantity }}</td>
                                    <td class="text-right">{{ number_format($rowx->sell_price, 2) }}</td>
                                    @if (in_array('Update', $page_action))
                                    <td class="text-center">
                                        <button wire:click="openReturnArea({{ $rowx->id }})" title="Return"
                                            class="btn btn-sm {{ $return_id == $rowx->id ? " btn-white text-success"
                                            : "btn-warning text-white" }} rtn-btn p-2" @disabled(($rowx->quantity == 0)
                                            && ($rowx->is_returned == 1))>
                                            <i class="fa {{$return_id == $rowx->id ? " fa-check" : " fa-undo" }}"
                                                aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    @endif
                                    <td class="text-right">
                                        {{ number_format($rowx->sell_price * $rowx->quantity, 2)}}
                                    </td>

                                </tr>
                                @php($y++)
                                @php($bill_total = $bill_total + ($rowx->sell_price * $rowx->quantity))
                                @endforeach
                                @endif

                                <tr style="background-color: #c8dfe5; border-top: 1px solid #fff;">
                                    <td class="text-d-blue text-left" colspan="4">
                                        <b>Total :</b>
                                    </td>
                                    <td class="text-success text-right" colspan="2">
                                        <div class="total-text">
                                            {{ number_format($bill_total, 2) }}
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color: #c8dfe5; border-top: 1px solid #fff;">
                                    <td class="text-d-blue text-left" colspan="4">
                                        <b>Paid Amount (Cash):</b>
                                    </td>
                                    <td class="text-info text-right" colspan="2">
                                        <div class="total-text">
                                            {{ number_format($bill_data[0]->paid_amount, 2) }}
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color: #c8dfe5; border-top: 1px solid #fff;">
                                    <td class="text-d-blue text-left" colspan="4">
                                        <b>Balance (Credit):</b>
                                    </td>
                                    <td class="text-warning text-right" colspan="2">
                                        <div class="total-text">
                                            {{ number_format($bill_data[0]->balance, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($bill_data[0]->is_returned_bill == 1)
                    <hr>
                    <h6 class="text-white bill-top-sub warning">Returned Items</h6>
                    <div class="sub-card">
                        <div class="table-responsive bill-table">
                            <table class="table table-bordered table-hover table-bg-warning mb-0">
                                <tr style="background-color: #eedec3; border-bottom: 2px solid #fff;">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">Returned Item</th>
                                    <th class="text-dark text-center">Returned Quantity</th>
                                    <th class="text-dark text-right">Unit Price</th>
                                    <th class="text-dark text-right">Amount</th>
                                </tr>
                                @php($r = 1)
                                @php($returned_item_total = 0)
                                @if (sizeOf($returned_item_data) != 0)
                                @foreach ($returned_item_data as $rowz)
                                <tr>
                                    <td>{{ $r }}.</td>
                                    <td>{{ $rowz->item_name }}</td>
                                    <td class="text-center">{{ $rowz->returned_quantity }}</td>
                                    <td class="text-right">{{ number_format($rowz->sell_price, 2) }}</td>
                                    <td class="text-right">
                                        {{ number_format($rowz->sell_price * $rowz->returned_quantity, 2)}}
                                    </td>

                                </tr>
                                @php($r++)
                                @php($returned_item_total = $returned_item_total + ($rowz->sell_price *
                                $rowz->returned_quantity))
                                @endforeach
                                @endif

                                <tr style="background-color: #eedec3; border-top: 1px solid #fff;">
                                    <td class="text-dark text-left" colspan="4">
                                        <b>Total :</b>
                                    </td>
                                    <td class="text-warning-custom text-right" colspan="2">
                                        <div class="total-text warning">
                                            {{ number_format($returned_item_total, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    @endif

                    @if ($customer_data)
                    <hr>
                    <h6 class="text-white bill-top-sub">Customer Detail</h6>

                    <style>
                        .for-customer {
                            background-color: #c8dfe5 !important;
                        }

                        .for-cus {
                            background-color: #f2fdff !important;
                        }

                        .for-cus h6 {
                            color: #14216a;
                            font-size: 15px;
                        }
                    </style>
                    <div class="transfer-summery-area for-customer mt-3">
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-border1 mb-2">
                                <div class="transfer-summery-sub-area for-cus">
                                    <h6>Name</h6>
                                    <h6>
                                        {{ $customer_data[0]->customer_name }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 mb-2">
                                <div class="transfer-summery-sub-area for-cus">
                                    <h6>Contact No.</h6>
                                    <h6>
                                        {{ $customer_data[0]->tp }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-border1">
                                <div class="transfer-summery-sub-area for-cus">
                                    <h6>Credit</h6>
                                    <h6>
                                        {{ $customer_data[0]->cridit }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 custom-padding">
                                <div class="transfer-summery-sub-area for-cus">
                                    <h6>Loyality</h6>
                                    <h6>
                                        {{ $customer_data[0]->loyality }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-print waves-effect" onclick="printrow();">
                            <i class="fa fa-print mr-1"></i>
                            Print Bill
                        </button>
                        <button type="button" wire:click="viewCloseModel" class="btn btn-danger waves-effect">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

    {{-- return model here --}}
    {{-- <div wire:ignore.self class="modal fade" id="return-model" tabindex="-1" role="dialog"
        aria-labelledby="returnModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content card-tpos-warning3">
                <div class="modal-header return-model-header">
                    <h5 class="modal-title text-warning-custom" id="returnModal">
                        Item Return
                    </h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body return-model-body">
                    @if (sizeOf($return_item_data) != 0)
                    <div class="bill-top-sub-warning mb-4">
                        <div class="mb-2">Item Name : <span class="text-dark">{{$return_item_data[0]->item_name}}</span>
                        </div>
                        <div class="">Quantity : <span
                                class="text-dark">{{$return_item_data[0]->bill_item_quantity}}</span></div>
                    </div>
                    <div class="form-group">
                        <label class="text-warning-custom">Return Quantity <font color="red">*</font></label>
                        <input type="number" class="form-control custom-form-input-warning" placeholder="Quantity"
                            wire:model="return_quantity">
                        @if ($showError)
                        @error('return_quantity')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="text-warning-custom">Return Reason <font color="red">*</font></label>
                        <textarea class="form-control custom-form-input-warning" wire:model="return_reason"
                            placeholder="Enter the reason for the return..."></textarea>
                        @if ($showError)
                        @error('return_reason')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>
                    @endif

                    <div class="text-right">
                        <button type="button" wire:click="returnCloseModel"
                            class="btn btn-danger m-t-15 waves-effect">Close</button>
                        <button type="button" wire:click="return"
                            class="btn btn-success m-t-15 waves-effect">Return</button>
                    </div>

                </div>
            </div>
        </div>
    </div> --}}
    {{-- return model end --}}

    {{-- print bill start --}}
    <div id="printBill" class="d-none" style="position: relative;">
        @if (sizeOf($property_data) != 0)
        <div style="display: flex; align-items:center; justify-content:center; gap:10px; padding:5px 20px 5px 20px;">
            <img src="{{ $property_data[0]->logo_url }}" style="width: 80px; height:80px; object-fit:contain;" alt="">
            <h2 style="text-align: left; margin-bottom:15px; line-height:1.25rem;">
                {{ $property_data[0]->property_name }}
            </h2>
        </div>
        <div style="text-align: center;">
            <p style="text-align: center; font-size:12px; line-height:0.2rem;">
                <b>{{ $property_data[0]->email }} | {{ $property_data[0]->tp }}</b>
            </p>
            <p style="text-align: center; font-size:12px; line-height:0.1rem;">
                <b>{{ $property_data[0]->address }}</b>
            </p>
        </div>
        @endif

        @if (sizeOf($bill_data) != 0)
        <div
            style="text-align: center;display:flex; align-items:flex-start; justify-content:space-between; border-top: 1px solid #686868; border-bottom: 1px solid #686868; padding:0px 3px;">
            <div style="font-size: 12px; text-align:left;">
                <p style="line-height:0.2rem;"><b>Customer</b>:
                    @if ($customer_data)
                    {{ $customer_data[0]->customer_name ? $customer_data[0]->customer_name : "------" }}
                    @else
                    <span>-------</span>
                    @endif
                </p>
                <p style="line-height:0.2rem;"><b>Mobile</b>:
                    @if ($customer_data)
                    {{ $customer_data[0]->tp ? $customer_data[0]->tp : "------" }}
                    @else
                    <span>-------</span>
                    @endif
                </p>
                <p style="line-height:0.2rem;"><b>User</b>:
                    {{ $bill_data[0]->user_name }}
                </p>
            </div>
            <div style="font-size: 12px;text-align:left;">
                <p style="line-height:0.2rem;"><b>Invoice No.</b>: {{ str_pad($bill_data[0]->invoice_number, 8, '0',
                    STR_PAD_LEFT) }}</p>
                <p style="line-height:0.2rem;"><b>Date</b>: {{ $bill_data[0]->date }}</p>
                <p style="line-height:0.2rem;"><b>Time</b>: {{ date('h:iA', strtotime($bill_data[0]->created_at)) }}</p>
            </div>
        </div>

        <div style="padding: 5px 5px 0 5px;">
            <table style="border: none; width:100%; margin-bottom:0px !important; text-transform:uppercase;">
                <thead>
                    <tr style="border-bottom: 1px solid #686868; text-align:left;">
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868;">No.</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868;">Item</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:center;">
                            QTY</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:right;">
                            U.P</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:right;">
                            Amount</th>
                    </tr>
                </thead>

                @if (sizeOf($bill_item_data) != 0)
                <tbody>
                    @php($z = 1)
                    @php($final_total = 0)
                    @foreach ($bill_item_data as $rowy)
                    <tr>
                        <td style="font-size:13px; font-weight:500 !important; ">{{ $z }}.</td>
                        <td style="font-size:13px; font-weight:500 !important; " colspan="4">{{ $rowy->item_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:3px 0; font-weight:500 !important; border-bottom: 1px solid #686868;"
                            colspan="2"></td>
                        <td
                            style="font-size:13px; text-align:center; font-weight:500 !important;  border-bottom: 1px solid #686868;">
                            {{ $rowy->quantity }}
                        </td>
                        <td
                            style="font-size:13px; text-align:right; font-weight:500 !important; border-bottom: 1px solid #686868; ">
                            {{ $rowy->sell_price }}
                        </td>
                        <td
                            style="font-size:13px; text-align:right; font-weight:500 !important; border-bottom: 1px solid #686868; ">
                            {{ number_format($rowy->sell_price * $rowy->quantity, 2) }}
                        </td>
                    </tr>
                    @php($z++)
                    @php($final_total = $final_total + ($rowy->sell_price * $rowy->quantity))
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4"
                            style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; text-align:left;">
                            <b>Total</b>
                        </td>
                        <td style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; text-align:right;">
                            <b>{{ number_format($final_total, 2) }}</b>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4"
                            style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; text-align:left;">
                            <b>Paid Amount(<span style="text-transform:capitalize;">{{ $bill_data[0]->payment_type
                                    }}</span>)</b>
                        </td>
                        <td style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; text-align:right;">
                            <b>{{ number_format($bill_data[0]->paid_amount, 2) }}</b>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4"
                            style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; border-bottom: 1px solid #686868; text-align:left;">
                            <b>Balance</b>
                        </td>
                        <td
                            style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; border-bottom: 1px solid #686868; text-align:right;">
                            <b>{{ number_format($bill_data[0]->balance, 2) }}</b>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>

            @if ($bill_data[0]->is_returned_bill == 1)
            <h4
                style="text-align: center; margin-bottom:0px; margin-top:0px; padding:5px 0; border-bottom: 1px solid #686868; background-color:#DDD;">
                Returned Items
            </h4>
            <table style="border: none; width:100%; margin-bottom:0px !important; text-transform:uppercase;">
                <thead>
                    <tr style="border-bottom: 1px solid #686868; text-align:left;">
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868;">No.</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868;">Reurned Item</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:center;">
                            QTY</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:right;">
                            U.P</th>
                        <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:right;">
                            Amount</th>
                    </tr>
                </thead>

                @if (sizeOf($returned_item_data) != 0)
                <tbody>
                    @php($s = 1)
                    @php($return_total = 0)
                    @foreach ($returned_item_data as $r_item)
                    <tr>
                        <td style="font-size:13px; font-weight:500 !important; ">{{ $s }}.</td>
                        <td style="font-size:13px; font-weight:500 !important; " colspan="4">{{ $r_item->item_name }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 0; font-weight:500 !important; border-bottom: 1px solid #686868;"
                            colspan="2"></td>
                        <td
                            style="font-size:13px; text-align:center; font-weight:500 !important;  border-bottom: 1px solid #686868;">
                            {{ $r_item->returned_quantity }}
                        </td>
                        <td
                            style="font-size:13px; text-align:right; font-weight:500 !important; border-bottom: 1px solid #686868; ">
                            {{ $r_item->sell_price }}
                        </td>
                        <td
                            style="font-size:13px; text-align:right; font-weight:500 !important; border-bottom: 1px solid #686868; ">
                            {{ number_format($r_item->sell_price * $r_item->returned_quantity, 2) }}
                        </td>
                    </tr>
                    @php($s++)
                    @php($return_total = $return_total + ($r_item->sell_price * $r_item->returned_quantity))
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4"
                            style="padding:4px 0;font-size:13px;border-bottom: double #686868; text-align:left;">
                            <b>Return Item(s) Total</b>
                        </td>
                        <td style="padding:4px 0;font-size:13px;border-bottom: double #686868; text-align:right;">
                            <b>{{ number_format($return_total, 2) }}</b>
                        </td>
                    </tr>
                </tfoot>
                @endif

            </table>
            @endif
        </div>
        @endif

        <p style="text-align: center; font-size:13px;margin-top:2px;">
            <b>நன்றி, மீண்டும் வருக.</b>
        </p>
        <p style="text-align: center; font-size:10px;line-height:0.2rem">
            விற்ற பொருட்கள் திருப்பி எடுக்கபட மாட்டாது,
        </p>
        <p style="text-align: center; font-size:12px;line-height:0.2rem;">
            பணம் திரும்ப வழங்கப்பட மாட்டாது.
        </p>
        <div style="border-bottom:1px solid #686868; margin: 6px 0 3px 0;"></div>
        <p style="text-align: center;line-height:0.1rem;margin-bottom:0px; font-size:12px;"><b>Solution by EPOS.</b></p>
    </div>
    {{-- print bill end --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <script>
        // for print report
        function printContent() {
            var prtContent = document.getElementById("printDocument");
            var WinPrint = window.open();
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }

        // for print bill
        function printrow() {
            var prtrow = document.getElementById("printBill");
            var WinBillPrint = window.open();
            WinBillPrint.document.write(prtrow.innerHTML);
            WinBillPrint.document.close();
            WinBillPrint.focus();
            WinBillPrint.print();
            WinBillPrint.close();
        }

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('xlstable');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('ExpenseReport.' + (type || 'xlsx')));
        }
    </script>

    <script>
        // this is for view
        window.addEventListener('view-show-form', event => {
            $('#view-model').modal('show');
        });
        window.addEventListener('view-hide-form', event => {
            $('#view-model').modal('hide');
        });

         // this is for return model
        window.addEventListener('return-show-form', event => {
            $('#return-model').modal('show');
        });
        window.addEventListener('return-hide-form', event => {
            $('#return-model').modal('hide');
        });
        
        window.addEventListener('focus-on-quantity-field', event => {
            $("#quantity_field").focus();
        });
    </script>
</div>