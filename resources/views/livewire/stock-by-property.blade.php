@push('property', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/property"><i class="fas fa-file"></i>Property</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Stock Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-2 col-md-2 col-12">
            <button class="btn btn-for-print mt-0" id="print" onclick="printContent();" type="button">
                <i class="fas fa-print"></i> Print
            </button>
        </div>

        <div class="col-lg-2 col-md-2 col-12">
            <button class="btn btn-for-excel mt-0" id="print" onclick="ExportToExcel('xlsx');" type="button">
                <i class="fa fa-file-excel"></i> Excel
            </button>
        </div>

        <div class="col-12 col-md-8">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search Here..." aria-label="">
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card  card-tpos-primary2">
                <div class="pt-3 pr-3 pl-3">
                    <div class="row">
                        <div class="col-12">
                            <br>
                            <h4 align="center" class="text-d-blue report-title" style="color:rgb(0, 120, 200);">
                                Stock Report
                            </h4>
                        </div>
                        {{-- <div class="col-lg-3 col-md-3 col-12"> <button class="btn btn-for-print" id="print"
                                onclick="printContent();" type="button">
                                <i class="fas fa-print"></i> Print
                            </button></div>

                        <div class="col-lg-3 col-md-3 col-12">
                            <button class="btn btn-for-excel" id="print" onclick="ExportToExcel('xlsx');" type="button">
                                <i class="fa fa-file-excel"></i> Excel
                            </button>
                        </div> --}}
                    </div>


                    @if ($propertyData != null)
                    <h4 class="text-blue text-center mt-3 mb-0" style="color: #4886ea; text-align:center;">
                        {{ $propertyData->property_name }}
                    </h4>
                    @endif

                </div>
                <div id="printDocument">
                    <div class="p-3">
                        @if ($propertyData != null)
                        <h3 class="d-none" style="text-align:center;color: #4886ea">
                            {{ $propertyData->property_name }}
                        </h3>
                        @endif

                        <h4 align="center" class="d-none" style="color:#14216a;">
                            Stock Report
                        </h4>

                        @if ($start_date)
                        <p class="text-center mb-0" style="text-align: center"><b>Report Date:</b>
                            {{ \Carbon\Carbon::parse($start_date)->format('Y F j') }}
                        </p>
                        @endif
                    </div>

                    <div class=" pr-3 pl-3 pb-3">
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

                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                            Item
                                        </th>

                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                            Transfer Quantity
                                        </th>
                                        <th style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0;">
                                            Stock Quantity
                                        </th>

                                    </tr>
                                    @php($x = 1)
                                    @php($total_transfer_qty = 0)
                                    @php($total = 0)
                                    @foreach ($list_data as $row)
                                    <tr align="center">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0; height:40px;">
                                            {{ $x }}.</td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            <b> {{ $row->item_name }}
                                                -{{ $row->measure . '' . $row->measurement_name }}</b>
                                            <br>
                                            {{ $row->category_name }} - {{ $row->brand_name }}
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            {{ $row->total_transfer }}
                                        </td>

                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                    border-width:0 1px 1px 0;">
                                            {{ $row->stock_qty }}
                                        </td>
                                    </tr>
                                    <?php $x++;
                                        $total = $total + $row->stock_qty;
                                        $total_transfer_qty = $total_transfer_qty + $row->total_transfer;
                                        ?>
                                    @endforeach
                                    <tr style="border-bottom: solid 3px; border-top: 2px solid;">
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: center; height:40px;" colspan="2"
                                            class="text-center font-weight-bold">
                                            Total
                                        </td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: center; colrgb(3, 3, 3)af80;"
                                            class="font-weight-bold text-center pr-1">
                                            <b>{{ number_format($total_transfer_qty) }}</b>
                                        </td>
                                        <td style="border:solid rgba(0, 0, 0, 0.658);
                                border-width:0 1px 1px 0; text-align: center; colrgb(3, 3, 3)af80;"
                                            class="font-weight-bold text-center pr-1">
                                            <b>{{ number_format($total) }}</b>
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
</div>