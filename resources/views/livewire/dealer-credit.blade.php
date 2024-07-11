@push('dealer-credit', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-truck"></i>Purchase</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Dealer Credit</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <select class="custom-form-input2 form-control text-center" wire:model="select_company">
                    <option value="0" selected>-- Filter by Company --</option>
                    @foreach ($filter_companies as $company)
                    <option class="text-capitalized" value="{{ $company->id }}">
                        {{ $company->company_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="form-group">
                <div class="input-group">
                    <input autofocus type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search by Invoice No or Dealer Name..." aria-label="">
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
                    </div>

                    @if (in_array('Save', $page_action))
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
                    <h4 class="table-title">Dealer Credit</h4>
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
                <div class="card-body p-2">
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-d-blue">No.</th>
                                        <th class="text-d-blue">Dealer</th>
                                        <th class="text-d-blue">Purchase Invoice</th>
                                        <th class="text-d-blue">Invoice Date</th>
                                        <th class="text-d-blue">Credit Amount</th>
                                        <th class="text-d-blue">Paid Amount</th>
                                        <th class="text-d-blue">Payable Amount</th>
                                        <th class="text-d-blue">Paid Date</th>
                                        <th class="text-d-blue text-center">Actions</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-capitalized">{{ $row->name }}</td>
                                    <td class="text-capitalized">{{ $row->invoice_number }}</td>
                                    <td class="text-capitalized">{{ $row->invoice_date }}</td>
                                    <td class="text-capitalized">{{ number_format($row->credit_amount,2) }}</td>
                                    <td class="text-capitalized">{{ number_format($row->paid_amount,2) }}</td>
                                    <td class="text-capitalized">{{ number_format(($row->credit_amount) -
                                        ($row->paid_amount),2) }}</td>
                                    @if ( $row->paid_date)
                                    <td class="text-capitalized">{{ $row->paid_date }}</td>
                                    @else
                                    <td class="text-capitalized">No Date</td>
                                    @endif

                                    <td class="text-center whitespace-nowrap">
                                        @if (in_array('Delete', $page_action))
                                        <a href="##" class="text-danger mr-1"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action))
                                        <a href="##" class="text-info"
                                            wire:click="updateRecord({{ $row->id }})"><b>Pay</b>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @php($x++)
                                @endforeach
                                @if(count($list_data) == 0)
                                <tr>
                                    <td colspan="9" class="text-center text-secondary">
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
                        {{ $key != 0 ? 'Update Data' : 'Create Data' }}
                    </h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    @if ($selected_id)
                    <div>
                        <h6 class="text-blue">Company Name:
                            <span class="text-d-blue">
                                {{ $dealer_credit_details[0]->company_name }}
                            </span>
                        </h6>
                        <h6 class="text-blue">Dealer Name:
                            <span class="text-d-blue">
                                {{ $dealer_credit_details[0]->dealer_name }}
                            </span>
                        </h6>
                        <h6 class="text-blue">Credit Amount:
                            <span class="text-d-blue">
                                {{ number_format($dealer_credit_details[0]->credit_amount,2) }}
                            </span>
                        </h6>
                        <h6 class="text-blue">Payable Amount:
                            <span class="text-d-blue">
                                {{ number_format(($dealer_credit_details[0]->credit_amount -
                                $dealer_credit_details[0]->paid_amount),2) }}
                            </span>
                        </h6>
                    </div>
                    @endif

                    <hr>

                    <div class="form-group">
                        <label class="text-blue">Paid Amount <font color="red">*</font></label>
                        <input type="text" class="form-control custom-form-input1" wire:model="new_paid_amount"
                            placeholder="Paid Amount" wire:keydown.enter.prevent="updateData">
                        @if ($showError)
                        @error('new_paid_amount')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="text-blue">Paid Date <font color="red">*</font></label>
                        <input type="date" class="form-control custom-form-input1" wire:model="new_paid_date"
                            wire:keydown.enter.prevent="updateData">
                        @if ($showError)
                        @error('new_paid_date')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                        @endif
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
                    <button type="button" wire:click="updateData"
                        class="btn btn-success m-t-15 waves-effect">Save</button>
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