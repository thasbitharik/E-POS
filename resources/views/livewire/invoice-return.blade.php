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
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Return Items</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-8 offset-md-4">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search by name/barcode here..." aria-label="" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
                    </div>

                    @if (in_array('Save', $page_action))
                    <button wire:click="openReturnModel" class="btn btn-warning return-item-btn ml-1">
                        <i class="fa fa-undo mr-1" aria-hidden="true"></i> Return Item
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

    @if (session()->has('return_message'))
    <div class="alert alert-success save-message"
        style="width: 100%; margin:0 auto 15px auto; text-align:center; padding: 10px; border-radius:10px">
        {{ session('return_message') }}
    </div>
    <script>
        $(function() {
                setTimeout(function() {
                    $('.save-message').slideUp();
                }, 2000);
            });
    </script>
    @endif

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-secondary2">
                <div class="table-top-area">
                    <h4 class="table-title">Returned Items</h4>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item {{($count>=10) ? '' : 'disabled'}}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{$count." - ".$count+10}}</a></li>
                            <li class="page-item {{(sizeOf($return_items_data)==10) ?'' : 'disabled'}} ">
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
                                        <th class="text-d-blue">Item</th>
                                        <th class="text-d-blue text-center">Returned Quantity</th>
                                        <th class="text-d-blue text-center">Available Quantity</th>
                                        <th class="text-d-blue">Returned Date</th>
                                        <th class="text-d-blue text-center">Actions</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($return_items_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-capitalized">{{ $row->item_name }}</td>
                                    <td class="text-capitalized text-center">{{ $row->returned_quantity }}</td>
                                    <td class="text-capitalized text-center">{{ $row->stock_quantity }}</td>
                                    <td class="text-capitalized">{{ $row->returned_date }}</td>

                                    <td class="text-center whitespace-nowrap">
                                        @if (in_array('Delete', $page_action))
                                        <a href="##" class="text-danger mr-1"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action))
                                        <a href="##" class="text-info" wire:click="updateRecord({{ $row->id }})"><i
                                                class="fa fa-pen" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @php($x++)
                                @endforeach
                                @if(count($return_items_data) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-secondary">
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

    {{-- Return model here --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg" id="item_return_modal" tabindex="-1" role="dialog"
        aria-labelledby="returnModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content card-tpos-warning3 custom">
                <div class="modal-header return-model-header">
                    <h5 class="modal-title text-warning-custom" id="returnModal">
                        {{ $key == 0 ? "Return item" : "Update the record." }}
                    </h5>
                    <a href="##" type="button" class="close custom-close" wire:click="closeReturnModal">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body return-model-body">
                    @if ($key == 0)
                    <div class="bill-top-sub-warning mb-3">
                        <div class="bill-top-sub-head">
                            <h6 class="text-warning-custom mb-0">Filter Items</h6>
                            @if ($return_select_category || $return_select_brand || $return_search_item)
                            <div class="text-right">
                                <button class="btn btn-sm clear-btn only-border" title="Clear filter"
                                    wire:click="clearFilter">
                                    &times;
                                    Clear all
                                </button>
                            </div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="text-warning-custom">Select Category :</label>
                                    <select class="form-control custom-form-input-warning"
                                        wire:model="return_select_category">
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $c)
                                        <option value="{{ $c->id }}">
                                            {{ $c->category }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="text-warning-custom">Select Brand :</label>
                                    <select class="form-control custom-form-input-warning"
                                        wire:model="return_select_brand">
                                        <option value="">-- Select Brand --</option>
                                        @foreach ($brands as $b)
                                        <option class="text-capitalized" value="{{ $b->id }}">
                                            {{ $b->brand }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group mb-lg-2">
                                    <label class="text-warning-custom">Select Item :</label>
                                    <select class="form-control custom-form-input-warning"
                                        wire:model="return_select_item">
                                        @if (($return_select_category == "") && ($return_select_brand == ""))
                                        <option value="">-- Select Any Category/Brand --</option>
                                        @else
                                        <option value="">-- Select Item --</option>
                                        @endif
                                        @foreach ($items as $i)
                                        <option class="text-capitalized" value="{{ $i->id }}">
                                            {{ $i->item_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group mb-lg-2">
                                    <label class="text-warning-custom">Search Item :</label>
                                    <input type="search" class="form-control custom-form-input-warning"
                                        placeholder="Search item by name/barcode" wire:model="return_search_item">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="filter-selection-data-area {{$visibleReturnArea == false ? " hide-this" : "show-this" }}
                        p-3">
                        <a href="##" type="button" class="close custom-close return-close" wire:click="clearReturn">
                            <span aria-hidden="true">&times;</span>
                        </a>
                        @if (sizeOf($selected_stock_data) != 0)
                        <div class='d-flex align-items-center flex-wrap gap-10'>
                            <h6 class="text-warning-custom mb-0">Return Item :
                                <span class="text-dark">{{$selected_stock_data[0]->item_name}}</span>
                            </h6>
                            <span class="text-secondary">|</span>
                            <h6 class="text-warning-custom mb-0">Stock Quantity :
                                <span class="text-dark">{{$selected_stock_data[0]->quantity}}</span>
                            </h6>
                        </div>
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group mb-lg-2">
                                    <label class="text-warning-custom">Return Date :</label>
                                    <input type="date" class="form-control @if ($showError) @error('new_return_date') is-invalid @enderror @endif 
                                        custom-form-input-warning" wire:model="new_return_date"
                                        wire:keydown.enter.prevent="returnItem">
                                    @if ($showError)
                                    @error('new_return_date')
                                    <small class="text-danger text-sm">{{ $message }}</small>
                                    @enderror
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group mb-lg-2">
                                    <label class="text-warning-custom">Return Quantity :</label>
                                    <input type="number" class="form-control @if ($showError) @error('new_return_quantity') is-invalid @enderror @endif
                                        custom-form-input-warning" wire:model="new_return_quantity" id="quantity_field"
                                        wire:keydown.enter.prevent="returnItem" placeholder="Enter return quantity">
                                    @if ($showError)
                                    @error('new_return_quantity')
                                    <small class="text-danger text-sm">{{ $message }}</small>
                                    @enderror
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session()->has('message'))
                    <div class="alert alert-success p-2 text-center">
                        {{ session('message') }}
                    </div>
                    @endif

                    @if ($key == 0)
                    @if ($return_select_category || $return_select_brand || $return_search_item)
                    <div class="filter-selection-data-area mb-0">
                        <div class="table-top-area warning">
                            <h4 class="table-title-warning">Items</h4>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item {{($stock_page_count>=5)?'':'disabled'}}">
                                        <a class="page-link" wire:click='previousPage' href="##" tabindex="-1">Prev</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="##">
                                            {{$stock_page_count." - ".$stock_page_count+5}}
                                        </a>
                                    </li>
                                    <li class="page-item {{(sizeOf($stock_data)==5)?'':'disabled'}} ">
                                        <a class="page-link" wire:click='nextPage' href="##">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="sub-card warning">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-warning-custom">No.</th>
                                            <th class="text-warning-custom">Barcode</th>
                                            <th class="text-warning-custom">Item Name</th>
                                            <th class="text-warning-custom text-center">Available Qty.</th>
                                            <th class="text-warning-custom text-right">Buy Price</th>
                                            <th class="text-warning-custom text-center">Select</th>
                                        </tr>
                                    </thead>
                                    @php($y = 1)
                                    @foreach ($stock_data as $stock)
                                    <tr>
                                        <td>{{ $y }}.</td>
                                        <td class="text-capitalized">
                                            <h6 class="mt-2 mb-2"> {{ $stock->barcode }}
                                                {!! $generator->getBarcode($stock->barcode, $generator::TYPE_CODE_128)
                                                !!}
                                            </h6>
                                        </td>
                                        <td class="text-capitalized">
                                            <b>{{ $stock->item_name }}</b>
                                            <br>
                                            <small class="text-orange">
                                                <b>{{ $stock->category_name }} - {{ $stock->brand_name }}</b>
                                            </small>
                                        </td>
                                        <td class="text-center {{ $stock->quantity==0 ? " text-danger" : "" }}">
                                            {{ $stock->quantity }}
                                        </td>
                                        <td class="text-right">{{ number_format($stock->buy_price, 2) }}</td>

                                        <td class="text-center whitespace-nowrap">
                                            @if (in_array('Save', $page_action))
                                            <button class="btn btn-sm {{ $stock_id == $stock->id ? " btn-success"
                                                : "btn-warning" }} rtn-btn text-white p-2" @disabled($stock->quantity ==
                                                0)
                                                wire:click="addToReturn({{ $stock->id }})">
                                                <i class="fa {{$stock_id == $stock->id ? " fa-check" : "fa-undo" }}"
                                                    aria-hidden="true"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @php($y++)
                                    @endforeach
                                    @if(count($stock_data) == 0)
                                    <tr>
                                        <td colspan="6" class="text-center text-secondary">
                                            No records found...!
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <div class="no-selection-area mb-3">
                                <h6 class="text-center mb-0 font-weight-600">Please select any filter or search an item!
                                </h6>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <div class="text-right">
                        <button type="button" wire:click="returnItem" @disabled($stock_id==0)
                            class="btn btn-success m-t-15 waves-effect">
                            {{ $key == 0 ? "Return" : "Update" }}
                        </button>
                        <button type="button" wire:click="closeReturnModal" class="btn btn-danger m-t-15 waves-effect">
                            Close
                        </button>
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
                        If you want to remove this item, <b>you can't undone</b>, It will be affected that relevent
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
</div>

<script>
    // this is for invoice return
    window.addEventListener('item-return-modal-show', event => {
        $('#item_return_modal').modal('show');
    });

    window.addEventListener('item-return-modal-hide', event => {
        $('#item_return_modal').modal('hide');
    });

    // this is for delete
    window.addEventListener('delete-show-form', event => {
        $('#delete-model').modal('show');
    });

    window.addEventListener('delete-hide-form', event => {
        $('#delete-model').modal('hide');
    });

    window.addEventListener('focus-on-quantity-field', event => {
        $("#quantity_field").focus();
    });
</script>