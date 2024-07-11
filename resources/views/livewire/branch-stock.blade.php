@push('branch-stock', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-file"></i>Reports</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Stock Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card card-tpos-primary2 p-3 sales-summary-card">
                <div class="report-filter-head mb-3">
                    <h4 class="text-light-green mb-0">
                        Filters
                    </h4>
                    <button class="btn btn-sm clear-btn" title="Clear all selection" wire:click="clearFilter">
                        &times;
                        Clear
                    </button>
                </div>

                <div class="sub-card summary-area p-3">
                    <div class="row">
                        <div class="col-12 col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group mb-2">
                                <label class="text-blue custom-label" for="category">Filter by Category</label>
                                <select class="custom-form-input1 form-control text-center"
                                    wire:model="select_category">
                                    <option value="0">-- Select here --</option>
                                    @foreach ($filter_categories as $cat_filter)
                                    <option value="{{ $cat_filter->id }}">
                                        {{ $cat_filter->category }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-xl-4 col-lg-4 col-md-6">
                            <div class="form-group mb-2 mt-3 mt-md-0">
                                <label class="text-blue custom-label" for="brand">Filter by Brand</label>
                                <select class="custom-form-input1 form-control text-center" wire:model="select_brand">
                                    <option value="0">-- Select here --</option>
                                    @foreach ($filter_brands as $br_filter)
                                    <option class="text-capitalized" value="{{ $br_filter->id }}">
                                        {{ $br_filter->brand }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-xl-4 col-lg-4 col-md-12">
                            <div class="form-group mb-2 mt-3 mt-md-3 mt-lg-0">
                                <label class="text-blue custom-label" for="brand">Search</label>
                                <input type="search" class="custom-form-input1 form-control"
                                    placeholder="Search product by Barcode/Name" wire:model="searchKey"
                                    wire:keyup="fetchData">
                            </div>
                        </div>
                    </div>
                    @if ($list_data)
                    <hr>
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-12 d-flex align-items-center">
                            <h6 class="text-d-blue mb-3 mb-md-0">Print</h6>
                        </div>
                        <div class="col-lg-2 col-md-3 col-12 ">
                            <button class="btn btn-for-print mt-0 w-100" id="print" onclick="printContent();"
                                type="button">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>

                        <div class="col-lg-2 col-md-3 col-12">
                            <button class="btn btn-for-excel mt-3 mt-md-0 w-100" id="print" onclick="ExportToExcel('xlsx');"
                                type="button">
                                <i class="fa fa-file-excel"></i> Excel
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card p-3 card-tpos-primary2 sales-summary-card card-tpos-secondary">
                @if ($select_category != 0 || $select_brand != 0 || $searchKey != null)
                <div class="pt-3 pr-3 pl-3 report-title">
                    <h4 align="center" class="text-light-green" style="color:rgb(0, 120, 200);">
                        Stock Report
                    </h4>

                    @if ($propertyData != null)
                    <h6 class="text-white text-center mt-2 mb-0" style="color: #4886ea; text-align:center;">
                        {{ $propertyData->property_name }}
                    </h6>
                    @endif
                </div>

                <div id="printDocument">
                    <div class="pt-3 pb-3">
                        @if ($propertyData != null)
                        <h3 class="d-none" style="text-align:center;color: #4886ea">
                            {{ $propertyData->property_name }}
                        </h3>
                        @endif

                        <h4 align="center" class="d-none" style="color:#14216a;">
                            Stock Report
                        </h4>

                        @if ($start_date)
                        <p class="text-center mb-0" class="text-d-blue" style="text-align: center"><b>Report Date:</b>
                            {{ \Carbon\Carbon::parse($start_date)->format('Y F j') }}
                        </p>
                        @endif
                    </div>

                    <div class="table-responsive report-table" style="border-radius: 0px;">
                        <table class="table table-bordered table-hover table-bg custom-border-white mb-0"
                            style="border:solid rgba(0, 0, 0, 0.658); border-width:1px 0px 0px 1px; width:100%"
                            id="xlstable">
                            <thead>
                                <tr style="border-bottom-color: rgba(0, 0, 0, 0.658) !important; ">
                                    <th class="text-d-blue" style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0; height:40px; width:20px;">
                                        #
                                    </th>

                                    <th class="text-d-blue" style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0;">
                                        Item
                                    </th>

                                    <th class="text-d-blue" style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0;text-align:center;">
                                        Transfer Quantity
                                    </th>
                                    <th class="text-d-blue" style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0;text-align:center;">
                                        Stock Quantity
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @php($x = 1)
                                @php($total_transfer_qty = 0)
                                @php($total = 0)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0; height:40px;">
                                        {{ $x }}.</td>

                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0; text-transform:capitalize;">
                                        <b> {{ $row->item_name }}</b>
                                        <br>
                                        <small>{{ $row->category_name }} - {{ $row->brand_name }}</small>
                                    </td>

                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0; text-align:center;">
                                        {{ $row->total_transfer }}
                                    </td>

                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0; text-align:center;">
                                        {{ $row->stock_qty }}
                                    </td>
                                </tr>

                                <?php $x++;
                                        $total = $total + $row->stock_qty;
                                        $total_transfer_qty = $total_transfer_qty + $row->total_transfer;
                                        ?>
                                @endforeach
                                @if (sizeOf($list_data) == 0)
                                <tr>
                                    <td colspan="4" class="text-secondary text-center">No item found.</td>
                                </tr>
                                @endif
                            </tbody>

                            <tfoot>
                                <tr style="border-bottom: solid 3px; border-top: 2px solid;">
                                    <td style="border:solid rgba(0, 0, 0, 0.658);
                                            border-width:0 1px 1px 0; height:40px;" colspan="2"
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
                            </tfoot>
                        </table>

                    </div>
                </div>

                @else
                <div class="row">
                    <div class="col-12">
                        <div class="report-title mb-3">
                            <h4 align="center" class="text-light-green mb-0" style="color:rgb(0, 120, 200);">
                                Stock Report
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="no-selection-area mb-0">
                            <h5 class="text-center mb-0">Please select any filter or search an item!</h5>
                        </div>
                    </div>
                </div>
                @endif
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