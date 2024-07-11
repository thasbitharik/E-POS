@push('counter', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fa fa-home"></i>Property</li>
            <li class="breadcrumb-item"><a href="/counter"><i class="fas fa-cash-register"></i>Counter</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Counter Cash Out Histories
            </li>
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
                <div class="form-group mb-4 mb-md-0">
                    <label for="select_counter" class="filter-form-label">Filter by Counter :</label>
                    <select class="custom-form-input1 form-control text-center" id="select_counter"
                        wire:model="select_counter">
                        <option value="0">-- Select Counter --</option>
                        @foreach ($counter_data as $counter)
                        <option value="{{ $counter->id }}">
                            {{ $counter->counter_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-4 mb-md-0">
                    <label for="select_cashier" class="filter-form-label">Filter by Cashier :</label>
                    <select class="custom-form-input1 form-control text-center" id="select_cashier"
                        wire:model="select_cashier">
                        <option value="0">-- Select Cashier --</option>
                        @foreach ($cashier_data as $cashier)
                        <option class="text-capitalized" value="{{ $cashier->id }}">
                            {{ $cashier->username }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-0">
                    <label for="select_date" class="filter-form-label">Filter by Date :</label>
                    <input type="date" class="custom-form-input1 form-control text-center" id="select_date"
                        wire:model="select_date" max="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-secondary2">
                <div class="table-top-area">
                    <h4 class="table-title">Counter Cash Out Histories</h4>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item  {{($count>=10)?'':'disabled'}}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{$count." - ".$count+10}}</a></li>
                            <li class="page-item {{(sizeOf($counter_cashout_data)==10)?'':'disabled'}} ">
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
                                        <th class="text-d-blue text-right">Amount</th>
                                        <th class="text-d-blue">Date & Time</th>
                                        <th class="text-d-blue">Counter</th>
                                        <th class="text-d-blue">Cash Out by</th>
                                        @if (in_array('Delete', $page_action))
                                        <th class="text-d-blue text-center">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($counter_cashout_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-right">{{ number_format($row->amount, 2) }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}
                                        <span class="text-info">&nbsp;|&nbsp;</span>
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('h:i A') }}
                                    </td>
                                    <td>{{ $row->counter_name }}</td>
                                    <td>{{ $row->users_name }}</td>

                                    @if (in_array('Delete', $page_action))
                                    <td class="text-center whitespace-nowrap">
                                        <a href="##" class="text-danger mr-1"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @php($x++)
                                @endforeach
                                @if(count($counter_cashout_data) == 0)
                                <tr>
                                    <td colspan="{{ in_array('Delete', $page_action) ? " 6" : "5" }}"
                                        class="text-center text-secondary">
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
        // this is for delete
        window.addEventListener('delete-show-form', event => {
            $('#delete-model').modal('show');
        });

        window.addEventListener('delete-hide-form', event => {
            $('#delete-model').modal('hide');
        });
    </script>
</div>