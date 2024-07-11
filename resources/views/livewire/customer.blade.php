@push('customer', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="far fa-user"></i>Customers</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Customer</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-8 offset-md-4">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search Here..." aria-label="" autofocus>
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
                    <h4 class="table-title">Customers</h4>
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
                                        <th class="text-d-blue">Name</th>
                                        <th class="text-d-blue">Contact</th>
                                        <th class="text-d-blue">Loyality</th>
                                        <th class="text-d-blue text-right">Credit Limit</th>
                                        <th class="text-d-blue text-right">Total Credit</th>
                                        <th class="text-d-blue text-right">Credit Available</th>
                                        <th class="text-d-blue text-right">Credit Paid</th>
                                        <th class="text-d-blue text-right">Payable Credit</th>
                                        <th class="text-d-blue text-center">Action</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-capitalized">{{ $row->customer_name }}</td>
                                    <td>{{ $row->tp }}</td>
                                    <td>{{ $row->loyality }}</td>
                                    <td class="text-right">{{ number_format($row->credit_limit, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->cridit, 2) }}</td>
                                    <td class="text-right">
                                        {{ number_format($row->credit_limit - ($row->cridit - $row->received_credit), 2)
                                        }}
                                    </td>
                                    <td class="text-right">{{ number_format($row->received_credit, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->cridit - $row->received_credit, 2) }}
                                    </td>

                                    <td class="text-center whitespace-nowrap">
                                        @if (in_array('Delete', $page_action))
                                        <a href="#" class="text-danger m-2"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action))
                                        <a href="#" class="text-info" wire:click="updateRecord({{ $row->id }})"><i
                                                class="fa fa-pen" aria-hidden="true"></i>
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
                        {{ $key != 0 ? 'Update Data' : 'Create Data' }}
                    </h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label class="text-blue">Customer Name <font color="red">*</font></label>
                        <input type="text" class="form-control custom-form-input1" placeholder="Customer Name"
                            wire:model="new_name" wire:keydown.enter.prevent="saveData">
                        @if ($showError)
                        @error('new_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="text-blue">Contact Number <font color="red">*</font></label>
                        <input type="text" class="form-control custom-form-input1" placeholder="Contact Number"
                            wire:model="new_contact" wire:keydown.enter.prevent="saveData">
                        @if ($showError)
                        @error('new_contact')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>


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

                    <div class="mb-2">
                        <div class="cng-bcode-area p-3">
                            <div class="pretty p-default">
                                <input type="checkbox" wire:model="new_credit_details" />
                                <div class="state p-success">
                                    <label class="text-d-blue"><b>Is credit customer</b></label>
                                </div>
                            </div>
                        </div>
                    </div>



                    @if ($new_credit_details == 1)
                    <div class="collapse_credit">
                        <div class="mt-4">

                            <style>
                                .banner-box {
                                    background: linear-gradient(to bottom, #ffffff, #f0f0f0);
                                    box-shadow: 0 2px 4px #0000001a;
                                    border: 1px solid #66d575;
                                    border-radius: 10px;
                                    padding: 20px;
                                    margin-bottom: 20px;
                                }
                            </style>

                            @if (($key != 0) && ($show_credit_detail != 0))
                            <div class="row">
                                <div class="form-group col-6 text-danger text-center banner-box">
                                    <h6 class="text-danger">Payable Credit</h6>
                                    <h6><b>{{ $payable_credit ? $payable_credit : 0 }}</b></h6>
                                </div>

                                <div class="form-group col-6 text-center text-blue banner-box">
                                    <h6 class="text-blue">Credit Paid</h6>
                                    <h6><b>{{ $paid_credit ? $paid_credit : 0 }}</b></h6>
                                </div>
                            </div>
                            @endif

                            <div class="form-group">
                                <label class="text-blue">Credit Limit <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1" placeholder="Credit Limit"
                                    wire:model="new_credit_limit" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_credit_limit')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="text-blue">Received Credit </label>
                                <input type="number" class="form-control custom-form-input1"
                                    placeholder="Received Credit" wire:model="new_received_credit"
                                    wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_received_credit')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif



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
