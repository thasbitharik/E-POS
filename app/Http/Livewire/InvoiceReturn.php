<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Brand as BrandModel;
use App\Models\Category as CategoryModel;
use App\Models\InvoiceReturn as InvoiceReturnModel;
use App\Models\BranchStore as BranchStoreModel;
use Illuminate\Support\Facades\Auth;
use Route;

class InvoiceReturn extends Component
{
    public $page_action = [];
    public $return_items_data = [];
    public $data_count = 0;
    public $searchKey;
    public $categories = [];
    public $brands = [];
    public $items = [];
    public $key = 0;
    public $message = "";
    public $invoiceId;
    public $propertyId;
    public $userId;
    public $return_select_category = "";
    public $return_select_brand = "";
    public $return_select_item = "";
    public $return_search_item = "";
    public $delete_id = 0;
    public $showError = false;

    // new data variables
    public $new_return_date = "";
    public $new_return_quantity = "";
    public $stock_data = [];
    public $selected_stock_data = [];
    public $visibleReturnArea = false;
    public $stock_id = 0;

    public function mount($id, $date)
    {
        $this->invoiceId = $id;
        $this->new_return_date = $date;
        $this->propertyId = Auth::user()->property_id;
        $this->userId = Auth::user()->id;
    }

    //open return model insert
    public function openReturnModel()
    {
        $this->key = 0;
        $this->new_return_quantity = "";
        $this->showError = false;
        $this->stock_id = 0;
        $this->visibleReturnArea = false;
        $this->selected_stock_data = [];
        $this->dispatchBrowserEvent('item-return-modal-show');
    }

    //close model insert
    public function closeReturnModal()
    {
        $this->dispatchBrowserEvent('item-return-modal-hide');
    }

    //open model delete
    public function deleteOpenModel($id)
    {
        $this->delete_id = $id;
        $this->dispatchBrowserEvent('delete-show-form');
    }

