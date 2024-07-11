@push('counter', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fa fa-home"></i>Property</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Counter</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-6">
            <div class="form-group">
                <a href="/counters-activity-log" class="btn counter-log-btn"><i class="fa fa-history mr-2"
                        aria-hidden="true"></i>Counters Activity Log</a>
                <a href="/counter-cashout-history" class="btn counter-log-btn mt-3 mt-sm-0"><i class="fas fa-cash-register mr-2"
                        aria-hidden="true"></i>Counters Cash Out History</a>
            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <input autofocus type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search Here..." aria-label="">
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

    @if (session()->has('status_message'))
    <div class="alert alert-transfer"
        style="width: 100%; margin:0 auto 15px auto; text-align:center; padding: 10px; border-radius:10px">
        {{ session('status_message') }}
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
            <div class="card card-tpos-secondary2">
                <div class="table-top-area">
                    <h4 class="table-title">Counter</h4>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item  {{($count>=10)?'':'disabled'}}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{$count." - ".$count+10}}</a></li>
                            <li class="page-item {{(sizeOf($counter_data)==10)?'':'disabled'}} ">
                                <a class="page-link" wire:click='add' href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <style>
                    .count-status-button-container {
                        display: flex;
                        gap: 0;
                        border-radius: 50px;
                        padding: 5px;
                        background-color: #FFF;
                        margin: 5px 0;
                        max-width: max-content;
                        align-self: center;
                        box-shadow: 1px 3px 10px 0 rgb(32 13 0 / 10%);
                    }

                    .count-status-button {
                        font-weight: 700 !important;
                        font-size: 14px !important;
                        padding: 4px 12px !important;
                        border-radius: 50px !important;
                        background-color: transparent !important;
                    }

                    .count-status-button:disabled {
                        opacity: 1 !important;
                    }

                    .count-status-button.left {
                        background-color: #48d45f !important;
                        box-shadow: inset 1px 3px 10px 0 rgb(32 13 0 / 20%);
                        color: #FFF !important;
                    }

                    .count-status-button.left.is-active {
                        color: #48d45f !important;
                        background-color: transparent !important;
                        box-shadow: none;
                        opacity: 1;
                    }

                    .count-status-button.right {
                        color: #FFF !important;
                        background-color: #f73329 !important;
                        box-shadow: inset 1px 3px 10px 0 rgb(32 13 0 / 20%);
                    }

                    .count-status-button.right.is-active {
                        color: #f73329 !important;
                        background-color: transparent !important;
                        box-shadow: none;
                        opacity: 1;
                    }
                </style>

                <div class="card-body p-2">
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-d-blue">No.</th>
                                        <th class="text-d-blue">Counter No.</th>
                                        <th class="text-d-blue text-center">Is Open?</th>
                                        <th class="text-d-blue text-center">Is Active?</th>
                                        <th class="text-d-blue text-center">Actions</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($counter_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-capitalized">{{ $row->counter }}</td>
                                    <td class="text-center">
                                        <div class='d-flex justify-content-center'>
                                            <div class="count-status-button-container">
                                                <button @disabled($row->open_status == 1) class="btn {{$row->open_status
                                                    == 0 ? " is-active" : "" }}
                                                    btn-sm count-status-button left"
                                                    wire:click="updateCounterOpenStatus({{ $row->id }}, '1')">
                                                    {{$row->open_status == 0 ? "Open" : "Opened"}}
                                                </button>
                                                <button @disabled($row->open_status == 0) class="btn {{$row->open_status
                                                    == 1 ? " is-active" : "" }}
                                                    btn-sm count-status-button right"
                                                    wire:click="updateCounterOpenStatus({{ $row->id }}, '0')">
                                                    {{$row->open_status == 1 ? "Close" : "Closed"}}
                                                </button>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class='d-flex justify-content-center'>
                                            <div class="count-status-button-container">
                                                <button @disabled($row->active_status == 1) class="btn
                                                    {{$row->active_status == 0 ? " is-active" : "" }}
                                                    btn-sm count-status-button left"
                                                    wire:click="updateCounterActiveStatus({{ $row->id }}, '1')">
                                                    {{$row->active_status == 1 ? "Active" : "Activate"}}
                                                </button>
                                                <button @disabled($row->active_status == 0) class="btn
                                                    {{$row->active_status == 1 ? " is-active" : "" }}
                                                    btn-sm count-status-button right"
                                                    wire:click="updateCounterActiveStatus({{ $row->id }}, '0')">
                                                    {{$row->active_status == 0 ? "Inactive" : "Deactivate"}}
                                                </button>
                                            </div>
                                        </div>
                                    </td>

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
                                @if(count($counter_data) == 0)
                                <tr>
                                    <td colspan="5" class="text-center text-secondary">
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
                    {{-- Category --}}
                    <div class="form-group">
                        <label class="text-blue">Counter No. <font color="red">*</font></label>
                        <input type="text" class="form-control custom-form-input1" wire:model="new_counter"
                            placeholder="Counter No." wire:keydown.enter.prevent="saveData">
                        @if ($showError)
                        @error('new_counter')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                        @endif
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