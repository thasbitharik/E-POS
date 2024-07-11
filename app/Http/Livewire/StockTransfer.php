<?php

namespace App\Http\Livewire;

use App\Models\BranchStore as BranchStoreModel;
use App\Models\BranchTransfer;
use App\Models\InvoiceItem as InvoiceItemModel;
use App\Models\PurchaseInvoice as PurchaseInvoiceModel;
use App\Models\Property;
use App\Models\TemTransfer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Route;

class StockTransfer extends Component
{
    public $invoiceId;
    public $page_action = [];
    //public variables basic
    public $list_data = [];

    public $branch_data = [];

    public $searchKey;
    public $key = 0;
    public $message = "";

    public $tab = 1;
    //new variable data management
    public $new_brand = "";

    public $new_main_quantity = 0;
    public $new_branch_quantity = 0;

    public $property_list = [];
    public $new_property_id;

    public $transferBill;
    public $bill_date;
    public $bill_items = [];
    public $branch;

    public function mount($id)
    {
        $this->bill_date = date('Y-m-d');
        $this->invoiceId = $id;
    }

    //open model insert
    public function openModel()
    {
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //close model insert
    public function closeModel()
    {
        $this->dispatchBrowserEvent('insert-hide-form');
    }

    //open model delete
    public $delete_id = 0;
    public function deleteOpenModel($id)
    {
        $this->delete_id = $id;
        $this->dispatchBrowserEvent('delete-show-form');
    }

    #delete Data
    public function deleteRecord()
    {
        if ($this->delete_id != 0) {
            $data = TemTransfer::find($this->delete_id);

            //update main store balance
            $main = InvoiceItemModel::find($data->invoice_items_id);
            $main->quantity = $main->quantity + $data->quantity;
            $main->save();
            $data->delete();

            $this->deleteCloseModel();

            //show Delete message
            session()->flash('del_message', 'Record Deleted!');
        }
    }

    //close model close
    public function deleteCloseModel()
    {
        $this->dispatchBrowserEvent('delete-hide-form');
    }

    public function resetMainStockQuantity()
    {
        $this->new_main_quantity = 0;
    }

    public function searchClear()
    {
        $this->searchKey = "";
        $this->dispatchBrowserEvent('change-focus-other-field');
    }

    public $tem_list = [];

    public $tem_total_sell;
    public $tem_total_buy;
    public $tem_number_of_item;
    public $total_items;
    public $transfer_total;
    public $transfer_sell_total;

    public function foundValues()
    {
        $this->tem_total_sell = 0;
        $this->tem_total_buy = 0;
        $this->total_items = 0;
        $this->tem_number_of_item = 0;
        $this->transfer_total = 0;
        $this->transfer_sell_total = 0;

        if (sizeOf($this->tem_list) != 0) {

            for ($i = 0; $i < sizeOf($this->tem_list); $i++) {
                $this->tem_total_sell = $this->tem_total_sell + $this->tem_list[$i]->sell_price;
                $this->tem_total_buy = $this->tem_total_buy + $this->tem_list[$i]->buy_price;
                $this->total_items = $this->total_items + $this->tem_list[$i]->quantity;
                $this->tem_number_of_item = sizeOf($this->tem_list);
                $this->transfer_total = $this->transfer_total + ($this->tem_list[$i]->quantity * $this->tem_list[$i]->buy_price);
                $this->transfer_sell_total = $this->transfer_sell_total + ($this->tem_list[$i]->quantity * $this->tem_list[$i]->sell_price);
            }
        } else {
            $this->tem_total_sell = 0;
            $this->tem_total_buy = 0;
            $this->total_items = 0;
            $this->tem_number_of_item = 0;
            $this->transfer_total = 0;
            $this->transfer_sell_total = 0;
        }
    }

    //fetch data from db
    public function fetchData()
    {
        #if search active
        // $this->tem_list = TemTransfer::where('property_id', '=', Auth::user()->property_id)->where('auth_id', '=', Auth::user()->id)->get();
        $this->property_list = Property::where('id', '!=', 1)->get();

        $this->tem_list = DB::table('tem_transfers')
            ->select('tem_transfers.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
            ->join('invoice_items', 'invoice_items.id', '=', 'tem_transfers.invoice_items_id')
            ->join('items', 'items.id', '=', 'invoice_items.item_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
            ->where('tem_transfers.property_id', '=', Auth::user()->property_id)
            ->where('tem_transfers.auth_id', '=', Auth::user()->id)
            ->where('tem_transfers.invoice_id', '=', $this->invoiceId)
            ->get();

        if (sizeOf($this->tem_list) != 0) {
            $this->foundValues();
        }

        if ($this->searchKey) {
            $this->list_data = DB::table('invoice_items')
                ->select('invoice_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
                ->join('items', 'items.id', '=', 'invoice_items.item_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->where('invoice_items.barcode', '=', $this->searchKey)
                ->where('invoice_items.invoice_id', '=', $this->invoiceId)
                ->latest()
                ->get();

            $this->branch_data = DB::table('branch_stores')
                ->select('branch_stores.*', 'invoice_items.barcode')
                ->join('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
                ->where('invoice_items.barcode', '=', $this->searchKey)
                ->where('branch_stores.invoice_id', '=', $this->invoiceId)
                ->get();
        } else {
            $this->list_data = [];
            $this->branch_data = [];
        }

        if ($this->new_property_id) {
            $this->branch = Property::find($this->new_property_id);
        }

        if ($this->transferBill) {
            // printing infomations of bill
            $this->bill_items = DB::table('branch_stores')
                ->select('branch_stores.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
                ->join('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
                ->join('items', 'items.id', '=', 'invoice_items.item_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->where('branch_stores.transfer_id', '=', $this->transferBill->id)
                ->where('branch_stores.invoice_id', '=', $this->invoiceId)
                ->get();
        }
    }

    // insert and update data here
    public function mainToBranchTransfer()
    {
        $this->fetchData();
        //validate data
        $this->validate(
            [
                'new_main_quantity' => 'required|numeric|min:1|max:' . ($this->list_data[0]->quantity)
            ],
            [
                'new_main_quantity.required' => "The transfer quantity is required.",
                'new_main_quantity.numeric' => "The transfer quantity must be a number.",
                'new_main_quantity.min' => "The transfer quantity must be grater than 0.",
                'new_main_quantity.max' => "The transfer quantity must not be greater than " . ($this->list_data[0]->quantity) . "."
            ]
        );

        // make tem transfer
        // check item alredy have
        $check = TemTransfer::where('property_id', '=', Auth::user()->property_id)
            ->where('auth_id', '=', Auth::user()->id)
            ->where('invoice_items_id', '=', $this->list_data[0]->id)
            ->where('invoice_id', $this->invoiceId)
            ->first();
        // new item
        if ($check) {
            $tem = TemTransfer::find($check->id);
            $tem->buy_price = $this->list_data[0]->buy;
            $tem->sell_price = $this->list_data[0]->sell;
            $tem->quantity = $check->quantity + $this->new_main_quantity;
            $tem->save();
        } else {
            $tem = new TemTransfer();
            $tem->property_id = Auth::user()->property_id;
            $tem->auth_id = Auth::user()->id;
            $tem->invoice_id = $this->invoiceId;
            $tem->invoice_items_id = $this->list_data[0]->id;
            $tem->buy_price = $this->list_data[0]->buy;
            $tem->sell_price = $this->list_data[0]->sell;
            $tem->quantity = $this->new_main_quantity;
            $tem->save();
        }

        //update main store balance
        $main = InvoiceItemModel::find($this->list_data[0]->id);
        $main->quantity = $main->quantity - $this->new_main_quantity;
        $main->save();

        //show success message
        session()->flash('main_message', ' Stock added Successfuly!');
        //clear data
        $this->clearData();
        $this->searchClear();
    }

    public function finalTransfer()
    {
        // make transfer note
        $transferBill = new BranchTransfer();
        $transferBill->amount = $this->transfer_total;
        $transferBill->sell_amount = $this->transfer_sell_total;
        $transferBill->trasfer_date = $this->bill_date;
        $transferBill->total_buy = $this->tem_total_buy;
        $transferBill->total_sell = $this->tem_total_sell;
        $transferBill->item_count = $this->tem_number_of_item;
        $transferBill->total_item_quantity = $this->total_items;
        $transferBill->property_id = $this->new_property_id;
        $transferBill->invoice_id = $this->invoiceId;
        $transferBill->auth_id = Auth::user()->id;
        $transferBill->save();

        $this->transferBill = $transferBill;

        // transfer stock to branch store
        foreach ($this->tem_list as $row) {
            $da = new BranchStoreModel();
            $da->transfer_id = $this->transferBill->id;
            $da->invoice_id = $this->invoiceId;
            $da->quantity = $row['quantity'];
            $da->transfer_qty = $row['quantity'];
            $da->sell_price = $row['sell_price'];
            $da->buy_price = $row['buy_price'];
            $da->invoice_items_id = $row['invoice_items_id'];
            $da->property_id = $this->new_property_id;
            $da->auth_id = Auth::user()->id;
            $da->save();

            $de = TemTransfer::find($row['id']);
            $de->delete();
        }

        $transfered_bill = PurchaseInvoiceModel::find($this->invoiceId);
        $transfered_bill->is_transfered = 1;
        $transfered_bill->save();

        $this->dispatchBrowserEvent('do-print');

        session()->flash('transfer_message', 'Stock transfer completed successfully!');

        // return Redirect::to('/purchase-invoice');
        $this->redirectWithDelay('/purchase-invoice');
    }

    private function redirectWithDelay($url, $delay = 3000)
    {
        $this->emit('redirect', $url, $delay);
    }


    public function checkPrint()
    {
        $this->dispatchBrowserEvent('do-print');
    }

    //clear data
    public function clearData()
    {
        # emty field
        $this->key = 0;
        $this->new_main_quantity = 0;
        $this->new_branch_quantity = 0;
    }


    #comon controls
    public function pageAction()
    {
        $x = Route::currentRouteName();
        $data = DB::table('access_points')
            ->select('access_points.access_model_id', 'access_points.id as access_point', 'access_points.value', 'access_models.access_model')
            ->join('access_models', 'access_points.access_model_id', '=', 'access_models.id')
            ->where('access_models.access_model', '=', $x)
            ->get();

        $access = session()->get('Access');
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i]->access_point, $access)) {
                array_push($this->page_action, $data[$i]->value);
            }
        }
    }

    public function render()
    {
        $invoice_details = DB::table('purchase_invoices')
            ->select('purchase_invoices.*', 'dealers.name as dealer_name', 'companies.company_name')
            ->leftJoin('dealers', 'purchase_invoices.dealer_id', 'dealers.id')
            ->leftJoin('companies', 'purchase_invoices.company_id', 'companies.id')
            ->where('purchase_invoices.id', $this->invoiceId)
            ->get();

        $this->fetchData();
        $this->pageAction();
        return view('livewire.stock-transfer', compact('invoice_details'))->layout('layouts.master');
    }
}
