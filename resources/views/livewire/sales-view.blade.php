@push('sales-view', 'active')
<div>
    @if ($counter_is_opened == false)
    <div>
        <div>
            <div class="row">
                @if ($counter_balance && $counter_balance != 0)
                <div class="col-12">
                    <div class="card open-count-card mb-3">
                        <h6 class="text-white mb-0">Counter Cash Balance :
                            <span class="text-light-green">
                                {{number_format($counter_balance, 2)}}
                            </span>
                        </h6>
                        <button class="btn closing-btn" wire:click="openCashoutModel">
                            Counter Cash Out
                        </button>
                    </div>
                </div>
                @endif

                <div class="col-12 col-md-12">
                    <div class="card card-tpos-secondary2 mb-3">
                        <div class="table-top-area">
                            <h4 class="table-title">{{$counter_want_to_close == false ? "Counter Opening" : "Counter
                                Closing"}} </h4>
                            @if ($cash_5000_Qty != 0 ||
                            $cash_1000_Qty != 0 ||
                            $cash_500_Qty != 0 ||
                            $cash_100_Qty != 0 ||
                            $cash_50_Qty != 0 ||
                            $cash_20_Qty != 0 ||
                            $cash_10_Qty != 0 ||
                            $cash_5_Qty != 0 ||
                            $cash_2_Qty != 0 ||
                            $cash_1_Qty != 0)
                            <div class="text-right">
                                <button class="btn btn-sm clear-btn only-border" title="Clear" wire:click="clearCash">
                                    <i class="fa fa-times align-middle"></i>
                                    Clear
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="card-body p-2">
                            <div class="sub-card">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-bg mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-d-blue">No.</th>
                                                <th class="text-d-blue">Cash</th>
                                                <th class="text-d-blue text-center custom-width-area">Quantity</th>
                                                <th class="text-d-blue text-right custom-width-area">Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>01.</td>
                                                <td>5000/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_5000_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_5000_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_5000_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>02.</td>
                                                <td>1000/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_1000_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_1000_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_1000_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>03.</td>
                                                <td>500/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_500_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_500_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_500_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>04.</td>
                                                <td>100/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_100_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_100_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_100_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>05.</td>
                                                <td>50/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_50_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_50_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_50_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>06.</td>
                                                <td>20/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_20_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_20_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_20_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>07.</td>
                                                <td>10/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_10_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_10_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_10_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>08.</td>
                                                <td>5/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_5_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_5_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_5_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>09.</td>
                                                <td>2/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_2_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_2_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_2_Value, 2)}}</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>10.</td>
                                                <td>1/-</td>
                                                <td class="text-center pt-1 pb-1">
                                                    <input type="number"
                                                        class="form-control custom-form-input1 text-center"
                                                        placeholder="Quantity" wire:model="cash_1_Qty"
                                                        @disabled($open_immediatly==true)>
                                                    @error('cash_1_Qty')
                                                    <small class="text-danger text-center text-sm text-nowrap">
                                                        {{$message}}
                                                    </small>
                                                    @enderror
                                                </td>
                                                <td class="text-right">
                                                    <b>{{number_format($cash_1_Value, 2)}}</b>
                                                </td>
                                            </tr>
                                        </tbody>

                                        <tfoot>
                                            <tr style="background-color: #cbe3e9 !important;
                                        border-top: 2px solid #FFF !important;">
                                                <td colspan="3"><b>Total Amount :</b></td>
                                                <td>
                                                    <div class="total-text text-success">
                                                        {{ number_format($total_cash_count_amount, 2) }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="card open-count-card">
                        <div class="custom-input-flex">
                            <div>
                                <h6 class="text-white mb-0">
                                    {{$counter_want_to_close == false ? "Open Counter" : "Close Counter"}}
                                </h6>

                                @if ($show_open_immediatly == true && $counter_want_to_close == false)
                                <div class="pretty p-default p-round mt-2">
                                    <input type="checkbox" wire:model="open_immediatly" wire:change="openImmediately" />
                                    <div class="state p-success-o">
                                        <label class="{{$open_immediatly == true ? " text-white" : "text-secondary" }}">
                                            <b>Open immediately</b>
                                        </label>
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if ($show_open_immediatly == true && $counter_want_to_close == false)
                            <div class="form-group mb-0 transition-all 
                                {{ $open_immediatly == true ? " d-block" : "d-none" }}">
                                <input type="number" id="amount_field"
                                    class="form-control custom-form-input1 with-dark-shadow"
                                    wire:model="immediate_open_amount" wire:keydown.enter.prevent="openCounter"
                                    placeholder="Enter opening amount">
                                @error('immediate_open_amount')
                                <span class="text-danger open-amt-error-msg">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <div>
                            @if ($counter_want_to_close == true)
                            <button class="btn closing-btn" wire:click="cancelCounterClosing">
                                Cancel
                            </button>

                            <button class="btn open-btn" wire:click="closeCounter" id="closeCounter"
                                @disabled($total_cash_count_amount==0)>
                                Close Counter
                            </button>
                            @else
                            <button class="btn open-btn" wire:click="openCounter" @disabled($total_cash_count_amount==0
                                && (int)$immediate_open_amount==0)>
                                Open Counter
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Insert model here --}}
            <div wire:ignore.self class="modal fade" id="insert-model" tabindex="-1" role="dialog"
                aria-labelledby="formModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content card-tpos-primary2">
                        <div class="modal-header">
                            <h5 class="modal-title text-light-green" id="formModal">
                                Counter Cash Out
                            </h5>
                            <a href="##" type="button" class="close custom-close" wire:click="closeCashoutModel">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </div>
                        <div class="modal-body">

                            <div class="counter-balance-area">
                                <h6 class="mb-0 text-blue">Counter Cash Balance :</h6>
                                <h6 class="mb-0 text-d-blue"> {{number_format($counter_balance, 2)}}</h6>
                            </div>

                            {{-- Cashout amount --}}
                            <div class="form-group mb-0">
                                <label class="text-blue">Cash Out Amount <font color="red">*&nbsp;(The maximum cash out
                                        amount is {{number_format($counter_balance - 3000)}})</font></label>
                                <input type="number" class="form-control custom-form-input1"
                                    wire:model="counter_cashout_amount" placeholder="Enter Cash Out Amount"
                                    wire:keydown.enter.prevent="counterCashOut">
                                @if ($showError)
                                @error('counter_cashout_amount')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>

                            <div class="text-right">
                                <button type="button" wire:click="counterCashOut"
                                    class="btn btn-success m-t-15 waves-effect">Cash Out</button>
                            </div>

                            @if (session()->has('message'))
                            <div class="alert alert-success text-center mt-3">
                                {{ session('message') }}
                            </div>
                            @endif

                            @if (session()->has('error_message'))
                            <div class="alert alert-red text-center mt-3">
                                {{ session('error_message') }}
                            </div>
                            @endif

                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="cashout-table-area">
                                        <div class="table-top-area">
                                            <h6 class="table-title">Cash Out History</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-d-blue">No.</th>
                                                        <th class="text-d-blue">Amount</th>
                                                        <th class="text-d-blue">Date & Time</th>
                                                        <th class="text-d-blue">Cash Out by</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php($y = 1)
                                                    @foreach ($counter_cash_out_histories as $rowx)
                                                    <tr>
                                                        <td>{{ $y }}.</td>
                                                        <td>{{ number_format($rowx->amount) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($rowx->created_at)->format('Y-m-d
                                                            h:i A') }}</td>
                                                        <td>{{ $rowx->user_name }}</td>
                                                    </tr>
                                                    @php($y++)
                                                    @endforeach
                                                    @if(count($counter_cash_out_histories) == 0)
                                                    <tr>
                                                        <td colspan="4" class="text-center text-secondary">
                                                            No records found...!
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-top-area justify-content-end p-2">
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination justify-content-end">
                                                    <li class="page-item  {{($d_count>=10)?'':'disabled'}}">
                                                        <a class="page-link" wire:click='prev' tabindex="-1">Prev</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">
                                                            {{$d_count." - ".$d_count+10}}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="page-item {{(sizeOf($counter_cash_out_histories)==10)?'':'disabled'}} ">
                                                        <a class="page-link" wire:click='next' href="#">Next</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-0">

                            <div class="text-right">
                                <button type="button" wire:click="closeCashoutModel"
                                    class="btn btn-danger m-t-15 waves-effect">Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- model end --}}

        </div>

        <script>
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Delete') {
                    Livewire.emit('clearCash');
                }
            });

            window.addEventListener('focus-on-amount-field', event => {
                $("#amount_field").focus();
            });
        </script>
    </div>
    @else
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-blue border-b-blue">
                <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Sales</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-6 text-left">
                <div class="form-group mb-3">
                    @if (in_array('Save', $page_action))
                    <button wire:click="newBill" class="btn btn-lg new-bill-btn"><i class="fa fa-plus"
                            aria-hidden="true"></i>&nbsp;New Bill
                    </button>
                    <span class="ml-2 new-bill-sugg"><i class="fa fa-info-circle mr-2"></i>Press <b>F9</b> for new
                        bill</span>
                    @endif
                </div>
            </div>
            <div class="col-6 text-right">
                <div class="form-group mb-3">
                    @if ($counter_is_opened == true)
                    <button wire:click="showCloseCounter" class="btn btn-lg close-counter-btn"><i class="fa fa-times"
                            aria-hidden="true"></i>&nbsp;&nbsp;Close Counter
                    </button>
                    @endif
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
                        <h4 class="table-title">Pending Bills</h4>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                <li class="page-item {{($count>=10)?'':'disabled'}}">
                                    <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">{{$count." - ".$count+10}}</a></li>
                                <li class="page-item {{(sizeOf($temp_bill_data)==10)?'':'disabled'}} ">
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
                                            <th class="text-d-blue">No. of Items</th>
                                            <th class="text-d-blue">Total</th>
                                            <th class="text-d-blue text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    @php($x = 1)
                                    @foreach ($temp_bill_data as $row)
                                    <tr>
                                        <td>{{ $x }}.</td>
                                        <td>{{ $row->item_count }}</td>
                                        <td class="text-capitalized">{{ number_format($row->total_amount, 2) }}</td>

                                        <td class="text-center whitespace-nowrap">
                                            @if (in_array('Delete', $page_action))
                                            <a href="##" title="Delete Pending Bill"
                                                class="text-white mr-1 btn btn-danger btn-sm pt-1 pb-1 pr-2 pl-2"
                                                wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                    aria-hidden="true"></i>&nbsp; Delete
                                            </a>
                                            @endif

                                            @if (in_array('Update', $page_action))
                                            <a href="/sale/{{ $row->id }}" title="Continue Bill"
                                                class="text-white mr-1 btn btn-success btn-sm pt-1 pb-1 pr-2 pl-2">
                                                <i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Continue
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @php($x++)
                                    @endforeach
                                    @if(count($temp_bill_data) == 0)
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary">
                                            No pending bill(s)...!
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                                <div class="records-count-area">
                                    <small class="total-records">Total No.of Pending Bills :&nbsp;{{ $data_count
                                        }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- delete model here --}}
        <div wire:ignore.self class="modal fade" id="delete-model" tabindex="-1" role="dialog"
            aria-labelledby="formModal" aria-hidden="true">
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
                            If you want to remove this pending bill, <b>you can't undone</b>, It will be affected that
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
            document.addEventListener('keydown', function(event) {
                if (event.key === 'F9') {
                    Livewire.emit('newBill');
                }
            });
        </script>
    </div>
    @endif

    <div wire:ignore.self class="modal fade" id="counter-close" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content card-tpos-info">
                <div class="modal-body info-model-body">
                    <div>
                        <h1 class="text-center text-info"><i class="fa fa-info-circle mb-3"></i></h1>
                        <h3 class="text-center text-info">Counter Closed!</h3>
                        <p class="text-center pt-2">The {{$counter_id != null ? $counter_name : null}} has been
                            successfully closed.</p>
                    </div>

                    <div class="text-center">
                        <button type="button" wire:click="hideCounterCloseAlert"
                            class="btn btn-info m-t-15 waves-effect">
                            OK
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // this is for insert
        window.addEventListener('insert-show-form', event => {
            $('#insert-model').modal('show');
        });
        window.addEventListener('insert-hide-form', event => {
            $('#insert-model').modal('hide');
        });

        // this is for counter closed alert
        window.addEventListener('counter-close-alert-show', event => {
            $('#counter-close').modal('show');
        });
    
        window.addEventListener('counter-close-alert-hide', event => {
            $('#counter-close').modal('hide');
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