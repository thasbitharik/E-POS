@push('products', 'active')
<div>
    @php
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    @endphp

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-box"></i>Stock</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Products</li>
        </ol>
    </nav>

    <div class="data-filter-area">
        <div class="data-filter-head">
            <h6>Filters</h6>
            <button class="btn btn-sm clear-btn only-border" title="Clear filter" wire:click="clearFilter">
                &times;
                Clear
            </button>
        </div>

        <div class="row">
            <div class="col-12 col-md-4">
                <div class="form-group mb-3 mb-md-0">
                    <label for="select_category" class="filter-form-label">Filter by Category :</label>
                    <select class="custom-form-input1 form-control text-center" wire:model="select_category">
                        <option value="0">-- Select Category --</option>
                        @foreach ($filter_categories as $cat_filter)
                        <option value="{{ $cat_filter->id }}">
                            {{ $cat_filter->category }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-3 mb-md-0">
                    <label for="select_category" class="filter-form-label">Filter by Brand :</label>
                    <select class="custom-form-input1 form-control text-center" wire:model="select_brand">
                        <option value="0">-- Select Brand --</option>
                        @foreach ($filter_brands as $br_filter)
                        <option class="text-capitalized" value="{{ $br_filter->id }}">
                            {{ $br_filter->brand }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-0">
                    <label for="search_barcode" class="filter-form-label">Filter by Barcode/Name :</label>
                    <input type="search" class="form-control custom-form-input1" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search Product by Barcode/Name" autofocus>
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

    @if (session()->has('message'))
    <div class="alert alert-success save-message"
        style="width: 100%; margin:0 auto 15px auto; text-align:center; padding: 10px; border-radius:10px">
        {{ session('message') }}
    </div>

    <script>
        $(function() {
            setTimeout(function() {
                $('.alert-success').slideUp();
            }, 2000);
        });
    </script>
    @endif

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-secondary2">
                <div class="table-top-area">
                    <h4 class="table-title">Products</h4>
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
                                        <th class="text-d-blue text-center">Qty.</th>
                                        <th class="text-d-blue text-right">Buy Price</th>
                                        <th class="text-d-blue text-right">Sell Price</th>
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
                                    <td class="{{$row->quantity==0 ? " text-danger": "" }} text-center"><b>{{
                                            $row->quantity }}</b></td>
                                    <td class="text-right">{{ number_format($row->buy_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->sell_price, 2) }}</td>
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
                                        @if (in_array('Delete', $page_action))
                                        <a href="##" class="text-danger mr-1"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action))
                                        <a href="##" class="text-info mr-1"
                                            wire:click="updateRecord({{ $row->id }}, {{ $row->invoice_items_id }})"><i
                                                class="fa fa-pen" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                        @if (in_array('Print', $page_action))
                                        <a href="/print-barcode/{{ $row->invoice_items_id }}" class="text-success">
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
    <div wire:ignore.self class="modal fade" id="insert-model" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-tpos-primary2">
                <div class="modal-header">
                    <h5 class="modal-title text-light-green" id="formModal">
                        Change item details
                    </h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    <div class="row">
                        {{-- Item detail --}}
                        @if (sizeOf($selected_item_data) != 0)
                        <div class="col-12 pt-1 pl-3 pr-3 pb-3">
                            <div class="filter-area p-3">
                                <h6 class="mb-3 text-blue text-capitalized">Item :
                                    <span class="text-d-blue">
                                        {{ $selected_item_data[0]->item_name }}
                                    </span>
                                </h6>
                                <h6 class="text-blue">Buy Price : <span class="text-d-blue">{{
                                        number_format($selected_item_data[0]->buy_price, 2) }}</span></h6>
                                <hr>
                                <style>
                                    .barcode-area {
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        flex-direction: column;
                                        background-color: #FFF;
                                        border-radius: 10px;
                                        padding: 15px;
                                    }

                                    .barcode-show-area {
                                        max-height: 0;
                                        transition: max-height ease 0.5s, opacity ease 0.5s;
                                        opacity: 0;
                                    }

                                    .barcode-show-area.barcode-shown {
                                        max-height: 150px;
                                        opacity: 1;
                                    }

                                    .cng-bcode-area {
                                        background-color: #FFF;
                                        border-radius: 10px;
                                        border: 2px solid #cce0e4;
                                        box-shadow: 0px 0px 10px #bdd4d8;
                                    }
                                </style>
                                <div class="barcode-area">
                                    <h6 class="text-blue">Barcode</h6>
                                    {!! $generator->getBarcode($selected_item_data[0]->barcode,
                                    $generator::TYPE_CODE_128) !!}
                                    <b class="text-dark">{{ $selected_item_data[0]->barcode }}</b>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-12 mb-3">
                            <div class="cng-bcode-area p-3">
                                <div class="pretty p-default">
                                    <input type="checkbox" wire:model="change_barcode" />
                                    <div class="state p-success">
                                        <label class="text-d-blue"><b>Change barcode</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Barcode --}}
                        <div class="col-12 barcode-show-area {{$change_barcode == true ? " barcode-shown" : "" }}">
                            <div class="form-group">
                                <label class="text-blue">Change Barcode <font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Barcode"
                                    required="" wire:model="new_barcode" wire:keydown.enter.prevent="updateData">
                                @if ($showError)
                                @error('new_barcode')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Item name --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-blue">Change Item Name<font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Item Name"
                                    required="" wire:model="new_item_name" wire:keydown.enter.prevent="updateData">
                                @if ($showError)
                                @error('new_item_name')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Sell price --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-blue">Change Sell Price <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1" placeholder="Sell Price"
                                    required="" wire:model="new_sell" wire:keydown.enter.prevent="updateData">
                                @if ($showError)
                                @error('new_sell')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="text-right">
                                <button type="button" wire:click="closeModel"
                                    class="btn btn-danger m-t-15 waves-effect">Close</button>
                                <button type="button" wire:click="updateData"
                                    class="btn btn-success m-t-15 waves-effect">Update</button>
                            </div>
                        </div>
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
                        If you want to remove this data, <b>you can't undone</b>, It will be affected that
                        relevent
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