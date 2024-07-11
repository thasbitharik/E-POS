@push('return-report', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-file"></i>Reports</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Return Report</li>
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
                        <div class="col-12 col-lg-12 col-md-4">
                            <div class="form-group">
                                <label class="text-blue">Invoice</label>
                                <select class="form-control custom-form-input1" wire:model="select_invoice_number">
                                    <option value="">-- Select Invoice --</option>
                                    @foreach ($returned_bills as $r_bills)
                                    <option value="{{$r_bills->invoice_no}}">{{ str_pad($r_bills->invoice_no, 8, '0',
                                        STR_PAD_LEFT) }}</option>
                                    @endforeach
                                </select>
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
                    <h4 align="center" class="text-d-blue report-title" style="color:rgb(0, 120, 200);">
                        Return Report</h4>

                    <h4 class="text-blue text-center mt-3 mb-0" style="color: #4886ea; text-align:center;">
                        {{$property_name}}
                    </h4>
                </div>
                <div id="printDocument">
                    <div class="p-3">
                        <h3 class="d-none" style="text-align:center;color: #4886ea">
                            {{$property_name}}
                        </h3>

                        <h4 align="center" class="d-none" style="color:#14216a;">
                            Return Report
                        </h4>

                        @if ($start_date)
                        <p class="text-center mb-0" style="text-align: center"><b>From</b>
                            {{ \Carbon\Carbon::parse($start_date)->format('Y F j') }}
                            <b>to</b>
                            {{ \Carbon\Carbon::parse($end_date)->format('Y F j') }}
                        </p>
                        @endif

                        @if ($select_invoice_number != null)
                        <h6 class="text-d-blue mb-0 mt-2">Invoice Number : <span class="text-blue">
                                {{ str_pad($invoice_number, 8, '0', STR_PAD_LEFT) }}
                            </span>
                        </h6>
                        @endif
                    </div>
                    <div class="pr-3 pl-3 pb-3">
                        <div class="sub-card p-2">
                            <div class="table-responsive report-table" style="border-radius: 0px;">
                                <table class="table table-striped mb-0 table-bordered table-hover p-2"
                                    style="border:solid rgba(0, 0, 0, 0.658); border-width:1px 0px 0px 1px; width:100%"
                                    id="xlstable">
                                    <tr align="center">
                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; height:40px; width:20px;">
                                            #
                                        </th>
                                        @if ($select_invoice_number == null)
                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                            Invoice Number
                                        </th>
                                        @endif
                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                            Date & Time
                                        </th>
                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                            User
                                        </th>
                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                            Return Note
                                        </th>

                                    </tr>
                                    @php($x = 1)
                                    @foreach ($return_data as $row)
                                    <tr align="center">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0; height:40px;">{{ $x }}.</td>

                                        @if ($select_invoice_number == null)
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            {{ str_pad($row->invoice_number, 8, '0', STR_PAD_LEFT) }}
                                        </td>
                                        @endif

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;white-space:nowrap">
                                            {{ $row->date }} <br>
                                            <span style="color: #767676; font-size:12px;">{{
                                                \Carbon\Carbon::parse($row->time)->format('h:i A') }}</span>
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            {{ $row->user_name }}
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0; padding:8px;">
                                            @if(!empty($row->return_history))
                                            {{ $row->return_history }}
                                            @else
                                            <span>--------------------</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $x++; ?>
                                    @endforeach
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
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('ExpenseReport.' + (type || 'xlsx')));
    }
</script>