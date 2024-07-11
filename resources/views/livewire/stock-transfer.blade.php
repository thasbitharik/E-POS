@push('purchace-invoice', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fa fa-truck"></i>Purchase</li>
            <li class="breadcrumb-item"><a href="/purchase-invoice"><i class="fa fa-file"></i>Purchase Invoice</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Stock Transfer</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12">
            <a href="/purchase-invoice" class="btn btn-warning go-back-btn mb-3">
                <i class="fa fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="row">
        @if (isset($invoice_details) && (sizeOf($invoice_details) != 0))
        <div class="col-12 col-md-5">
            <h6 class="table-title invoice-item-title bg-white">Stock Transfer for - Invoice&nbsp;-
                <span class="text-blue">{{ $invoice_details[0]->invoice_number }}</span>
            </h6>
        </div>
        @endif
        <div class="col-12 col-md-7">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" id="search_bar" class="form-control custom-form-input2" autofocus
                        wire:click="searchClear" wire:model="searchKey" wire:keyup="fetchData"
                        placeholder="Search Here..." aria-label="">
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-primary2 sales-summary-card card-tpos-secondary">
                <div class="p-3">
                    <div class="report-title p-10 mb-2">
                        <h5 align="center" class="text-light-green mb-0">Item Details</h5>
                    </div>
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg custom-border-white mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-d-blue">Category</th>
                                        <th class="text-d-blue">Brand</th>
                                        <th class="text-d-blue">Item Name</th>
                                        <th class="text-d-blue">Buy Price</th>
                                        <th class="text-d-blue">Sell Price</th>
                                        <th class="text-d-blue">MFD</th>
                                        <th class="text-d-blue">Expiry</th>
                                    </tr>
                                </thead>
                                @if (sizeOf($list_data) != 0)
                                <tr>
                                    <td>{{ $list_data[0]->category_name }}</td>
                                    <td>{{ $list_data[0]->brand_name }}</td>
                                    <td>
                                        {{ $list_data[0]->item_name . '-' . $list_data[0]->measure .
                                        $list_data[0]->measurement_name }}
                                    </td>
                                    <td>{{ number_format($list_data[0]->buy, 2) }}</td>
                                    <td>{{ number_format($list_data[0]->sell, 2) }}</td>
                                    <td>{{ $list_data[0]->mfd }}</td>
                                    <td>
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
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card card-tpos-primary2 p-2 sales-summary-card">
                <h4 align="center" class="text-light-green report-title">Main Store</h4>
                <div class="sub-card summary-area p-3">
                    @if (sizeOf($list_data) != 0)
                    <h6 align="center" class="text-d-blue"> Available Quantity : {{ $list_data[0]->quantity }}</h6>
                    <div class="form-group">
                        <label class="text-blue">Quantity</label>
                        <input type="number" class="form-control custom-form-input1" wire:model="new_main_quantity"
                            wire:keydown.enter.prevent="mainToBranchTransfer" placeholder="Enter transfer quantity">
                        @error('new_main_quantity')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if (session()->has('main_message'))
                    <div class="alert alert-success">
                        {{ session('main_message') }}
                    </div>
                    @endif

                    <hr>

                    <div class="text-right">
                        <button type="button" wire:click="resetMainStockQuantity"
                            class="btn btn-danger waves-effect">Clear
                        </button>
                        <button type="button" wire:click="mainToBranchTransfer" class="btn btn-success waves-effect">Add
                            to Transfer</button>
                    </div>
                    @else
                    <h6 align="center" class="text-danger">Item not Found!</h6>
                    @endif
                </div>
            </div>

            @if (isset($invoice_details) && (sizeOf($invoice_details) != 0))
            <div class="card card-tpos-primary2 p-2 sales-summary-card">
                <h4 align="center" class="text-light-green report-title">Invoice Detail</h4>

                <div class="comp-det-area">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-d-blue">Company</h6>
                        </div>
                        <div class="col-6">
                            <h6 class="text-blue">{{ $invoice_details[0]->company_name }}</h6>
                        </div>
                    </div>
                    <div class="dashed-border m-t-15"></div>
                    <div class="row m-t-15">
                        <div class="col-6">
                            <h6 class="text-d-blue">Dealer</h6>
                        </div>
                        <div class="col-6">
                            <h6 class="text-blue">{{ $invoice_details[0]->dealer_name }}</h6>
                        </div>
                    </div>
                    <div class="dashed-border m-t-15"></div>
                    <div class="row m-t-15">
                        <div class="col-6">
                            <h6 class="text-d-blue">Invoice Date</h6>
                        </div>
                        <div class="col-6">
                            <h6 class="text-blue">{{ $invoice_details[0]->invoice_date }}</h6>
                        </div>
                    </div>
                    <div class="dashed-border m-t-15"></div>
                    <div class="row m-t-15">
                        <div class="col-6">
                            <h6 class="text-d-blue">Amount</h6>
                        </div>
                        <div class="col-6">
                            <h6 class="text-blue">{{ number_format($invoice_details[0]->amount, 2) }}</h6>
                        </div>
                    </div>
                    <div class="dashed-border m-t-15"></div>
                    <div class="row m-t-15">
                        <div class="col-6">
                            <h6 class="text-d-blue">Discount</h6>
                        </div>
                        <div class="col-6">
                            <h6 class="text-blue">{{ number_format($invoice_details[0]->discount, 2) }}</h6>
                        </div>
                    </div>
                    <div class="dashed-border m-t-15"></div>
                    <div class="row m-t-15">
                        <div class="col-6">
                            <h6 class="text-d-blue">VAT</h6>
                        </div>
                        <div class="col-6">
                            <h6 class="text-blue">{{ number_format($invoice_details[0]->vat, 2) }}</h6>
                        </div>
                    </div>
                    <div class="dashed-border m-t-15"></div>
                    <div class="row m-t-15">
                        <div class="col-6">
                            <h6 class="text-d-blue">Net Total</h6>
                        </div>
                        <div class="col-6">
                            <h6 class="text-blue">{{ number_format($invoice_details[0]->net_amount, 2) }}</h6>
                        </div>
                    </div>
                </div>

            </div>
            @endif
        </div>

        <div class="col-12 col-md-8 col-lg-8">
            <div class="card card-tpos-primary2 p-2 sales-summary-card">
                <div class="p-1">
                    <h4 align="center" class="text-light-green report-title">Store</h4>

                    <div class="sub-card summary-area p-3">
                        <div class="row p-1">
                            <div class="col-md-4 col-lg-4 col-sm-12">
                                <div class="form-group mb-4 mb-md-0">
                                    <select class="form-control custom-form-input1" wire:model="new_property_id">
                                        <option value="">-- Select Branch --</option>
                                        @foreach ($property_list as $property)
                                        @if ($new_property_id == $property->id)
                                        <option value="{{ $property->id }}" selected>
                                            {{ $property->property_name }}
                                        </option>
                                        @else
                                        <option value="{{ $property->id }}">{{ $property->property_name }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>

                                    @error('new_property_id')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>

                            <div class="col-12 col-md-4 col-lg-4 col-sm-12">
                                <div class="form-group mb-4 mb-md-0">
                                    <input type="date" class="form-control custom-form-input1"
                                        placeholder="Contact Number" wire:model="bill_date">
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4 col-sm-12">
                                @if ($new_property_id)
                                @if (sizeof($tem_list) != 0)
                                <button class="btn btn-transfer mb-0" wire:click="finalTransfer()">
                                    Transfer&nbsp;<i class="fa fa-share" aria-hidden="true"></i>
                                </button>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 mb-2">
                        <div class="transfer-summery-area">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-border">
                                    <div class="transfer-summery-sub-area">
                                        <h6>Unit Count</h6>
                                        <h6>
                                            @if ($total_items)
                                            {{ $total_items }}
                                            @else
                                            0
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 custom-padding1">
                                    <div class="transfer-summery-sub-area">
                                        <h6>No. of Items</h6>
                                        <h6>
                                            @if ($tem_number_of_item)
                                            {{ $tem_number_of_item }}
                                            @else
                                            0
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-border custom-padding pt-lg-2">
                                    <div class="transfer-summery-sub-area">
                                        <h6>Total Buy Value</h6>
                                        <h6>
                                            @if ($transfer_total)
                                            {{ number_format($transfer_total, 2) }}
                                            @else
                                            0.00
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 custom-padding pt-lg-2">
                                    <div class="transfer-summery-sub-area">
                                        <h6>Total Sell Value</h6>
                                        <h6>
                                            @if ($transfer_sell_total)
                                            {{ number_format($transfer_sell_total, 2) }}
                                            @else
                                            0.00
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pr-1 pl-1">
                    <h5 align="center" class="text-light-green report-title">Details</h5>
                </div>
                <div class="p-1">
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg custom-border-white mb-0">
                                @if (sizeOf($tem_list) != 0)
                                <thead>
                                    <tr>
                                        <th class="text-d-blue">No.</th>
                                        <th class="text-d-blue">Item Name</th>
                                        <th class="text-d-blue text-center">Qty.</th>
                                        <th class="text-d-blue text-right">Buy Price</th>
                                        <th class="text-d-blue text-right">Sell Price</th>
                                        <th class="text-d-blue text-right">Buy Total</th>
                                        <th class="text-d-blue text-right">Sell Total</th>
                                        <th class="text-d-blue text-center">Action</th>
                                        {{-- <th class="text-blue">Expiry</th> --}}
                                    </tr>
                                </thead>
                                @php($x=1)
                                @foreach ($tem_list as $rec)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td>
                                        {{ $rec->item_name . '-' . $rec->measure . $rec->measurement_name }}<br>
                                        <small><b>{{ $rec->category_name }}-{{ $rec->brand_name }}</b></small>

                                    </td>
                                    <td class="text-center">{{ $rec->quantity }}</td>
                                    <td class="text-right">{{ number_format($rec->buy_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($rec->sell_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($rec->buy_price * $rec->quantity, 2) }}</td>
                                    <td class="text-right">{{ number_format($rec->sell_price * $rec->quantity, 2) }}</td>
                                    <td class="text-center">
                                        @if (in_array('Delete', $page_action))
                                        <a href="##" class="text-danger m-2"
                                            wire:click="deleteOpenModel({{ $rec->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @php($x++)
                                @endforeach
                                @else
                                @if(count($tem_list) == 0)
                                <tr>
                                    <td colspan="5" class="text-center text-secondary">
                                        No records found...!
                                    </td>
                                </tr>
                                @endif
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

    {{-- transferBill --}}
    <div id="printDocument" class="d-none">
        <div style="text-align: center; align-items: center;  margin-top:12px;">

            <table style="border:solid rgba(0, 0, 0, 0.658); border-width:1px 0px 0px 1px; width:99%">
                <tr style="background-color: rgba(193, 188, 242, 0.338);">
                    <th colspan="4"
                        style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; text-align: center;">
                        TRANSFER NOTE</th>
                </tr>
                <tr style="height:17px;">
                    <td colspan="3"
                        style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px; text-align:center;">
                        @if ($branch)
                        <b> {{ $branch->property_name }}</b>
                        @endif
                        Teseceg
                    </td>
                </tr>
                <tr style="height:17px;">
                    <td style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                        @if ($transferBill)
                        Transfer Note No. : {{ str_pad($transferBill->id, 8, '0', STR_PAD_LEFT) }}
                        <span></span>
                        @endif
                    </td>
                    <td style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                        @if (isset($invoice_details) && (sizeOf($invoice_details) != 0))
                        Invoice No. : {{ $invoice_details[0]->invoice_number }}
                        @endif
                    </td>
                    <td style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                        @if ($transferBill)
                        {{ $transferBill->trasfer_date }}
                        @endif
                    </td>
                </tr>
            </table>
            <hr>
        </div>

        <table class="table table-striped table-bordered table-hover p-2"
            style=" border:solid rgba(0, 0, 0, 0.658); border-width:1px 0px 0px 1px; width:99%">
            <tr>
                <th style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;">#</th>
                <th width="30%" style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px; ">
                    <small>Item</small>
                </th>
                <th style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                    <small>Quantity</small>
                </th>
                <th style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                    <small>Buy Price</small>
                </th>
                <th style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                    <small>Sell Price</small>
                </th>
                <th style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                    <small>Buy Total Amount</small>
                </th>
                <th style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                    <small>Sell Total Amount</small>
                </th>
            </tr>
            @php($x = 1)
            @php($final_total = 0)
            @php($final_sell_total = 0)
            @if (sizeOf($bill_items) != 0)
            @foreach ($bill_items as $rowx)
            <tr>
                <td style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">
                    {{ $x }}
                </td>
                <td style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; font-size:10px;">

                    {{ $rowx->item_name . '-' . $rowx->measure . $rowx->measurement_name }}<br>
                    <b>{{ $rowx->category_name }}-{{ $rowx->brand_name }}</b>

                </td>
                <td
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;  text-align: center; font-size:10px;">
                    <b> {{ $rowx->quantity }}</b>

                </td>
                <td
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;  text-align: right; padding-right: 5px; font-size:10px;">
                    <b> {{ number_format($rowx->buy_price, 2) }}</b>
                </td>
                <td
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;  text-align: right; padding-right: 5px; font-size:10px;">
                    <b> {{ number_format($rowx->sell_price, 2) }}</b>
                </td>
                <td
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;  text-align: right; padding-right: 5px; font-size:10px;">
                    <b>{{ number_format($rowx->buy_price * $rowx->quantity, 2) }}</b>
                </td>
                <td
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;  text-align: right; padding-right: 5px; font-size:10px;">
                    <b>{{ number_format($rowx->sell_price * $rowx->quantity, 2) }}</b>
                </td>
            </tr>
            @php($x++)
            @php($final_total = $final_total + $rowx->buy_price * $rowx->quantity)
            @php($final_sell_total = $final_sell_total + $rowx->sell_price * $rowx->quantity)
            @endforeach
            @endif

            <tr>
                <td colspan="5"
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0; text-align: center; font-size:14px;">
                    <b>Total : </b>
                </td>
                <td
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;  text-align: right; padding-right: 5px; font-size:14px;">
                    <b> {{ number_format($final_total, 2) }}</b>
                </td>
                <td
                    style="border:solid rgba(0, 0, 0, 0.658); border-width:0 1px 1px 0;  text-align: right; padding-right: 5px; font-size:14px;">
                    <b> {{ number_format($final_sell_total, 2) }}</b>
                </td>
            </tr>

        </table>
    </div>

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
        window.addEventListener('do-print', event => {
            printrow();
        });

        // this is for delete
        window.addEventListener('delete-show-form', event => {
            $('#delete-model').modal('show');
        });

        window.addEventListener('delete-hide-form', event => {
            $('#delete-model').modal('hide');
        });

        function printrow() {
            var prtrow = document.getElementById("printDocument");
            var WinPrint = window.open();
            WinPrint.document.write(prtrow.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
    </script>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('redirect', function (url, delay) {
                setTimeout(function () {
                    window.location.href = url;
                }, delay);
            });
        });
    </script>
</div>