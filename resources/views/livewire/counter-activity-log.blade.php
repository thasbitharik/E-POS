@push('counter', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fa fa-home"></i>Property</li>
            <li class="breadcrumb-item"><a href="/counter"><i class="fas fa-cash-register"></i>Counter</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Counters Activity Log</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-secondary2">
                <div class="table-top-area">
                    <h4 class="table-title">Counters Activity Log</h4>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item  {{($count>=10)?'':'disabled'}}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{$count." - ".$count+10}}</a></li>
                            <li class="page-item {{(sizeOf($counter_activity_data)==10)?'':'disabled'}} ">
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
                                        <th class="text-d-blue">Activity</th>
                                        @if (in_array('Delete', $page_action))
                                        <th class="text-d-blue text-center">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($counter_activity_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td>
                                        @if ($row->activity == "opened")
                                        {!! "The <b>" . $row->counter_name . "</b> is <span class='text-green'> <b>" .
                                                $row->activity . "</b></span> with <b>" . number_format($row->amount) .
                                            "/-</b> by <b>" .
                                            $row->users_name . "</b> on " .
                                        \Carbon\Carbon::parse($row->created_at)->format('F
                                        jS Y h:i A') !!}
                                        @else
                                        {!! "The <b>" . $row->counter_name . "</b> is <span class='text-danger'> <b>" .
                                                $row->activity . "</b></span> with <b>" . number_format($row->amount) .
                                            "/-</b> by <b>" .
                                            $row->users_name . "</b> on " .
                                        \Carbon\Carbon::parse($row->created_at)->format('F
                                        jS Y h:i A') !!}
                                        @endif
                                    </td>

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
                                @if(count($counter_activity_data) == 0)
                                <tr>
                                    <td colspan="{{ in_array('Delete', $page_action) ? " 3" : "2" }}"
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