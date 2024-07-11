<?php

namespace App\Http\Livewire;

use App\Models\Brand as BrandModel;
use App\Models\Category as CategoryModel;
use App\Models\InvoiceItem as InvoiceItemModel;
use Carbon\Carbon;
// use DNS1D;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;

class InvoiceItem extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];
    public $purchase_invoice = [];
    public $categories = [];
    public $brands = [];
    public $items = [];
    public $invoice_items = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";

    //new variable data management
    public $purchase_id;
    public $new_expiry;
    public $new_min_sell;
    public $new_sell;
    public $new_buy;
    public $new_quantity;
    public $new_item;
    public $new_mfd_date;
    public $new_barcode;

    # filter
    public $select_category = "";
    public $select_brand = "";
    public $search_barcode = "";

    public $data_count = 0;
    public $invoice_items_value = 0;

    public $existing_barcode;
    public $barcode = '';

    //mount function inital call
    public function mount($id)
    {
        $this->purchase_id = $id;
    }

    //open model insert
    public function openModel()
    {
        $this->clearData();
        $this->showError = false;
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
            $delear = InvoiceItemModel::find($this->delete_id);
            $delear->delete();
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

    //load data
    public function loadData()
    {
        // # fetch data from database
        // if ($this->select_brand == "" && $this->select_category == "") {
        //     #all item show
        //     $this->items = DB::table('items')
        //         ->select('items.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
        //         ->join('categories', 'categories.id', '=', 'items.category_id')
        //         ->join('brands', 'brands.id', '=', 'items.brand_id')
        //         ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
        //         ->orderBy('items.item_name', 'ASC')
        //         ->take(50)
        //         ->get();

        // } elseif ($this->select_brand != "" && $this->select_category == "") {
        //     $this->items = DB::table('items')
        //         ->select('items.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
        //         ->join('categories', 'categories.id', '=', 'items.category_id')
        //         ->join('brands', 'brands.id', '=', 'items.brand_id')
        //         ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
        //         ->where('brands.id', '=', $this->select_brand)
        //         ->orderBy('items.item_name', 'ASC')
        //         ->get();

        // } elseif ($this->select_category != "" && $this->select_brand == "") {
        //     $this->items = DB::table('items')
        //         ->select('items.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
        //         ->join('categories', 'categories.id', '=', 'items.category_id')
        //         ->join('brands', 'brands.id', '=', 'items.brand_id')
        //         ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
        //         ->where('categories.id', '=', $this->select_category)
        //         ->orderBy('items.item_name', 'ASC')
        //         ->get();

        // } elseif ($this->select_brand != "" && $this->select_category != "") {
        //     $this->items = DB::table('items')
        //         ->select('items.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
        //         ->join('categories', 'categories.id', '=', 'items.category_id')
        //         ->join('brands', 'brands.id', '=', 'items.brand_id')
        //         ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
        //         ->where('categories.id', '=', $this->select_category)
        //         ->where('brands.id', '=', $this->select_brand)
        //         ->orderBy('items.item_name', 'ASC')
        //         ->get();
        // } elseif ($this->search_barcode != "") {
        //     $this->items = DB::table('items')
        //         ->select('items.*', 'invoice_items.barcode')
        //         ->join('invoice_items', 'items.id', '=', 'invoice_items.item_id')
        //         ->where('invoice_items.barcode', '=', $this->search_barcode)
        //         ->orderBy('items.item_name', 'ASC')
        //         ->get();
        // } else {
        //     $this->items = [];
        // }

        $this->items = DB::table('items')
            ->select('items.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name', 'invoice_items.barcode')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
            ->leftJoin('invoice_items', 'items.id', '=', 'invoice_items.item_id')
            ->distinct();

        if ($this->select_category) {
            $this->items = $this->items->where('categories.id', '=', $this->select_category);
        }

        if ($this->select_brand) {
            $this->items = $this->items->where('brands.id', '=', $this->select_brand);
        }

        if ($this->search_barcode) {
            $this->items = $this->items->where('invoice_items.barcode', '=', $this->search_barcode);
        }

        if ($this->search_barcode && $this->items->count() == 0) {
            session()->flash('item_not_found', 'Item not found with this barcode!');
        }

        if ($this->select_category == "" && $this->select_brand == "" && $this->search_barcode == "") {
            $this->items = $this->items->take(50);
        }

        $this->items = $this->items->orderBy('items.item_name', 'ASC')
            ->get();


        $this->existing_barcode = InvoiceItemModel::where('item_id', $this->new_item)
            ->orderBy('id', 'desc')
            ->value('barcode');

        if ($this->purchase_id) {
            $this->invoice_items_value = DB::table('invoice_items')
                ->where('invoice_items.invoice_id', '=', $this->purchase_id)
                ->sum(DB::raw('quantity * buy'));
        } else {
            $this->invoice_items_value = 0;
        }
    }

    public function setBarcode($barcode)
    {
        $this->new_barcode = $barcode;
    }

    public function clearBarcode()
    {
        $this->new_barcode = '';
    }

    //fetch data from db
    public function fetchData()
    {
        $this->categories = CategoryModel::select('categories.*')->orderBy('categories.category', 'ASC')->get();
        $this->brands = BrandModel::select('brands.*')->orderBy('brands.brand', 'ASC')->get();

        #fetch company details
        $this->purchase_invoice = DB::table('purchase_invoices')
            ->select('purchase_invoices.*', 'companies.company_name', 'dealers.name as dealer_name')
            ->join('companies', 'companies.id', '=', 'purchase_invoices.company_id')
            ->join('dealers', 'dealers.id', '=', 'purchase_invoices.dealer_id')
            ->where('purchase_invoices.id', '=', $this->purchase_id)
            ->latest()
            ->get();


        #if search active
        // if (!$this->searchKey) {
        //     $this->list_data = DB::table('invoice_items')
        //         ->select('invoice_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
        //         ->join('items', 'items.id', '=', 'invoice_items.item_id')
        //         ->join('categories', 'categories.id', '=', 'items.category_id')
        //         ->join('brands', 'brands.id', '=', 'items.brand_id')
        //         ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
        //         ->where('invoice_items.invoice_id', '=', $this->purchase_id)
        //         ->latest()
        //         ->skip($this->count)
        //         ->limit(10)
        //         ->get();
        // } else {
        //     $this->list_data = DB::table('invoice_items')
        //         ->select('invoice_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
        //         ->join('items', 'items.id', '=', 'invoice_items.item_id')
        //         ->join('categories', 'categories.id', '=', 'items.category_id')
        //         ->join('brands', 'brands.id', '=', 'items.brand_id')
        //         ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
        //         ->where('invoice_items.invoice_id', '=', $this->purchase_id)
        //         ->where('invoice_items.barcode', 'LIKE', "%{$this->searchKey}%")
        //         ->orWhere('items.item_name', 'LIKE', "%{$this->searchKey}%")
        //         ->latest()
        //         ->skip($this->count)
        //         ->limit(10)
        //         ->get();
        // }

        $query = DB::table('invoice_items')
            ->select('invoice_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
            ->join('items', 'items.id', '=', 'invoice_items.item_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
            ->where('invoice_items.invoice_id', '=', $this->purchase_id)
            ->latest()
            ->skip($this->count)
            ->limit(10);

        $this->list_data = $query->when($this->searchKey, function ($query) {
            $query->where(function ($query) {
                $query->where('invoice_items.barcode', 'LIKE', "%{$this->searchKey}%")
                    ->orWhere('items.item_name', 'LIKE', "%{$this->searchKey}%");
            });
        })->get();

        $this->data_count = DB::table('invoice_items')
            ->where('invoice_items.invoice_id', $this->purchase_id)
            ->count('id');
    }

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;
        //validate data
        $this->validate(
            [
                'new_item' => 'required',
                'new_quantity' => 'required|min:1',
                'new_buy' => 'required|min:1',
                'new_sell' => 'required|min:1',
                'new_min_sell' => 'required|min:1',
                'new_expiry' => 'required|after:today',
                'new_mfd_date' => 'required|before:today',
            ]
        );

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new InvoiceItemModel();
            $bar = "";
            if ($this->new_barcode) {
                $bar = $this->new_barcode;
            } else {
                $bar = (Carbon::now()->timestamp) - 1600000000;

                // \Storage::disk('barcode')->put($bar . '.png', base64_decode(DNS1D::getBarcodePNG($bar, "I25")));
                // \Storage::disk('barcode')->put('44456456' . '.png', base64_decode(DNS1D::getBarcodePNG('44456456', 'I25')));

            }

            $data->quantity = $this->new_quantity;
            $data->buy_quantity = $this->new_quantity;
            $data->sell = $this->new_sell;
            $data->buy = $this->new_buy;
            $data->min_sell = $this->new_min_sell;
            $data->expiry = $this->new_expiry;
            $data->mfd = $this->new_mfd_date;
            $data->barcode = $bar;
            $data->item_id = $this->new_item;
            $data->invoice_id = $this->purchase_id;
            $data->save();

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = InvoiceItemModel::find($this->key);
            $bar = "";
            if ($this->new_barcode) {
                $bar = $this->new_barcode;
            } else {
                $bar = (Carbon::now()->timestamp) - 1600000000;
            }
            $data->quantity = $this->new_quantity;
            $data->buy_quantity = $this->new_quantity;
            $data->sell = $this->new_sell;
            $data->buy = $this->new_buy;
            $data->min_sell = $this->new_min_sell;
            $data->expiry = $this->new_expiry;
            $data->mfd = $this->new_mfd_date;
            $data->barcode = $bar;
            $data->item_id = $this->new_item;
            $data->invoice_id = $this->purchase_id;
            $data->save();

            //show success message
            session()->flash('message', ' Update Successfuly!');

            //clear data
            $this->clearData();
        }
    }

    public function clearFilter()
    {
        $this->select_brand = "";
        $this->select_category = "";
        $this->search_barcode = "";
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $item = InvoiceItemModel::find($id);

        $this->new_item = $item->item_id;
        $this->new_expiry = $item->expiry;
        $this->new_mfd_date = $item->mfd;
        $this->key = $id;
        $this->new_min_sell = $item->min_sell;
        $this->new_sell = $item->sell;
        $this->new_buy = $item->buy;
        $this->new_quantity = $item->quantity;
        $this->new_barcode = $item->barcode;
        $this->key = $id;

        $this->showError = false;
        // open model in edit
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //clear data
    public function clearData()
    {
        # emty field
        $this->key = 0;
        $this->new_item = "";
        $this->new_expiry = "";
        $this->new_mfd_date = "";
        $this->new_min_sell = "";
        $this->new_sell = "";
        $this->new_buy = "";
        $this->new_quantity = "";
        $this->new_barcode = null;
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
        $this->loadData();
        $this->fetchData();
        $this->pageAction();
        return view('livewire.invoice-item')->layout('layouts.master');
    }
}
