<div>
    @php
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    @endphp

    <a href="{{ URL::previous() }}" class="btn btn-warning go-back-btn">
        <i class="fa fa-arrow-left mr-2"></i>
        Back
    </a>
    <div class="print-b-code-title d-flex">
        <h5 class="table-title mt-3">
            Print Barcodes
        </h5>
    </div>

    <div class="card p-2 mt-3">
        <div class="barcode-print-title">
            <h5 class="text-light-green text-center mb-0">Your stickers looks like this...</h5>
        </div>
        <div class="sub-card with-border">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-3">
                        <div id='DivIdToPrint' class="d-flex align-items-center justify-content-center">
                            <table class="barcode-print">
                                @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div style="v display: grid;
                                        margin-left:10px;
                            width: 2.3in;height: 1in;
                            padding: 3px;
                            border-radius: 5px;
                            font-weight: 600;  overflow: hidden;
                            ">
                                            <div style="text-align: center;font-size: 11px; text-transform:capitalize;text-wrap:nowrap;">
                                                <b> {{ $item->item_name}}</b>
                                            </div>

                                            <div style="display: flex; align-items: center; justify-content: center;">
                                                {!! $generator->getBarcode($item->barcode, $generator::TYPE_CODE_128)
                                                !!}
                                                {{-- <img style="width: 90%;  height:30%;"
                                                    src="../barcodes/{{ $item->barcode }}.png" alt="barcode"> --}}
                                            </div>

                                            <div style="text-align: center; font-size: 11px;">
                                                {{ $item->barcode }}
                                            </div>

                                            <div style="text-align: center; font-size: 11px;">
                                                <b> {{ 'Rs.' . number_format($item->sell, 2) }}</b>
                                            </div>

                                            <div style="text-align: center; font-size: 11px;">
                                                <b> {{ 'MFD:' . $item->mfd . '| EXP:' . $item->expiry }}</b>
                                            </div>


                                        </div>
                                    </td>
                                    <td width="25px"></td>
                                    <td>
                                        <div style="v display: grid;
                            width: 2.3in;height: 1in;
                            padding: 3px;
                            border-radius: 5px;
                            font-weight: 600; overflow: hidden;
                            ">
                                            <div style="text-align: center;font-size: 11px; text-transform:capitalize; text-wrap:nowrap;">
                                                <b> {{ $item->item_name}}</b>
                                            </div>



                                            <div style="display: flex; align-items: center; justify-content: center;">
                                                {!! $generator->getBarcode($item->barcode, $generator::TYPE_CODE_128)
                                                !!}
                                                {{-- <img style="width: 90%;  height:30%;"
                                                    src="../barcodes/{{ $item->barcode }}.png" alt="barcode"> --}}
                                            </div>

                                            <div style="text-align: center; font-size: 11px;">
                                                {{ $item->barcode }}
                                            </div>

                                            <div style="text-align: center; font-size: 11px;">
                                                <b> {{ 'Rs.' . number_format($item->sell, 2) }}</b>
                                            </div>

                                            <div style="text-align: center; font-size: 11px;">
                                                <b> {{ 'MFD:' . $item->mfd . '| EXP:' . $item->expiry }}</b>
                                            </div>


                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <br>
                        <div class="text-center pt-2">
                            <input type='button' class="btn btn-success" id='btn' value='Print' onclick='printDiv();'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function printDiv() {
        var divToPrint = document.getElementById('DivIdToPrint');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();

        setTimeout(function() {
            newWin.close();
        }, 10);
    }

</script>