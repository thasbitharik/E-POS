<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item as ItemModel;
use App\Models\Category as CategoryModel;
use App\Models\Brand as BrandModel;
use App\Models\Measurement as MeasurementModel;
use App\Models\Property as PropertyModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Bill as BillModel;
use Carbon\Carbon;
use Route;

class DashboardPage extends Component
{
    public $searchKey;
    public $propertyId;
    public $userId;
    public $userTypeId;
    public $filter_categories = [];
    public $filter_brands = [];
    public $select_category;
    public $select_brand;

    public $propertyData = [];
    public $userName;
    public $greeting;

    public $select_user = 0;
    public $userData = [];

    public $showFilterArea = false;

    public function mount()
    {
        $this->propertyId = Auth::user()->property_id;
        $this->userId = Auth::user()->id;
        $this->userTypeId = Auth::user()->user_type_id;
    }
    public function toggleFilterArea()
    {
        $this->showFilterArea = !$this->showFilterArea;
    }

    public function clearSelection()
    {
        $this->select_category = "";
        $this->select_brand = "";
        $this->searchKey = "";
    }

    public function showGreetings()
    {
        $hour = date('G');

        if ($hour >= 5 && $hour < 12) {
            $this->greeting = 'Good morning...';
        } elseif ($hour >= 12 && $hour < 15) {
            $this->greeting = 'Good afternoon...';
        } elseif ($hour >= 15 && $hour < 19) {
            $this->greeting = 'Good evening...';
        } elseif ($hour >= 19 && $hour < 24) {
            $this->greeting = 'Good night...';
        }
    }

    public function render()
    {
        $this->showGreetings();

        $this->filter_categories = DB::table('categories')->select('id', 'category')->orderBy('category', 'asc')->get();
        $this->filter_brands = DB::table('brands')->select('id', 'brand')->orderBy('brand', 'asc')->get();

        $this->propertyData = DB::table('properties')
            ->select('properties.property_name', 'properties.logo')
            ->where('properties.id', '=', $this->propertyId)
            ->get();

        $this->userName = DB::table('users')
            ->select('users.name')
            ->where('users.id', '=', $this->userId)
            ->value('name');

        if ($this->select_brand || $this->select_category || $this->searchKey) {
            $list_data = DB::table('branch_stores')
                ->select('branch_stores.*', 'categories.category as category_name', 'brands.brand as brand_name', 'items.id', 'items.item_name', 'invoice_items.id', 'invoice_items.sell as sell_price', 'invoice_items.barcode', 'invoice_items.item_id', DB::raw('SUM(branch_stores.transfer_qty) as total_transfer'), DB::raw('SUM(branch_stores.quantity) as stock_qty'), DB::raw('SUM(invoice_items.quantity) as main_store_qty'))
                ->leftJoin('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
                ->leftJoin('items', 'invoice_items.item_id', '=', 'items.id')
                ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
                ->groupBy('invoice_items.item_id')
                ->where('branch_stores.property_id', '=', $this->propertyId);
            if ($this->select_category) {
                $list_data = $list_data->where('items.category_id', '=', $this->select_category);
            }
            if ($this->select_brand) {
                $list_data = $list_data->where('items.brand_id', '=', $this->select_brand);
            }
            if ($this->searchKey) {
                $list_data = $list_data->where('items.item_name', 'LIKE', '%' . $this->searchKey . '%')
                    ->orWhere('invoice_items.barcode', 'LIKE', '%' . $this->searchKey . '%');
            }
            $list_data = $list_data->take(10)->latest()->get();
        } else {
            $list_data = [];
        }

        $this->userData = DB::table('users')
            ->select('users.id', 'users.name')
            ->where('user_type_id', '=', 5)
            ->get();

        /////////////////////////

        //for sales summary
        // today sales
        $today_cash_sales = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Cash")
            ->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $today_cash_sales = $today_cash_sales->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $today_cash_sales = $today_cash_sales->where('auth_id', $this->select_user);
        }

        $today_cash_sales = $today_cash_sales->sum('amount');

        ////

        $today_card_sales = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Card")
            ->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $today_card_sales = $today_card_sales->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $today_card_sales = $today_card_sales->where('auth_id', $this->select_user);
        }

        $today_card_sales = $today_card_sales->sum('amount');

        ////

        $today_sales = $today_cash_sales + $today_card_sales;

        ////
        // today bills
        $today_cash_bills = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Cash")
            ->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $today_cash_bills = $today_cash_bills->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $today_cash_bills = $today_cash_bills->where('auth_id', $this->select_user);
        }
        $today_cash_bills = $today_cash_bills->count('id');

        ////

        $today_card_bills = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Card")
            ->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $today_card_bills = $today_card_bills->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $today_card_bills = $today_card_bills->where('auth_id', $this->select_user);
        }
        $today_card_bills = $today_card_bills->count('id');

        ////

        $today_bills = $today_cash_bills + $today_card_bills;

        ////
        // total sales
        $total_cash_sales = BillModel::where('payment_type', "Cash")->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $total_cash_sales = $total_cash_sales->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $total_cash_sales = $total_cash_sales->where('auth_id', $this->select_user);
        }
        $total_cash_sales = $total_cash_sales->sum('amount');

        ////

        $total_card_sales = BillModel::where('payment_type', "Card")->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $total_card_sales = $total_card_sales->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $total_card_sales = $total_card_sales->where('auth_id', $this->select_user);
        }
        $total_card_sales = $total_card_sales->sum('amount');

        ////

        $total_sales = $total_cash_sales + $total_card_sales;

        ////
        // total bills
        $total_cash_bills = BillModel::where('payment_type', "Cash")->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $total_cash_bills = $total_cash_bills->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $total_cash_bills = $total_cash_bills->where('auth_id', $this->select_user);
        }
        $total_cash_bills = $total_cash_bills->count('id');

        ////

        $total_card_bills = BillModel::where('payment_type', "Card")->where('property_id', $this->propertyId);

        if ($this->userTypeId != 2) {
            $total_card_bills = $total_card_bills->where('auth_id', $this->userId);
        }

        if ($this->select_user != 0) {
            $total_card_bills = $total_card_bills->where('auth_id', $this->select_user);
        }
        $total_card_bills = $total_card_bills->count('id');

        ////

        $total_bills = $total_cash_bills + $total_card_bills;

        ////

        return view(
            'livewire.dashboard-page',
            compact(
                'list_data',
                'today_cash_sales',
                'today_card_sales',
                'today_sales',
                'today_cash_bills',
                'today_card_bills',
                'today_bills',
                'total_cash_sales',
                'total_card_sales',
                'total_sales',
                'total_cash_bills',
                'total_card_bills',
                'total_bills'
            )
        )->layout('layouts.master');
    }
}