@push('sales', 'active')
<div class='position-relative'>
    <img src="{{asset('assets/img/epos_icon.png')}}" class="epos-logo" alt="">
    <div class="row">
        <div class="col-12">
            <div class="card sales-head-section mb-3">
                <div class="logo--area">
                    <img src="{{ asset('assets/img/epos_icon.png') }}" class="logo--icon-img" alt="">
                    <img src="{{ asset('assets/img/epos-text_logo light.png') }}" class="logo--text-img" alt="">
                </div>
                <h3 class="text-light-green mt-1 mb-0">- Sales</h3>

                <button class="btn btn-seccess show-keyboard-button" wire:click="activeChange" type="button"
                    data-toggle="collapse" data-target="#showKeyboardShortcuts" aria-expanded="false"
                    aria-controls="showKeyboardShortcuts">
                    <i class="fa fa-keyboard mr-2"></i>Keyboard Shortcuts
                </button>
            </div>
        </div>
    </div>

    <div class="collapse {{ $active_window == true ? 'show' : '' }}" id="showKeyboardShortcuts">
        <div class="card card-body key-map-card">
            <div class="key-map-row">
                <div class="key-map-area">
                    <div class="key-cap">Ctrl</div>
                    <h6 class="key-shortcut">Enter/Clear Barcode</h6>
                </div>
                <div class="key-map-area">
                    <div class="key-cap">F9</div>
                    <h6 class="key-shortcut">Make Payment</h6>
                </div>
                <div class="key-map-area">
                    <div class="key-cap">F8</div>
                    <h6 class="key-shortcut">Add Customer Info</h6>
                </div>
                <div class="key-map-area">
                    <div class="key-cap">Del</div>
                    <h6 class="key-shortcut">Remove Customer Info</h6>
                </div>
                <div class="key-map-area">
                    <div class="key-cap">F4</div>
                    <h6 class="key-shortcut">Go Back</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-tpos-primary2 sales-card-primary p-3">
        <div class="row mb-3">
            <div class="col-12 col-md-4">
                <button wire:click="goBack" class="btn btn-warning go-back-btn mb-3">
                    <i class="fa fa-arrow-left"></i>
                    Back
                </button>
            </div>
            <div class="col-12 col-md-8">
                <div class="form-group mb-0">
                    <div class="input-group">
                        <input type="search" id="search_bar" class="form-control custom-form-input2 search" autofocus
                            wire:click="searchClear" wire:model="searchKey" wire:keyup="fetchData"
                            placeholder="Enter barcode or product name here..." aria-label="">
                        <div class="input-group-append">
                            <button class="btn btn-search" wire:click="fetchData">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--
        <hr> --}}
        <style>
            .custom-table-scroll {
                max-height: 300px;
                overflow-y: auto;
            }

            .table-head-sticky {
                position: sticky;
                top: 0;
            }
        </style>

        @if (isset($search_item_data) && ($searchKey) && sizeOf($list_data) == 0)
        <div class="mb-3">
            <div class="sub-card card-tpos-primary2 card-with-border overflow-hidden">
                <div class="table-top-area p-2">
                    <h6 class="table-title">Searched Items</h6>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item  {{($item_count>=10)?'':'disabled'}}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{$item_count." - ".$item_count+10}}</a>
                            </li>
                            <li class="page-item {{(sizeOf($search_item_data)==10)?'':'disabled'}} ">
                                <a class="page-link" wire:click='add' href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="table-responsive custom-table-scroll">
                    <table class="table table-bordered table-hover table-bg mb-0">
                        <thead>
                            <tr class="table-head-sticky">
                                <th class="text-d-blue">No.</th>
                                <th class="text-d-blue">Item Name</th>
                                <th class="text-d-blue text-right">Price</th>
                                <th class="text-d-blue text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(sizeOf($search_item_data) != 0)
                            @php($x = 1)
                            @foreach ($search_item_data as $single_item)
                            <tr>
                                <td>{{$x}}.</td>
                                <td class="text-capitalized">{{$single_item->item_name}}</td>
                                <td class="text-right">{{number_format($single_item->sell, 2)}}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" wire:click="setItem({{$single_item->id}})">
                                        <i class="fa fa-check mr-1"></i>
                                        Select
                                    </button>
                                </td>
                            </tr>
                            @php($x++)
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="text-center text-secondary">
                                    No items found...!
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    {{-- <div class="records-count-area">
                        <small class="total-records">Total number of items searched :&nbsp;{{ count($search_item_data)
                            }}</small>
                    </div> --}}
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            @if (sizeOf($bill_item) != 0)
            <div class="col-12">
                <div class="sale-left-panel p-2 mb-4">
                    @if ($customer_data)
                    <div class="table-responsive sales-table-area">
                        <table class="table table-bordered table-hover sales-table">
                            <thead>
                                <tr class="sales-table-head first">
                                    <th colspan="3" class="text-left text-d-blue border-none">Customer Detail</th>
                                    <th colspan="1" class="text-right border-none">
                                        <button class="btn btn-sm clear-btn only-border" title="Deselect Customer"
                                            wire:click="clearCustomerData">
                                            &times;
                                            Clear
                                        </button>
                                    </th>
                                </tr>
                                <tr class="sales-table-head">
                                    <th class="text-blue">Name</th>
                                    <th class="text-blue">Contact No.</th>
                                    <th class="text-blue text-right">Credit</th>
                                    <th class="text-blue text-right">Loyalty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-capitalized">{{ $customer_data->customer_name }}</td>
                                    <td>{{ $customer_data->tp }}</td>
                                    <td class="text-right">{{ number_format($customer_data->cridit, 2) }}</td>
                                    <td class="text-right">{{ $customer_data->loyality }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    @endif
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="text-left d-flex align-items-center">
                                <h6 class="mb-0 p-2 text-blue total-amt-text">
                                    <span class="text-d-blue">Sub Total :</span>
                                    {{ 'Rs. ' . number_format($item_total, 2) }}
                                </h6>
                                |
                                <h6 class="mb-0 p-2 text-blue total-amt-text">
                                    <span class="text-d-blue">No.of Items :</span>
                                    {{ count($bill_item) }}
                                </h6>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-right">
                                <button type="button" wire:click="openModel"
                                    class="btn btn-warning add-item-btn btn-md waves-effect">
                                    <i class="fa fa-user mr-2"></i> <span>Customer</span>
                                </button>

                                <button type="button" wire:click="openModelPayment()"
                                    class="btn btn-success add-item-btn btn-md waves-effect">
                                    <i class="fa fa-credit-card mr-2"></i><span>Complete Order/Pay</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-12 col-md-6 col-lg-6">
                <div class="p-1 sale-left-panel">
                    <div class="pr-1 pl-1 pt-1">
                        <h6 align="center" class="text-d-blue sale-head-area">Searched Item</h6>
                    </div>
                    <div class="pr-1 pl-1 pb-1">
                        @if (isset($list_data) && (sizeOf($list_data) != 0))
                        <div class="table-responsive sales-table-area">
                            <table class="table table-bordered sales-table">
                                <thead>
                                    <tr class="sales-table-head first">
                                        <th colspan="3" class="text-center text-d-blue">Item Detail</th>
                                    </tr>
                                    <tr class="sales-table-head">
                                        <th class="text-blue">Category</th>
                                        <th class="text-blue">Brand</th>
                                        <th class="text-blue">Measure</th>
                                    </tr>
                                </thead>
                                <tbody class="search-item-detail-body">
                                    <tr>
                                        <td class="text-capitalized">{{ $list_data[0]->category_name }}</td>
                                        <td class="text-capitalized">{{ $list_data[0]->brand_name }}</td>
                                        <td>{{ $list_data[0]->measure . $list_data[0]->measurement_name }}</td>
                                    </tr>

                                    <tr class="table-custom-border">
                                        <td><b>Item Name</b></td>
                                        <td colspan="2" class="text-capitalized">
                                            {{ $list_data[0]->item_name }}
                                            <br>
                                            <b>MFD:</b> {{ $list_data[0]->mfd }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><b>Sell Price</b></td>
                                        <td colspan="2">{{ number_format($list_data[0]->sell, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>Expiry</b></td>
                                        <td colspan="2">
                                            {{ $list_data[0]->expiry }}
                                            <br>
                                            <?php
                                                $expiryDate = \Carbon\Carbon::parse($list_data[0]->expiry);
                                                $diff = $expiryDate->diff(\Carbon\Carbon::now());
                                                $expiryInfo = '';
                                                if ($expiryDate->isToday()) {
                                                    echo '<small class="text-warning"><b>Item expires today.!</b></small>';
                                                } elseif ($expiryDate->isTomorrow()) {
                                                    echo '<small class="text-warning"><b>Item expires tomorrow.!</b></small>';
                                                } else {
                                                    if ($diff->y > 0) {
                                                        $expiryInfo .= $diff->y . ' year(s) ';
                                                    }
                                                    if ($diff->m > 0) {
                                                        $expiryInfo .= $diff->m . ' month(s) ';
                                                    }
                                                    if ($diff->d > 0) {
                                                        $expiryInfo .= $diff->d . ' day(s) ';
                                                    }
                                                    if (\Carbon\Carbon::now() < $expiryDate) {
                                                        echo '<small class="text-success text-lg"><b>Item expire within ' . $expiryInfo . '</b></small>';
                                                    } else {
                                                        echo '<small class="text-danger"><b>Item expired ' . $expiryInfo . ' ago</b></small>';
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="quantity-highlight main">
                                            <span class="text-warning">
                                                <b>Qty-Main :
                                                    {{ $list_data[0]->quantity }}
                                                </b>
                                            </span>
                                        </td>
                                        @if (sizeOf($branch_data) != 0)
                                        <td colspan="2" class="quantity-highlight branch">
                                            <span class="text-success">
                                                <b>Qty-Branch :
                                                    @if ($branch_data[0]->quantity == 0)
                                                    Out Of Stock In Branch
                                                    @else
                                                    {{ $branch_data[0]->quantity }}/
                                                    {{ $branch_data[0]->branch_total }}
                                                    @endif
                                                </b>
                                            </span>
                                        </td>
                                        @else
                                        <td colspan="2" class="quantity-highlight error">
                                            <span class="text-danger">
                                                Item Not in Branch..!
                                            </span>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>

                                @if (sizeOf($branch_data) != 0)

                                @if ($branch_data[0]->quantity != 0)
                                <tfoot>
                                    <tr class="sales-table-foot">
                                        {{-- <td>
                                            <div class="form-group mb-1">
                                                <label><b>Change New Price</b></label>
                                                <input type="number" class="form-control custom-form-input1"
                                                    wire:model="new_sel_price"
                                                    wire:keydown.enter.prevent="addToTemBIll">
                                                @error('new_sel_price')
                                                <small class="text-danger text-sm">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </td> --}}

                                        <td colspan="2">
                                            <h6 class="text-blue">Quantity</h6>
                                        </td>

                                        <td>
                                            <div class="form-group mb-1">
                                                {{-- <label><b>Quantity</b></label> --}}
                                                <input type="number" id="quantity_field"
                                                    class="form-control custom-form-input1 text-center"
                                                    wire:model="new_sel_quantity"
                                                    wire:keydown.enter.prevent="addToTemBIll">
                                                @if ($quantity_error)
                                                @error('new_sel_quantity')
                                                <small class="text-danger text-sm">{{ $message }}</small>
                                                @enderror
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                                @endif
                                @endif

                            </table>
                        </div>

                        @if (session()->has('main_message'))
                        <div class="alert alert-success">
                            {{ session('main_message') }}
                        </div>
                        @endif

                        @if (sizeOf($branch_data) != 0)
                        <div class="text-right">
                            <button type="button" wire:click="addToTemBIll"
                                class="btn btn-success add-item-btn btn-md mt-3 mb-2 waves-effect">
                                <i class="fa fa-plus mr-2"></i> <span>Add To Bill</span>
                            </button>
                        </div>
                        @endif
                        @else
                        <div class="item-not-found-area">
                            <img src="{{ asset('assets/img/no-results.png') }}" class="item-not-found-image" alt="">
                            <h5>No items..!</h5>
                        </div>
                        @endif

                    </div>

                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6">
                <div class="p-1 sale-left-panel">
                    <div class="pr-1 pl-1 pt-1">
                        <h6 align="center" class="text-d-blue sale-head-area">Bill Item(s)</h6>
                    </div>
                    <div class="pr-1 pl-1 pb-1">
                        @if (sizeOf($bill_item) != 0)
                        <div class="table-responsive sales-table-area">
                            <table class="table table-bordered table-hover sales-table">
                                <thead>
                                    <tr class="sales-table-head first">
                                        <th>#</th>
                                        <th>Details</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-right">U.P</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">Trash</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($x = 1)
                                    @foreach ($bill_item as $row)
                                    <tr>
                                        <td>{{ $x }}.</td>
                                        <td>
                                            <b>{{ $row->item_name }}</b><br>
                                            {{ $row->category_name }}{{ $row->brand_name }}<br>
                                        </td>
                                        <td class="text-center">{{ $row->quantity }}</td>
                                        <td class="text-right">{{ number_format($row->sell_price) }}</td>
                                        <td class="text-right">{{ number_format($row->quantity * $row->sell_price) }}
                                        </td>
                                        <td class="text-center">
                                            @if (in_array('Delete', $page_action))
                                            <a href="##" class="text-danger m-2"
                                                wire:click="deleteRecord({{ $row->id }})"><i class="fa fa-trash"
                                                    aria-hidden="true"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @php($x++)
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- <div class="text-right">
                            <button type="button" wire:click="closeModel"
                                class="btn btn-danger m-t-15 waves-effect">Clear
                            </button>
                            <button type="button" wire:click="textPrint"
                                class="btn btn-primary m-t-15 waves-effect">Back to
                                Main</button>
                        </div> --}}
                        @else
                        <div class="item-not-found-area">
                            <img src="{{ asset('assets/img/no-results.png') }}" class="item-not-found-image" alt="">
                            <h5>No items..!</h5>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Insert model here --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg" id="insert-model" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card-tpos-primary2">
                <div class="modal-header">
                    <h5 class="modal-title text-light-green" id="formModal">Customer Information</h5>
                    <a href="##" class="close custom-close" wire:click="closeModel">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="sub-info">
                        <div class="row">
                            {{-- Customer info --}}
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="text-blue">Search Customer</label>
                                    <input type="search" id="customer_field" class="form-control custom-form-input1"
                                        autofocus wire:model="customer_search" wire:keyup="fetchData"
                                        placeholder="Search Customer">
                                    @error('customer_search')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="text-blue">Select Customer</label>
                                    <select class="form-control custom-form-input1" wire:model="new_customer_id"
                                        wire:change="selectedCustomer" wire:keydown.enter.prevent="closeModel">
                                        <option value="0">-- Select Customer-- </option>
                                        @foreach ($customers as $customer)
                                        @if ($new_customer_id == $customer->id)
                                        <option value="{{ $customer->id }}" selected>
                                            {{ $customer->customer_name . '-' . $customer->tp }}
                                        </option>
                                        @else
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->customer_name . '-' . $customer->tp }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('new_customer_id')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
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

                        @if ($customer_data)
                        <div class="custom-table-area mb-3">
                            <div class="table-responsive">
                                <table class="table table-md table-striped table-bordered table-hover custom-table-bg">
                                    <tr class="tbl-row">
                                        <th class="text-white">Name</th>
                                        <th class="text-white">Contact Number</th>
                                        <th class="text-white text-right">Credit</th>
                                        <th class="text-white text-right">Loyality</th>
                                    </tr>

                                    <tr>
                                        <td>{{ $customer_data->customer_name }}</td>
                                        <td>{{ $customer_data->tp }}</td>
                                        <td class="text-right">{{ number_format($customer_data->cridit, 2) }}</td>
                                        <td class="text-right">{{ $customer_data->loyality }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="text-left mb-3">
                            <button class="btn btn-sm clear-btn only-border" title="Deselect Customer"
                                wire:click="clearCustomerData">
                                &times;
                                Clear
                            </button>
                        </div>
                        @endif


                        <div id="accordion">
                            <div class="accordion">
                                <div class="accordion-header mb-3" role="button" data-toggle="collapse"
                                    data-target="#panel-body-1" aria-expanded="true" wire:click="activeView">
                                    <h4><i class="fa fa-plus mr-1"></i> Create New Customer Not in List</h4>
                                </div>
                                <div class="accordion-body collapse {{ $active_panal == true ? 'show' : '' }} collapse-body"
                                    id="panel-body-1" data-parent="#accordion">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-6">
                                            <div class="form-group">
                                                <label class="text-blue">Customer Name</label>
                                                <input type="text" class="form-control custom-form-input1"
                                                    wire:model="new_name" placeholder="Customer Name"
                                                    wire:keydown.enter.prevent="saveData">
                                                @if ($customer_error)
                                                @error('new_name')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                                @enderror
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-6">
                                            <div class="form-group">
                                                <label class="text-blue">Contact Number</label>
                                                <input type="text" class="form-control custom-form-input1"
                                                    wire:model="new_contact" placeholder="Contact Number"
                                                    wire:keydown.enter.prevent="saveData">
                                                @if ($customer_error)
                                                @error('new_contact')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                                @enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if (session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                    @endif

                                    <div class="text-right">
                                        <button type="button" wire:click="saveData"
                                            class="btn btn-success m-t-15 waves-effect">
                                            Create New Customer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" wire:click="closeModel" class="btn btn-danger m-t-15 waves-effect">
                            Close
                        </button>
                        <button type="button" wire:click="closeModel" class="btn btn-success m-t-15 waves-effect">
                            Ok
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

    {{-- do payment model --}}
    <div wire:ignore.self class="modal fade" id="insert-model-payment" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-tpos-primary2">
                <div class="modal-header">
                    <h6 class="modal-title text-light-green" id="formModal">
                        Make Payment
                    </h6>
                    <a href="##" class="close custom-close" wire:click="closeModelPayment">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="payment-option-area">
                        <h6 class="text-blue mb-3">Payment Type</h6>
                        <div class="payment-selection-area">
                            <button wire:click="payOptions('Cash')" class="btn payment-btn 
                                {{ $payment_option == 'Cash' ? " btn-success" : "btn-secondary" }}">
                                Cash
                            </button>
                            <button wire:click="payOptions('Card')" class="btn payment-btn 
                                {{ $payment_option == 'Card' ? " btn-success" : "btn-secondary" }}">
                                </i>Card
                            </button>
                            @if ($is_credit_data == 1)
                            <button wire:click="payOptions('Credit')" class="btn payment-btn 
                                {{ $payment_option == 'Credit' ? " btn-success" : "btn-secondary" }}">
                                Credit
                            </button>
                            @endif
                        </div>

                        @if ($available_credit < $item_total && $payment_option=='Credit' ) <h6
                            class="text-danger text-center mb-0">Insuficient Credit Limit..!</h6>
                            @endif

                    </div>

                    <div class="item-total-display-area">
                        <h5 class="text-d-blue">Invoice Total :</h5>
                        <h5 class="text-blue">{{ number_format($item_total, 2) }}</h5>
                    </div>

                    {{-- paid by cash --}}
                    @if ($payment_option == 'Cash')
                    <div class="payment-input-section">
                        <div class="form-group mb-3">
                            <label class="text-blue">Amount :</label>
                            <div class="payment-input-area">
                                <div class="position-relative w-100">
                                    <input class="form-control custom-form-input1 border-success
                                        @if ($payment_error) @error('new_paid_cash') border-danger @enderror @endif"
                                        id="payment_field" type="number" placeholder="Enter the payment amount"
                                        wire:model="new_paid_cash" wire:keydown.enter.prevent="doBill" autofocus>
                                    @if ($payment_error)
                                    @error('new_paid_cash')
                                    <div class="text-danger pay-error-msg absolute">{{ $message }}</div>
                                    @enderror
                                    @endif
                                </div>

                                <button class="btn pay-clear-btn" wire:click="doZero">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- display balance --}}
                    @if ($new_paid_cash)
                    <div class="item-total-display-area balance">
                        <h5 class="text-d-blue">Balance :</h5>
                        <h5 class="text-blue balance">{{ number_format($new_paid_cash - $item_total, 2) }}</h5>
                    </div>
                    @endif
                    @endif
                    {{-- --}}

                    {{-- paid by card --}}
                    @if ($payment_option == 'Card')
                    <div class="payment-input-section">
                        <div class="form-group">
                            <label class="text-blue">Select Bank :</label>
                            <select class="form-control custom-form-input1 border-success 
                                @if ($payment_error) @error('new_bank') border-danger @enderror @endif"
                                wire:model="new_bank" wire:keydown.enter.prevent="doBill">
                                <option value="0">-- Select Bank -- </option>
                                @foreach ($banks as $bank_data)
                                @if ($new_bank == $bank_data->id)
                                <option value="{{ $bank_data->id }}" selected>
                                    {{ $bank_data->bank . '-' . $bank_data->account_number }}
                                </option>
                                @else
                                <option value="{{ $bank_data->id }}">
                                    {{ $bank_data->bank . '-' . $bank_data->account_number }}
                                </option>
                                @endif
                                @endforeach
                            </select>

                            @if ($payment_error)
                            @error('new_bank')
                            <div class="text-danger pay-error-msg">{{ $message }}</div>
                            @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="text-blue">Description :</label>
                            <textarea class="form-control custom-form-input1 border-success 
                            @if ($payment_error) @error('new_card_description') border-danger @enderror @endif"
                                wire:model="new_card_description" placeholder="Description"></textarea>
                            @if ($payment_error)
                            @error('new_card_description')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                            @endif
                        </div>
                    </div>
                    @endif
                    {{-- --}}
                    <hr>

                    <div class="row">
                        @if ($new_paid_cash >= $globle_tem_order_total)
                        <div class="col-12 col-sm-5 mx-auto">
                            <button class="btn btn-lg btn-success btn-pay" wire:click="doBill"
                                @disabled($available_credit < $item_total && $payment_option=='Credit' )>
                                BILL
                            </button>
                        </div>
                        @endif
                    </div>
                    {{-- <div class="text-right">
                        <button type="button" wire:click="closeModelPayment" class="btn btn-danger m-t-15 waves-effect">
                            Close
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- payment option compleate --}}

    {{-- print bill --}}
    <div id="printDocument" class="d-none" style="position: relative;">
        {{-- <div id="printDocument"> --}}
            @if ($propery_data)
            <div
                style="display: flex; align-items:center; justify-content:center; gap:10px; padding:5px 20px 5px 20px;">
                <img src="{{ $propery_data->logo_url }}" style="width: 80px; height:80px; object-fit:contain;" alt="">
                <h2 style="text-align: left; margin-bottom:15px; line-height:1.2rem;">{{ $propery_data->property_name }}
                    </h6>
            </div>
            <div style="text-align: center;">
                <p style="text-align: center; font-size:12px; line-height:0.2rem;">
                    <b>{{ $propery_data->email }} | {{ $propery_data->tp}}</b>
                </p>
                <p style="text-align: center; font-size:12px; line-height:0.1rem;">
                    <b>{{ $propery_data->address }}</b>
                </p>
            </div>
            @endif

            @if ($final_bill_data)
            <div
                style="text-align: center;display:flex; align-items:flex-start; justify-content:space-between; border-top: 1px solid #686868; border-bottom: 1px solid #686868; padding:0px 3px;">
                <div style="font-size: 12px; text-align:left;">
                    <p style="line-height:0.2rem;"><b>Customer</b>:
                        @if ($customer_data)
                        {{ $customer_data->customer_name ? $customer_data->customer_name : "------" }}
                        @else
                        <span>-------</span>
                        @endif
                    </p>
                    <p style="line-height:0.2rem;"><b>Mobile</b>:
                        @if ($customer_data)
                        {{ $customer_data->tp ? $customer_data->tp : "------" }}
                        @else
                        <span>-------</span>
                        @endif
                    </p>
                    <p style="line-height:0.2rem;"><b>User</b>:
                        {{ $user_name }}
                    </p>
                </div>
                <div style="font-size: 12px;text-align:left;">
                    <p style="line-height:0.2rem;"><b>Invoice No.</b>: {{ str_pad($final_bill_data->id, 8, '0',
                        STR_PAD_LEFT) }}</p>
                    <p style="line-height:0.2rem;"><b>Date</b>: {{ $final_bill_data->date }}</p>
                    <p style="line-height:0.2rem;"><b>Time</b>: {{ date('h:iA',
                        strtotime($final_bill_data->created_at)) }}</p>
                </div>
            </div>

            <div style="padding: 5px 5px 0 5px;">
                <table style="border: none; width:100%; margin-bottom:0px !important; text-transform:uppercase;">
                    <thead>
                        <tr style="border-bottom: 1px solid #686868; text-align:left">
                            <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868;">No.</th>
                            <th style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868;">Item</th>
                            <th
                                style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:center;">
                                QTY</th>
                            <th
                                style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:right;">
                                U.P</th>
                            <th
                                style="padding:4px 0;font-size:13px; border-bottom: 1px solid #686868; text-align:right;">
                                Amount</th>
                        </tr>
                    </thead>

                    @if (sizeOf($printBill) != 0)
                    <tbody>
                        @php($x = 1)
                        @php($final_total = 0)
                        @foreach ($printBill as $rowx)
                        <tr>
                            <td style="font-size:13px; font-weight:500 !important; ">{{ $x }}.</td>
                            <td style="font-size:13px; font-weight:500 !important; " colspan="4">{{ $rowx->item_name
                                }}</td>
                        </tr>
                        <tr>
                            <td style="padding:3px 0; font-weight:500 !important; border-bottom: 1px solid #686868;"
                                colspan="2"></td>
                            <td
                                style="font-size:13px; text-align:center; font-weight:500 !important;  border-bottom: 1px solid #686868;">
                                {{ $rowx->quantity }}
                            </td>
                            <td
                                style="font-size:13px; text-align:right; font-weight:500 !important; border-bottom: 1px solid #686868; ">
                                {{ $rowx->sell_price }}
                            </td>
                            <td
                                style="font-size:13px; text-align:right; font-weight:500 !important; border-bottom: 1px solid #686868; ">
                                {{ number_format($rowx->sell_price * $rowx->quantity, 2) }}
                            </td>
                        </tr>
                        @php($x++)
                        @php($final_total = $final_total + $rowx->sell_price * $rowx->quantity)
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4" style="padding:4px 0;font-size:13px; text-align:left;">
                                <b>Total</b>
                            </td>
                            <td style="padding:4px 0;font-size:13px; text-align:right;">
                                <b>{{ number_format($final_total, 2) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"
                                style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; text-align:left;">
                                <b>Paid Amount(<span style="text-transform:capitalize;">{{
                                        $final_bill_data->payment_type }}</span>)</b>
                            </td>
                            <td style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; text-align:right;">
                                <b>{{ number_format($final_bill_data->paid_amount, 2) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"
                                style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; border-bottom: 1px solid #686868; text-align:left;">
                                <b>Balance</b>
                            </td>
                            <td
                                style="padding:4px 0;font-size:13px;border-top: 1px solid #686868; border-bottom: 1px solid #686868; text-align:right;">
                                <b>{{ number_format($final_bill_data->balance, 2) }}</b>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @endif

            <p style="text-align: center; font-size:12px;margin-top:2px;">
                <b>நன்றி, மீண்டும் வருக.</b>
            </p>
            <p style="text-align: center; font-size:10px;line-height:0.2rem">
                விற்ற பொருட்கள் திருப்பி எடுக்கபட மாட்டாது,
            </p>
            <p style="text-align: center; font-size:12px;line-height:0.2rem;">
                பணம் திரும்ப வழங்கப்பட மாட்டாது.
            </p>
            <div style="border-bottom:1px solid #686868; margin: 6px 0 3px 0;"></div>
            <p style="text-align: center;line-height:0.1rem;margin-bottom:0px; font-size:12px;"><b>Solution by
                    EPOS.</b></p>
        </div>
    </div>

    {{-- delete model here --}}
    <div wire:ignore.self class="modal fade" id="delete-model" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-tpos-warning2">
                <div class="modal-header delete-model-header">
                    <h5 class="modal-title text-danger" id="formModal">Warning!</h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body delete-model-body">
                    <p class="text-center">
                        If you want to remove this Item, It will be back to branch store!
                    </p>

                    <div class="text-right">
                        <button type="button" wire:click="deleteCloseModel"
                            class="btn btn-success m-t-15 waves-effect">No </button>
                        <button type="button" wire:click="deleteRecord"
                            class="btn btn-danger m-t-15 waves-effect">Yes</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

    @push('scripts')
    <script>
        window.livewire.on('change-focus-other-field', function() {
            $("#search_bar").focus();
        });

        window.addEventListener('change-focus-other-field', event => {
            $('#search_bar').focus();
        });
    </script>
    @endpush

    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F8') {
                // Livewire.emit('focusOnCusomerSearch');
                Livewire.emit('openModel');
                Livewire.emit('closeModelPayment');
            } else if (event.key === 'F9') {
                Livewire.emit('openModelPayment');
                Livewire.emit('closeModel');
            } else if (event.key === 'Delete') {
                Livewire.emit('clearCustomerData');
            } else if (event.key === 'Control') {
                Livewire.emit('searchClear');
            } else if (event.key === 'F4') {
                Livewire.emit('goBack');
            }
        });
        
    
        // this is for payment
        window.addEventListener('insert-show-form-payment', event => {
            $('#insert-model-payment').modal('show');
        });
    
        window.addEventListener('insert-hide-form-payment', event => {
            $('#insert-model-payment').modal('hide');
            // $("#search_bar").focus();
            // $("#search_bar_x").focus();
        });

        // this is for insert
        window.addEventListener('insert-show-form', event => {
            $('#insert-model').modal('show');
        });
        window.addEventListener('insert-hide-form', event => {
            $('#insert-model').modal('hide');
        });
    
        // this is for delete
        window.addEventListener('delete-show-form', event => {
            $('#delete-model').modal('show');
        });
    
        window.addEventListener('delete-hide-form', event => {
            $('#delete-model').modal('hide');
        });
    
        window.addEventListener('do-print', event => {
            $('#insert-model-payment').modal('hide');
            printrow();
        });
    
        function printrow() {
            var prtrow = document.getElementById("printDocument");
            var WinPrint = window.open();
            WinPrint.document.write(prtrow.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
    
            $('#insert-model-payment').modal('hide');
        }
    </script>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('redirect', function(url, delay) {
                setTimeout(function() {
                    window.location.href = url;
                }, delay);
            });
        });
    </script>
</div>