<?php

namespace App\Http\Livewire;

use App\Models\Brand as BrandModel;
use App\Models\Category as CategoryModel;
use App\Models\InvoiceItem as InvoiceItemModel;
use App\Models\Item as ItemModel;
use App\Models\BranchStore as BranchStoreModel;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;

class Products extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";
    public $selected_item_id = 0;
    public $invoice_id = 0;
    public $selected_item_data = [];

    //new variable data management
    public $new_sell;
    public $new_barcode;
    public $new_item_name;

    # filter
    public $filter_categories = [];
    public $filter_brands = [];
    public $select_category = 0;
    public $select_brand = 0;

    public $data_count = 0;
    public $change_barcode = false;

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
            $stock = BranchStoreModel::find($this->delete_id);
            $stock->delete();
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

    // pagination purpose code
    public $count = 0;
    public function add()
    {
        $this->count = $this->count + 10;
    }

    public function les()
    {
        $this->count = $this->count - 10;
    }

    public function fetchData()
    {
        $this->filter_categories = DB::table('categories')->select('id', 'category')->orderBy('category', 'asc')->get();
        $this->filter_brands = DB::table('brands')->select('id', 'brand')->orderBy('brand', 'asc')->get();

        $query = DB::table('branch_stores')
            ->select(
                'branch_stores.*',
                'invoice_items.barcode',
                'invoice_items.expiry',
                'invoice_items.mfd',
                'items.item_name',
                'items.measure',
                'categories.category as category_name',
                'brands.brand as brand_name',
                'measurements.measurement as measurement_name'
            )
            ->join('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
            ->join('items', 'items.id', '=', 'invoice_items.item_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
            ->latest()
            ->skip($this->count)
            ->limit(10);

        $this->list_data = $query->when($this->searchKey, function ($query) {
            $query->where(function ($query) {
                $query->where('invoice_items.barcode', 'LIKE', "%{$this->searchKey}%")
                    ->orWhere('items.item_name', 'LIKE', "%{$this->searchKey}%");
            });

        })->when($this->select_category, function ($query) {
            $query->where('items.category_id', $this->select_category);

        })->when($this->select_brand, function ($query) {
            $query->where('items.brand_id', $this->select_brand);

        })->get();

        $this->data_count = DB::table('branch_stores')
            ->count('id');
    }

    public function updateRecord($id, $invoiceId)
    {
        $this->selected_item_id = $id;
        $this->invoice_id = $invoiceId;

        $item = BranchStoreModel::find($this->selected_item_id);
        $this->new_sell = $item->sell_price;
        $this->key = $this->selected_item_id;

        $invoice_item = InvoiceItemModel::find($this->invoice_id);
        $this->new_barcode = $invoice_item->barcode;
        $this->new_item_name = $invoice_item->barcode;

        $item_data = ItemModel::find($invoice_item->item_id);
        $this->new_item_name = $item_data->item_name;

        $this->showError = false;
        // open model in edit
        $this->dispatchBrowserEvent('insert-show-form');
    }

    public function updateData()
    {
        $this->showError = true;
        //validate data
        $this->validate(
            [
                'new_sell' => 'required|min:1',
                'new_item_name' => 'required'
            ]
        );

        if ($this->change_barcode === true) {
            $this->validate(
                [
                    'new_barcode' => 'required'
                ]
            );
        }

        if ($this->key != 0) {
            $data = BranchStoreModel::find($this->key);
            $data->sell_price = $this->new_sell;
            $data->save();

            $invoice_item_data = InvoiceItemModel::find($this->invoice_id);
            if ($this->change_barcode === true) {
                $invoice_item_data->barcode = $this->new_barcode;
            }
            $invoice_item_data->sell = $this->new_sell;
            $invoice_item_data->save();

            $item_info = ItemModel::find($invoice_item_data->item_id);
            $item_info->item_name = $this->new_item_name;
            $item_info->save();

            //show success message
            session()->flash('message', ' Update Successfuly!');
        }

        $this->dispatchBrowserEvent('insert-hide-form');
        $this->change_barcode = false;
    }

    public function clearFilter()
    {
        $this->select_category = 0;
        $this->select_brand = 0;
        $this->searchKey = null;
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
        if ($this->selected_item_id != 0) {
            $this->selected_item_data = DB::table('branch_stores')
                ->select(
                    'branch_stores.id',
                    'branch_stores.buy_price',
                    'branch_stores.invoice_items_id',
                    'invoice_items.barcode',
                    'invoice_items.item_id',
                    'items.item_name',
                    'items.measure',
                    'measurements.measurement as measurement_name'
                )
                ->join('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
                ->join('items', 'items.id', '=', 'invoice_items.item_id')
                ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->where('branch_stores.id', $this->selected_item_id)
                ->get();
        } else {
            $this->selected_item_data = [];
        }

        $this->fetchData();
        $this->pageAction();
        return view('livewire.products')->layout('layouts.master');
    }
}
