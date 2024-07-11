@push('purchace-invoice', 'active')
<div>
    @php
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    @endphp
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-truck"></i>Purchase</li>
            <li class="breadcrumb-item"><a href="/purchase-invoice"><i class="fa fa-file"></i>Purchase Invoice</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Invoice Item(s)</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-4 mb-2 mb-md-0">
            @if ($purchase_invoice[0]->is_transfered == 0)
            <h6 class="table-title invoice-item-title bg-white">Invoice items total value&nbsp;:&nbsp;
                <span class="text-blue">{{ number_format($invoice_items_value, 2) }}</span>
            </h6>
            @else
            <div></div>
            @endif
        </div>

        <div class="col-12 col-md-8">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search Here..." aria-label="" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
                    </div>

                    @if (in_array('Save', $page_action) && $purchase_invoice && ($purchase_invoice[0]->is_transfered ==
                    0))
                    <button id="formOpen" wire:click="openModel" class="btn btn-tpos-primary ml-1"><i class="fa fa-plus"
                            aria-hidden="true"></i> Create New
                    </button>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if (session()->has('del_message'))
    <div class="alert alert-danger"
        style="width: 100%; margin:0 auto 15px auto; text-align:center; padding: 10px; border-radius:10px">
        {{ session('del_message') }}
    </div>

    <script>
        $(function() {
                setTimeout(function() {
                    $('.alert-danger').slideUp();
                }, 2000);
            });
    </script>
    @endif

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-secondary2">
                <div class="table-top-area">
                    @foreach ($purchase_invoice as $item)
                    <h6 class="table-title invoice-item-title">Company&nbsp;-&nbsp;
                        <span class="text-blue">{{ $item->company_name }}</span>
                    </h6>
                    <h6 class="table-title invoice-item-title">Invoice Number&nbsp;-&nbsp;
                        <span class="text-blue">{{ $item->invoice_number }}</span>
                    </h6>
                    <h6 class="table-title invoice-item-title">Date&nbsp;-&nbsp;
                        <span class="text-blue">{{ $item->invoice_date }}</span>&nbsp;
                        <small class="text-secondary">
                            (Created {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }})
                        </small>
                    </h6>
                    @endforeach
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item  {{ $count >= 10 ? '' : 'disabled' }}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{ $count . ' - ' . $count + 10 }}</a>
                            </li>
                            <li class="page-item {{ sizeOf($list_data) == 10 ? '' : 'disabled' }} ">
                                <a class="page-link" wire:click='add' href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="card-body p-2">
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-d-blue">No.</th>
                                        <th class="text-d-blue">Barcode</th>
                                        <th class="text-d-blue">Item</th>
                                        <th class="text-d-blue">Qty</th>
                                        <th class="text-d-blue text-right">Buy</th>
                                        <th class="text-d-blue text-right">Sell</th>
                                        <th class="text-d-blue text-right">Min Sell</th>
                                        <th class="text-d-blue">Expiry</th>
                                        <th class="text-d-blue">MFD</th>
                                        <th class="text-d-blue text-center">Action</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td>
                                        <h6 class="barcode_sub mt-1 mb-1"> {{ $row->barcode }}
                                            {!! $generator->getBarcode($row->barcode, $generator::TYPE_CODE_128) !!}
                                        </h6>
                                    </td>
                                    <td class="text-capitalized">
                                        <b>{{ $row->item_name }}</b>
                                        <br>
                                        {{ $row->category_name }} - {{ $row->brand_name }}
                                    </td>
                                    <td>{{ $row->quantity }}</td>
                                    <td class="text-right">{{ number_format($row->buy, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->sell, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->min_sell, 2) }}</td>
                                    <td>{{ $row->expiry }}<br>
                                        <?php
                                            $expiryDate = \Carbon\Carbon::parse($row->expiry);
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
                                    <td> {{ $row->mfd }}</td>

                                    <td class="text-center whitespace-nowrap">
                                        @if (in_array('Delete', $page_action) && $purchase_invoice &&
                                        ($purchase_invoice[0]->is_transfered == 0))
                                        <a href="#" class="text-danger mr-1"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action) && $purchase_invoice &&
                                        ($purchase_invoice[0]->is_transfered == 0))
                                        <a href="#" class="text-info mr-1" wire:click="updateRecord({{ $row->id }})"><i
                                                class="fa fa-pen" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                        @if (in_array('Print', $page_action))
                                        <a href="/print-barcode/{{ $row->id }}" class="text-success">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @php($x++)
                                @endforeach
                                @if (count($list_data) == 0)
                                <tr>
                                    <td colspan="10" class="text-center text-secondary">
                                        No records found...!
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="records-count-area" style="border-radius: 6px; margin-top:5px">
                        <small class="total-records">Total No.of Records :&nbsp;{{ $data_count }}</small>
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
                    <h5 class="modal-title text-light-green" id="formModal">
                        {{ $key != 0 ? 'Update Data' : 'Create Data' }}
                    </h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12 p-3">
                            <div class="filter-area p-3">
                                <h6 class="mb-3 text-blue"><i class="fa fa-filter mr-2"></i>Filter</h6>
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label class="text-d-blue">Select Category</label>
                                            <select class="form-control custom-form-input3 text-capitalized"
                                                wire:model="select_category">
                                                <option value="">-- Select Category -- </option>
                                                @foreach ($categories as $category)
                                                @if ($select_category == $category->id)
                                                <option class="text-capitalized" value="{{ $category->id }}" selected>{{
                                                    $category->category }}</option>
                                                @else
                                                <option class="text-capitalized" value="{{ $category->id }}">{{
                                                    $category->category }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label class="text-d-blue">Select Brand</label>
                                            <select class="form-control custom-form-input3 text-capitalized"
                                                wire:model="select_brand">
                                                <option value="">-- Select Brand -- </option>
                                                @foreach ($brands as $brand)
                                                @if ($select_brand == $brand->id)
                                                <option class="text-capitalized" value="{{ $brand->id }}" selected>
                                                    {{ $brand->brand }}</option>
                                                @else
                                                <option class="text-capitalized" value="{{ $brand->id }}">{{
                                                    $brand->brand }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-8 col-lg-6 offset-md-2 offset-lg-3">
                                        <div class="form-group text-center">
                                            <label class="text-d-blue">Search item by barcode</label>
                                            <input type="search" class="form-control custom-form-input3 text-center"
                                                wire:model='search_barcode' placeholder="Enter / Scan Barcode here...">
                                        </div>
                                    </div>
                                </div>
                                {{--
                                <hr> --}}
                                @if ($select_category != "" || $select_brand != "" || $search_barcode != "")
                                <div class="text-right">
                                    <button class="btn btn-sm clear-btn only-border" title="Clear filter"
                                        wire:click="clearFilter">
                                        &times;
                                        Clear all
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            @if (session()->has('item_not_found'))
                            <div class="alert alert-red text-center">
                                {{ session('item_not_found') }}
                            </div>
                            @endif
                        </div>

                        {{-- Item --}}
                        <div class="col-12 col-md-8 col-lg-8">
                            <div class="form-group">
                                <label class="text-blue">Select Item <font color="red">*</font></label>
                                <select class="form-control custom-form-input1 text-capitalized" wire:model="new_item"
                                    wire:change="loadData" wire:keydown.enter.prevent="saveData">
                                    <option value="">-- Select Item-- </option>
                                    @foreach ($items as $item)
                                    @if ($new_item == $item->id)
                                    <option class="text-capitalized" value="{{ $item->id }}" selected>{{
                                        $item->item_name }}
                                    </option>
                                    @else
                                    <option class="text-capitalized" value="{{ $item->id }}">{{ $item->item_name }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_item')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- for barcode purpose --}}
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="text-blue">Barcode(Leave for Auto)</label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Used older"
                                    wire:model="new_barcode" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_barcode')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        <style>
                            .existing-bcode-area {
                                border-radius: 8px;
                                background-color: #ffffff;
                                padding: 20px;
                                margin-bottom: 20px;
                                /* border: #3c942f 1px solid; */
                                box-shadow: 0px 0px 10px #cbdce0;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                            }
                        </style>

                        @if ($new_item != "" && $existing_barcode)
                        <div class="col-12">
                            <div class="existing-bcode-area">
                                <h6 class="text-blue mb-0">
                                    Existing Barcode : <span class="text-d-blue">{{$existing_barcode ?
                                        $existing_barcode : "----------"}}</span>
                                </h6>
                                <div>
                                    <button class="btn btn-sm btn-success"
                                        wire:click='setBarcode({{$existing_barcode}})'>
                                        <i class="fa fa-check mr-1"></i>Select
                                    </button>
                                    <button class="btn btn-sm btn-danger ml-2" wire:click="clearBarcode">
                                        <i class="fa fa-times mr-1"></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- quantity --}}
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="text-blue">Quantity <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1" placeholder="Quantity"
                                    wire:model="new_quantity" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_quantity')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- buy price --}}
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="text-blue">Buy Price <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1" placeholder="Buy Price"
                                    wire:model="new_buy" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_buy')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- sell price --}}
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="text-blue">Sell Price <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1" placeholder="Sell Price"
                                    required="" wire:model="new_sell" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_sell')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif

                            </div>
                        </div>

                        {{-- min sell price --}}
                        <div class="col-12 col-md-4 col-lg-4 ">
                            <div class="form-group">
                                <label class="text-blue">Min Sell Price <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1"
                                    placeholder="Min Sell price" required="" wire:model="new_min_sell"
                                    wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_min_sell')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Manufacture date --}}
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="text-blue">Manufacturing Date <font color="red">*</font></label>
                                <input type="date" class="form-control custom-form-input1" required=""
                                    wire:model="new_mfd_date" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_mfd_date')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>


                        {{-- Expiry date --}}
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="text-blue">Expiry Date <font color="red">*</font></label>
                                <input type="date" class="form-control custom-form-input1" required=""
                                    wire:model="new_expiry" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_expiry')
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
                        <button type="button" wire:click="closeModel" class="btn btn-danger m-t-15 waves-effect">Close
                        </button>
                        <button type="button" wire:click="saveData"
                            class="btn btn-success m-t-15 waves-effect">Save</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

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
                        If you want to remove this data, <b>you can't undone</b>, It will be affected that relevent
                        records!
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

    <script>
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
    </script>
</div>