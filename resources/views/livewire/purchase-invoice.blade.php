@push('purchace-invoice', 'active')
<div>
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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-truck"></i>Purchase</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Purchase-Invoice</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-8 offset-md-4">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search by purchase invoice no." aria-label="" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
                    </div>

                    @if (in_array('Save', $page_action))
                    <button id="formOpen" wire:click="openModel" class="btn btn-tpos-primary ml-1"><i class="fa fa-plus"
                            aria-hidden="true"></i> Create-New
                    </button>
                    @endif

                </div>
            </div>
        </div>
    </div>

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
                <div class="form-group mb-3 mb-md-2">
                    <label for="select_company" class="filter-form-label">Filter by Company :</label>
                    <select class="custom-form-input1 form-control text-center" id="select_company"
                        wire:model="select_company">
                        <option value="0" selected>-- Select Company --</option>
                        @foreach ($filter_companies as $company)
                        <option class="text-capitalized" value="{{ $company->id }}">
                            {{ $company->company_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-3 mb-md-2">
                    <label for="select_dealer" class="filter-form-label">Filter by Dealer :</label>
                    <select class="custom-form-input1 form-control text-center" id="select_dealer"
                        wire:model="select_dealer">
                        @if ($select_company == 0)
                        <option value="0" selected>-- Please Select the Company --</option>
                        @else
                        <option value="" selected>-- Select Dealer --</option>
                        @endif

                        @foreach ($filter_dealers as $dealer)
                        <option class="text-capitalized" value="{{ $dealer->id }}">
                            {{ $dealer->name }}&nbsp;({{ $dealer->tp }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-3 mb-md-2">
                    <label for="select_date" class="filter-form-label">Filter by Invoice date :</label>
                    <input type="date" class="custom-form-input1 form-control text-center" id="select_date"
                        wire:model="select_date" max="{{ date('Y-m-d')}}">
                </div>
            </div>

            <div class="col-12">
                <div class="not-transferd-area custom mb-0 mt-2">
                    <div class="pretty p-switch p-fill mr-0">
                        <input type="checkbox" wire:model="not_transfered" />
                        <div class="state p-success">
                            <label class="{{$not_transfered == true ? " text-blue" : "text-d-blue" }}">
                                <b>Show not transfered invoices only.</b>
                            </label>
                        </div>
                    </div>
                    <div class="page-divide-right"></div>
                    <div class="pretty p-switch p-fill mr-0">
                        <input type="checkbox" wire:model="contain_return" />
                        <div class="state p-warning">
                            <label class="{{$contain_return == true ? " text-warning" : "text-d-blue" }}">
                                <b>Show only the bill containing the returned items.</b>
                            </label>
                        </div>
                    </div>
                    <div class="page-divide-right"></div>
                    <div class="pretty p-switch p-fill mr-0">
                        <input type="checkbox" wire:model="contain_credit" />
                        <div class="state p-info">
                            <label class="{{$contain_credit == true ? " text-info" : "text-d-blue" }}">
                                <b>Show only the bill containing the credited dealers.</b>
                            </label>
                        </div>
                    </div>
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

    @if (session()->has('transfer_message'))
    <div class="alert alert-success alert-transfer"
        style="width: 100%; margin:0 auto 15px auto; text-align:center; padding: 10px; border-radius:10px">
        {{ session('transfer_message') }}
    </div>

    <script>
        $(function() {
            setTimeout(function() {
                $('.alert-transfer').slideUp();
            }, 2000);
        });
    </script>
    @endif

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-secondary2 position-relative">

                <div wire:loading wire:target="transferStocks()" class="loading-area">
                    <img src="{{asset('./assets/img/loading.gif')}}" alt="">
                    <h6>Please wait... <b>Stocks are transferring.</b></h6>
                </div>

                <div class="table-top-area">
                    <h4 class="table-title">Purchase-Invoice(s)</h4>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item  {{($count>=10)?'':'disabled'}}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{$count." - ".$count+10}}</a></li>
                            <li class="page-item {{(sizeOf($list_data)==10)?'':'disabled'}} ">
                                <a class="page-link" wire:click='add' href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="card-body pt-2 pr-2 pl-2 pb-2">
                    <div class="note-for-transfer ">
                        <h6>Note : <span class="note--span">Invoices highlighted with <span
                                    class="highlight-color"></span> this color have been transfered!</span></h6>
                    </div>

                    <div class="sub-card">
                        <div class="table-responsive custom-border">
                            <table class="table table-bordered table-hover table-bg mb-0">
                                <thead>
                                    <tr class="custom-table-bg">
                                        <th class="text-d-blue">No.</th>
                                        <th class="text-d-blue">Company</th>
                                        <th class="text-d-blue">Dealer</th>
                                        <th class="text-d-blue">Invoice Number</th>
                                        <th class="text-d-blue text-right">Amount</th>
                                        <th class="text-d-blue text-center">Invoice Date</th>
                                        <th class="text-d-blue">Created</th>
                                        <th class="text-d-blue text-left">
                                            Action
                                            <div class="action-info-area">
                                                <i class="fa fa-info-circle show-info-icon"></i>
                                                <div class="action-info-container">
                                                    @if (in_array('Delete', $page_action))
                                                    <div class="action-info">
                                                        <i class="fa fa-trash text-danger mr-1" aria-hidden="true"></i>
                                                        Delete Invoice
                                                    </div>
                                                    @endif

                                                    @if (in_array('View', $page_action))
                                                    <div class="action-info">
                                                        <i class="fa fa-eye text-cyan mr-1" aria-hidden="true"></i>
                                                        View Invoice
                                                    </div>
                                                    @endif

                                                    @if (in_array('Update', $page_action))
                                                    <div class="action-info">
                                                        <i class="fa fa-pen text-info mr-1" aria-hidden="true"></i>
                                                        Edit Invoice
                                                    </div>
                                                    @endif

                                                    @if (in_array('Add-More', $page_action))
                                                    <div class="action-info">
                                                        <i class="fa fa-plus text-success mr-1" aria-hidden="true"></i>
                                                        Add Invoice Items
                                                    </div>
                                                    <div class="action-info">
                                                        <i class="fa fa-undo text-yellow mr-1" aria-hidden="true"></i>
                                                        Return Items
                                                    </div>
                                                    <div class="action-info">
                                                        <i class="fa fa-share text-warning mr-1" aria-hidden="true"></i>
                                                        Transfer Stocks
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr class="@if ($row->is_transfered == 1) bg-transferd @endif">
                                    <td>{{ $x }}.</td>
                                    <td class="text-capitalized">{{ $row->company_name }}</td>
                                    <td class="text-capitalized">{{ $row->dealer_name }}</td>
                                    <td>{{ $row->invoice_number }}</td>
                                    <td class="text-right">{{ number_format($row->amount, 2) }}</td>
                                    <td class="text-center">{{ $row->invoice_date }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}
                                        <br>
                                        <small class="text-secondary">
                                            Created {{ \Carbon\Carbon::parse($row->created_at)->diffForHumans() }}
                                        </small>
                                    </td>

                                    <td class="text-left whitespace-nowrap">
                                        @if (in_array('Delete', $page_action) && ($row->is_transfered == 0))
                                        <a href="##" class="text-danger mr-1" title="Delete Invoice"
                                            wire:click="deleteOpenModel({{ $row->id }})">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('View', $page_action))
                                        <a href="##" class="text-cyan mr-1" title="View Invoice"
                                            wire:click="openViewModel({{ $row->id }})">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action))
                                        <a href="##" class="text-info mr-1" title="Edit Invoice"
                                            wire:click="updateRecord({{ $row->id }})">
                                            <i class="fa fa-pen" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Add-More', $page_action))
                                        <a href="/invoice-items/{{ $row->id }}" target="_blank"
                                            title="Add Invoice Items" class="text-success mr-1">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Add-More', $page_action) && ($row->has_return == 1 ))
                                        <a href="/invoice-return/{{ $row->id }}/{{ $row->invoice_date }}"
                                            target="_blank" title="Return Items" class="text-yellow mr-1">
                                            <i class="fa fa-undo" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Add-More', $page_action) && ($row->is_transfered == 0))
                                        <button wire:click="openTransferConfirmModel({{ $row->id }})"
                                            title="Transfer Stocks" class="text-warning trans-btn">
                                            <i class="fa fa-share" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @php($x++)
                                @endforeach
                                @if(count($list_data) == 0)
                                <tr>
                                    <td colspan="8" class="text-center text-secondary">
                                        No records found...!
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="records-count-area" style="border-radius: 6px; margin-top:5px">
                        <div>
                            <small class="total-records">Total No.of Records :&nbsp;{{ $data_count }}</small>
                        </div>
                        @if ($select_company || $select_dealer)
                        <div class="mt-1">
                            <small class="total-records">Records Count for the Selection:&nbsp;{{
                                $filter_data_count
                                }}</small>
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
                    <h5 class="modal-title text-light-green" id="formModal">
                        {{ $key != 0 ? 'Update Data' : 'Create Data' }}
                    </h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    <div class="row">
                        {{-- Company --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Company <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_company"
                                    wire:keydown.enter.prevent="saveData">
                                    <option value="">-- Select Company-- </option>
                                    @foreach ($companies as $company)
                                    @if ($new_company == $company->id)
                                    <option class="text-capitalized" value="{{ $company->id }}" selected>
                                        {{ $company->company_name }}
                                    </option>
                                    @else
                                    <option class="text-capitalized" value="{{ $company->id }}">{{
                                        $company->company_name }}</option>
                                    @endif

                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_company')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Dealers --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Dealer <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_dealer"
                                    wire:keydown.enter.prevent="saveData">
                                    <option value="">-- Select Dealer-- </option>
                                    @foreach ($dealers as $dealer)
                                    @if ($new_dealer == $dealer->id)
                                    <option class="text-capitalized" value="{{ $dealer->id }}" selected>
                                        {{ $dealer->name ."-".$dealer->tp }}
                                    </option>
                                    @else
                                    <option class="text-capitalized" value="{{ $dealer->id }}"> {{ $dealer->name
                                        ."-".$dealer->tp }}</option>
                                    @endif

                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_dealer')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- is credit --}}
                        <div class="col-12 mb-3">
                            <div class="cng-bcode-area p-3">
                                <div class="pretty p-default">
                                    <input type="checkbox" wire:model="is_credit" />
                                    <div class="state p-success">
                                        <label class="text-d-blue"><b>Is Credit</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Invoice number --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Invoice Number <font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Invoice Number"
                                    wire:model="new_invoice_number" wire:keydown.enter.prevent="saveData">

                                @if ($showError)
                                @error('new_invoice_number')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Amount <font color="red">*</font></label>
                                <input type="number" min="0" class="form-control custom-form-input1"
                                    placeholder="Amount" wire:model="new_amount" wire:keydown.enter.prevent="saveData">

                                @if ($showError)
                                @error('new_amount')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Discount --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Discount</label>
                                <input type="number" min="0" class="form-control custom-form-input1"
                                    placeholder="Discount" wire:model="new_discount"
                                    wire:keydown.enter.prevent="saveData">

                                @if ($showError)
                                @error('new_discount')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- VAT --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">VAT</label>
                                <input type="number" class="form-control custom-form-input1" placeholder="VAT" min="0"
                                    wire:model="new_vat" wire:keydown.enter.prevent="saveData">

                                @if ($showError)
                                @error('new_vat')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Date <font color="red">*</font></label>
                                <input type="date" class="form-control custom-form-input1" wire:model="new_date"
                                    wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_date')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-12 col-md-12 col-lg-12 ">
                            <div class="form-group">
                                <label class="text-blue">Description</label>
                                <textarea type="text" class="form-control custom-form-input1" placeholder="Description"
                                    wire:model="new_description" wire:keydown.enter.prevent="saveData">
                                    </textarea>
                                @if ($showError)
                                @error('new_description')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- has return --}}
                        <div class="col-12 mb-3">
                            <div class="cng-bcode-area p-3">
                                <div class="pretty p-default">
                                    <input type="checkbox" wire:model="has_return" />
                                    <div class="state p-success">
                                        <label class="text-d-blue"><b>Has Return</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Return Amount --}}
                        <div class="col-12 col-md-6 barcode-show-area {{$has_return == true ? " barcode-shown" : "" }}">
                            <div class="form-group">
                                <label class="text-blue">Return Amont</label>
                                <input type="number" min="0" class="form-control custom-form-input1"
                                    placeholder="Return Amount" wire:model="new_return_amount"
                                    wire:keydown.enter.prevent="saveData">

                                @if ($showError)
                                @error('new_return_amount')
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
                        <button type="button" wire:click="closeModel"
                            class="btn btn-danger m-t-15 waves-effect">Close</button>
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

    {{-- view model here --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg" id="view-model" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card-tpos-primary2">
                <div class="modal-header">
                    <h5 class="modal-title text-light-green" id="formModal">Invoice Details</h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row gutters-sm">
                        @if (sizeOf($view) != 0)
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card card-tpos-secondary2 mb-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Company :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->company_name) ? $view[0]->company_name :
                                                "---------------" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Dealer :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->dealer_name) ? $view[0]->dealer_name :
                                                "---------------" }}
                                            </span>
                                            @if ($is_credit == true)
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->is_credit) ? "(In Credit)" :
                                                "" }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Invoice No. :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->invoice_number) ? $view[0]->invoice_number :
                                                "---------------" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Invoice Date :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->invoice_date) ? $view[0]->invoice_date :
                                                "---------------" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Amount :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->amount) ? number_format($view[0]->amount, 2) :
                                                "0.00" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Discount :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->discount) ? number_format($view[0]->discount, 2) :
                                                "0.00" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">VAT :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->vat) ? number_format($view[0]->vat, 2) :
                                                "0.00" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Net Amount :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->net_amount) ? number_format($view[0]->net_amount, 2)
                                                :
                                                "0.00" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    @if ($view[0]->has_return == 1)
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Return Amount :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->return_amount) ?
                                                number_format($view[0]->return_amount, 2) :
                                                "0.00" }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Description :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="text-blue font-weight-bold">
                                                {{ !empty($view[0]->description) ? $view[0]->description :
                                                "---------------" }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="text-center">
                        <button type="button" style="width: 30%" wire:click="viewCloseModel"
                            class="btn btn-danger waves-effect">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

    {{-- transfer confirm model here --}}
    <div wire:ignore.self class="modal fade" id="transfer-confirm-model" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-tpos-warning3 custom">
                <div class="modal-header return-model-header">
                    <h5 class="modal-title text-warning-custom" id="formModal">Transfer Confirmation</h5>
                    <a href="##" type="button" class="close custom-close" wire:click="closeTransferConfirmModel">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body return-model-body">
                    <p class="text-center">
                        <b>Are you sure?</b> Do you want to send items for <b>transfer</b> from this invoice?
                    </p>

                    <div class="text-right">
                        <button type="button" wire:click="closeTransferConfirmModel"
                            class="btn btn-danger m-t-15 waves-effect">No</button>
                        <button type="button" wire:click="transferStocks"
                            class="btn btn-success m-t-15 waves-effect">Yes</button>
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

        // this is for view
        window.addEventListener('view-show-form', event => {
            $('#view-model').modal('show');
        });
        window.addEventListener('view-hide-form', event => {
            $('#view-model').modal('hide');
        });

        // this is for transfer confirm
        window.addEventListener('transfer-confirm-show', event => {
            $('#transfer-confirm-model').modal('show');
        });

        window.addEventListener('transfer-confirm-hide', event => {
            $('#transfer-confirm-model').modal('hide');
        });
    </script>
</div>