    #delete Data
    public function deleteRecord()
    {
        if ($this->delete_id != 0) {
            $return_data = InvoiceReturnModel::find($this->delete_id);

            $stock_data = BranchStoreModel::find($return_data->branch_store_id);
            $stock_data->quantity = $stock_data->quantity + $return_data->returned_quantity;
            $stock_data->save();

            $return_data->delete();
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

    public $stock_page_count = 0;
    public function nextPage()
    {
        $this->stock_page_count = $this->stock_page_count + 5;
    }

    public function previousPage()
    {
        $this->stock_page_count = $this->stock_page_count - 5;
    }

    public function returnItem()
    {
        $this->showError = true;
        if ($this->stock_id != 0) {
            $stocks = BranchStoreModel::find($this->stock_id);

            $this->validate([
                'new_return_date' => 'required',
                'new_return_quantity' => 'required|numeric|min:1|max:' . ($stocks->quantity)
            ], [
                'new_return_quantity.required' => 'Quantity is required',
                'new_return_quantity.min' => 'The quantity must be at least 1.',
                'new_return_quantity.max' => 'The returning quantity should not exceed the stock quantity.',
            ]);

            if ($this->key == 0) {
                $stocks->quantity = $stocks->quantity - $this->new_return_quantity;
                $stocks->save();

                $return = new InvoiceReturnModel();
                $return->returned_date = $this->new_return_date;
                $return->returned_quantity = $this->new_return_quantity;
                $return->returned_quantity_value = $stocks->buy_price;
                $return->invoice_id = $this->invoiceId;
                $return->invoice_item_id = $stocks->invoice_items_id;
                $return->branch_store_id = $this->stock_id;
                $return->property_id = $this->propertyId;
                $return->auth_id = $this->userId;
                $return->save();

                session()->flash('message', 'Item returned successfully!');
                $this->clearData();

            } else {
                
                $return = InvoiceReturnModel::find($this->key);

                $stocks->quantity = $stocks->quantity + $return->returned_quantity;
                $stocks->quantity = $stocks->quantity - $this->new_return_quantity;
                $stocks->save();

                $return->returned_date = $this->new_return_date;
                $return->returned_quantity = $this->new_return_quantity;
                $return->auth_id = $this->userId;
                $return->save();

                session()->flash('return_message', "Returned item record successfully updated!");

                $this->dispatchBrowserEvent('item-return-modal-hide');
                $this->clearUpdateData();
            }
        }
    }

    public function updateRecord($id)
    {
        $this->key = $id;
        $data = InvoiceReturnModel::find($this->key);

        $this->new_return_date = $data->returned_date;
        $this->new_return_quantity = $data->returned_quantity;

        $this->stock_id = $data->branch_store_id;
        $this->showError = false;
        $this->visibleReturnArea = true;
        // open model in edit
        $this->dispatchBrowserEvent('item-return-modal-show');
    }

    public function addToReturn($id)
    {
        $this->stock_id = $id;
        $this->visibleReturnArea = true;
        $this->dispatchBrowserEvent('focus-on-quantity-field');
    }

    public function clearUpdateData()
    {
        $this->key = 0;
        $this->new_return_quantity = "";
        $this->stock_id = 0;
    }
    public function clearData()
    {
        $this->key = 0;
        $this->new_return_quantity = "";
        $this->stock_id = 0;
        $this->visibleReturnArea = false;
        $this->return_select_item = "";
        $this->return_search_item = "";
    }

    public function clearFilter()
    {
        $this->return_select_category = "";
        $this->return_select_brand = "";
        $this->return_select_item = "";
        $this->return_search_item = "";
    }

    public function clearReturn()
    {
        $this->stock_id = 0;
        $this->visibleReturnArea = false;
        $this->selected_stock_data = [];
        $this->new_return_quantity = "";
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

    public function fetchFilterData()
    {
        // for select filter
        $this->categories = CategoryModel::select('categories.*')->orderBy('categories.category', 'ASC')->get();
        $this->brands = BrandModel::select('brands.*')->orderBy('brands.brand', 'ASC')->get();

        if ($this->return_select_category && !$this->return_select_brand) {
            $this->items = DB::table('items')
                ->select('items.*')
                ->where('items.category_id', '=', $this->return_select_category)
                ->orderBy('items.item_name', 'ASC')
                ->get();

        } elseif ($this->return_select_brand && !$this->return_select_category) {
            $this->items = DB::table('items')
                ->select('items.*')
                ->where('items.brand_id', '=', $this->return_select_brand)
                ->orderBy('items.item_name', 'ASC')
                ->get();

        } elseif ($this->return_select_category && $this->return_select_brand) {
            $this->items = DB::table('items')
                ->select('items.*')
                ->where('items.category_id', '=', $this->return_select_category)
                ->where('items.brand_id', '=', $this->return_select_brand)
                ->orderBy('items.item_name', 'ASC')
                ->get();

        } else {
            $this->items = [];
        }

        /////for stock data
        if ($this->return_select_category || $this->return_select_brand || $this->return_search_item) {
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
                ->whereNotIn('branch_stores.quantity', [0])
                ->latest()
                ->skip($this->stock_page_count)
                ->limit(5);

            $this->stock_data = $query->when($this->return_search_item, function ($query) {
                $query->where(function ($query) {
                    $query->where('invoice_items.barcode', '=', $this->return_search_item)
                        ->orWhere('items.item_name', 'LIKE', "%{$this->return_search_item}%");
                });

            })->when($this->return_select_category, function ($query) {
                $query->where('items.category_id', $this->return_select_category);

            })->when($this->return_select_brand, function ($query) {
                $query->where('items.brand_id', $this->return_select_brand);

            })->when($this->return_select_item, function ($query) {
                $query->where('items.id', $this->return_select_item);

            })->get();

        } else {
            $this->stock_data = [];
        }

        // for selected stock detail
        if ($this->stock_id != 0) {
            $this->selected_stock_data = DB::table('branch_stores')
                ->select('branch_stores.*', 'items.item_name')
                ->join('invoice_items', 'branch_stores.invoice_items_id', '=', 'invoice_items.id')
                ->join('items', 'invoice_items.item_id', '=', 'items.id')
                ->where('branch_stores.id', $this->stock_id)
                ->get();
        } else {
            $this->selected_stock_data = [];
        }
    }
    public function fetchData()
    {
        //for retured item data
        $this->return_items_data = DB::table('invoice_returns')
            ->select('invoice_returns.*', 'items.item_name', 'invoice_items.barcode', 'branch_stores.quantity as stock_quantity')
            ->join('branch_stores', 'invoice_returns.branch_store_id', 'branch_stores.id')
            ->join('invoice_items', 'invoice_returns.invoice_item_id', 'invoice_items.id')
            ->join('items', 'invoice_items.item_id', 'items.id')
            ->where('invoice_returns.invoice_id', $this->invoiceId)
            ->where('invoice_returns.property_id', $this->propertyId)
            ->where('items.item_name', 'LIKE', '%' . $this->searchKey . '%')
            ->orWhere('invoice_items.barcode', '=', $this->searchKey)
            ->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        // for show total data count
        $this->data_count = DB::table('invoice_returns')
            ->count('id');
    }

    public function render()
    {
        $this->fetchFilterData();
        $this->fetchData();
        $this->pageAction();
        return view('livewire.invoice-return')->layout('layouts.master');
    }
}
