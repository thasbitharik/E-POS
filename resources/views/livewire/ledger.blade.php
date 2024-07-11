@push('ledger', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-dollar-sign"></i>Account</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Ledger</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 col-lg-3">
            <div class="card card-tpos-primary2 p-3">
                <div class="pb-3 text-center">
                    <h4 class="text-d-blue report-title">Select</h4>
                </div>
                <div class="sub-card p-3">
                    <div class="row">
                        @if ($propertyId == 1)
                        <div class="col-12 col-lg-12 col-md-4">
                            <div class="form-group">
                                <label class="text-blue">Property</label>
                                <select class="form-control custom-form-input1" wire:model="select_property">
                                    <option value="">-- Select Property --</option>
                                    @foreach ($filter_properties as $properties)
                                    <option value="{{$properties->id}}">{{$properties->property_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-12 col-lg-12 col-md-4">
                            <div class="form-group">
                                <label class="text-blue">Start Date</label>
                                <input type="date" class="form-control custom-form-input1" wire:model="start_date">
                                @error('start_date')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-4">
                            <div class="form-group">
                                <label class="text-blue">End Date</label>
                                <input type="date" class="form-control custom-form-input1" wire:model="end_date">
                                @error('end_date')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-right">
                        <button class="btn btn-for-print" id="print" onclick="printContent();" type="button">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button class="btn btn-for-excel" id="print" onclick="ExportToExcel('xlsx');" type="button">
                            <i class="fa fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-9">
            <div class="card  card-tpos-primary2">
                <div class="pt-3 pr-3 pl-3">
                    <h4 align="center" class="text-white report-title" style="color:rgb(0, 120, 200);">
                        Ledger</h4>

                    @if ($select_property != null)
                    <h4 class="text-blue text-center mt-3 mb-0" style="color: #4886ea; text-align:center;">
                        {{$property_name}}
                    </h4>
                    @endif
                </div>
                <div id="printDocument">
                    <div class="p-3">
                        <h3 class="d-none" style="text-align:center;color: #4886ea">
                            {{$property_name}}
                        </h3>

                        <h4 align="center" class="d-none" style="color:#14216a;">
                            Income statement
                        </h4>

                        @if ($start_date)
                        <p class="text-center mb-0" style="text-align: center"><b>From</b>
                            {{ \Carbon\Carbon::parse($start_date)->format('Y F j') }}
                            <b>to</b>
                            {{ \Carbon\Carbon::parse($end_date)->format('Y F j') }}
                        </p>
                        @endif

                    </div>
                    <div class=" pr-3 pl-3 pb-3">
                        <div class="sub-card p-2">
                            <div class="table-responsive report-table" style="border-radius: 0px;">
                                <table class="table table-striped mb-0 table-bordered table-hover p-2" style="border:solid rgba(0, 0, 0, 0.658); border-width:1px 0px 0px 1px; width:100%" id="xlstable">
                                    <thead>
                                        <tr align="center">
                                            <th style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0; height:40px; width:20px;">
                                                #
                                            </th>
                                            <th style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                                Date
                                            </th>

                                            <th style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                                Detail
                                            </th>
                                            <th style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                                Amount
                                            </th>
                                            <th style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                                Amount
                                            </th>

                                        </tr>
                                    </thead>
                                    <tr>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;" colspan="3"><b>Debit</b></td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;"></td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;"></td>

                                    </tr>
                                    @php($x = 1)
                                    @php($cash_in_total = 0)
                                    @foreach ($cashin_data as $cash_in)
                                    <tr align="center">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0; height:40px;">{{ $x }}.</td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            {{ $cash_in->date }}
                                        </td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            @if(!empty($cash_in->cash_in_type))
                                            @if ($cash_in->cash_in_type == "Customer Credit")
                                            {{ $cash_in->cash_in_type . "  "  . $cash_in->customer_name }}
                                            @else
                                            {{ $cash_in->cash_in_type}}
                                            @endif
                                            @endif
                                        </td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0; text-align: right; padding:3px; color: #4daf80;" class="font-weight-bold text-right">
                                            {{ number_format($cash_in->amount, 2) }}
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;">

                                        </td>

                                    </tr>
                                    <?php $x++;
                                $cash_in_total = $cash_in_total + $cash_in->amount; ?>
                                    @endforeach
                                    <tr style="border-bottom: solid 3px; border-top: 2px solid;">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: center; height:40px;" colspan="3" class="text-center font-weight-bold">
                                            Total
                                        </td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: right; color: #4daf80;" class="font-weight-bold text-right pr-1">
                                            <b>{{ number_format($cash_in_total, 2) }}</b>
                                        </td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;"></td>
                                    </tr>
                                    <tr>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;" colspan="5"></td>
                                    </tr>
                                    <tr>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;" colspan="3"><b>Credit</b></td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;"></td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;"></td>
                                    </tr>
                                    @php($x = 1)
                                    @php($cash_out_total = 0)
                                    @foreach ($cashout_data as $cash_out)
                                    <tr align="center">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0; height:40px;">{{ $x }}.</td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            {{ $cash_out->date }}
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            @if(!empty($cash_out->cash_out_type))
                                            {{ $cash_out->cash_out_type }}
                                            @else
                                            <span>--------------------</span>
                                            @endif
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;"></td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0; text-align: right; padding:3px; color: #4daf80;" class="font-weight-bold text-right">
                                            {{ number_format($cash_out->amount, 2) }}
                                        </td>

                                    </tr>
                                    <?php $x++;
                                $cash_out_total = $cash_out_total + $cash_out->amount; ?>
                                    @endforeach

                                    {{-- cardPAy --}}
                                    @php($x = 1)
                                    @php($card_pay_total = 0)
                                    @foreach ($card_payment as $cardPay)
                                    <tr align="center">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                     border-width:0 1px 1px 0; height:40px;">{{ $x }}.</td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                     border-width:0 1px 1px 0;">
                                            {{ $cardPay->date }}
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                     border-width:0 1px 1px 0;">
                                            @if(!empty($cardPay->payment_type == 'Card'))
                                            {{-- {{ $cardPay->payment_type == 'Card' }} --}}
                                            Card Payment
                                            @else
                                            <span>--------------------</span>
                                            @endif
                                        </td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                         border-width:0 1px 1px 0;"></td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                         border-width:0 1px 1px 0; text-align: right; padding:3px; color: #4daf80;" class="font-weight-bold text-right">
                                            {{ number_format($cardPay->amount, 2) }}
                                        </td>

                                    </tr>
                                    <?php $x++;
                                $card_pay_total = $card_pay_total + $cardPay->amount; ?>
                                    @endforeach


                                    {{-- creditcustomer --}}
                                    {{-- @php($x = 1) --}}
                                    @php($credit_amount = 0)
                                    @php($total_credits = 0)
                                    @foreach ($credit_cus as $Customer)
                                    <tr align="center">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                     border-width:0 1px 1px 0; height:40px;">{{ $x }}.</td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                     border-width:0 1px 1px 0;">
                                            {{ $Customer->date }}
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                     border-width:0 1px 1px 0;">
                                            @if(!empty($Customer->payment_type == 'Credit'))
                                            {{-- {{ $Customer->payment_type == 'Credit' }} --}}
                                            {{ $Customer->payment_type . "  "  . $Customer->customer_name }}


                                            @else
                                            <span>--------------------</span>
                                            @endif
                                        </td>


                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                         border-width:0 1px 1px 0;"></td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                         border-width:0 1px 1px 0; text-align: right; padding:3px; color: #4daf80;" class="font-weight-bold text-right">
                                            {{ number_format($Customer->amount, 2) }}
                                        </td>

                                    </tr>
                                    <?php $x++;
                                $credit_amount = $credit_amount + $Customer->amount;
                                        $total_credits = ($credit_amount + $card_pay_total +  $cash_out_total)
                                ?>
                                    @endforeach

                                    <tr style="border-bottom: solid 3px; border-top: 2px solid;">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: center; height:40px;" colspan="3" class="text-center font-weight-bold">
                                            Total
                                        </td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0;"></td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: right; color: #4daf80;" class="font-weight-bold text-right pr-1">
                                            <b>{{ number_format($total_credits, 2) }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                        border-width:0 1px 1px 0; text-align: left; color: #4daf80;" class="font-weight-bold text-left pr-1" colspan="4">
                                            <b>Net Profit or Less</b>
                                        </td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: right; color: #4daf80;" class="font-weight-bold text-right pr-1">
                                            <b>{{ number_format($cash_in_total - $total_credits, 2) }}</b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
    function printContent() {
        var prtContent = document.getElementById("printDocument");
        var WinPrint = window.open();
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }

    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('xlstable');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type
                , bookSST: true
                , type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('ExpenseReport.' + (type || 'xlsx')));
    }

</script>
