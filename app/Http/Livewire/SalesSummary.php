<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Bill as BillModel;
use App\Models\BillItem as BillItemModel;
use App\Models\Property as PropertyModel;
use App\Models\Customer as CustomerModel;
use App\Models\ReturnHistory as ReturnHistoryModel;
use App\Models\BranchStore as BranchStoreModel;
use App\Models\ExpenceType as ExpenceTypeModel;
use App\Models\Expence as ExpenceModel;
use App\Models\InvoiceItem as InvoiceItemModel;
use Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalesSummary extends Component
{
    public $page_action = [];
    public $start_date;
    public $end_date;

    public $showError = false;
    public $propertyId;

    public $property_name;

    // for filter
    public $filter_properties = [];
    public $select_property;

    public $returned_item_data = [];
    public $bill_item_data = [];
    public $bill_data = [];

    public $property_data = [];
    public $customer_data = [];
    public $view_id = 0;
    public $bill_property_id = 0;
    public $bill_customer_id;

    // for return
    public $return_id = 0;
    public $return_reason = "";
    public $return_quantity = 0;
    public $return_item_data = [];

    public $cashiersData = [];
    public $counterData = [];
    public $customerData = [];
    public $select_user = 0;
    public $select_counter = 0;
    public $select_customer = 0;
    public $select_payment_type;
    public $visibleReturnArea = false;
    public $counter_id;
    public $show_credit_customer = false;

    public function mount()
    {
        $this->propertyId = Auth::user()->property_id;
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');

        if (session('counter_id')) {
            $this->counter_id = session()->get('counter_id');
        }
    }

    public function openViewModel($id, $proID, $cusId)
    {
        $this->view_id = $id;
        $this->bill_property_id = $proID;
        $this->bill_customer_id = $cusId;

        $this->return_quantity = 0;
        $this->return_reason = null;
        $this->visibleReturnArea = false;
        $this->return_id = 0;

        $this->dispatchBrowserEvent('view-show-form');
    }

    public function viewCloseModel()
    {
        $this->view_id = 0;
        $this->return_quantity = 0;
        $this->return_reason = null;
        $this->visibleReturnArea = false;
        $this->return_id = 0;

        $this->dispatchBrowserEvent('view-hide-form');
    }

    public function openReturnArea($id)
    {
        $this->return_id = $id;
        $this->showError = false;

        $this->return_quantity = 0;
        $this->return_reason = null;
        $this->visibleReturnArea = true;
        $this->dispatchBrowserEvent('focus-on-quantity-field');
    }

    public function return()
    {
        $this->showError = true;
        if ($this->return_id != 0) {

            // Find the bill item and return
            $return = BillItemModel::find($this->return_id);

            // Validate data
            $rules = [
                'return_quantity' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:' . $return->quantity,
                ],
                'return_reason' => 'required|max:255',
            ];

            // Add custom messages for the validation
            $customMessages = [
                'return_quantity.required' => 'The return quantity is required.',
                'return_quantity.numeric' => 'The return quantity must be a numeric value.',
                'return_quantity.min' => 'The return quantity must be greater than 0.',
                'return_quantity.max' => 'The return quantity cannot exceed the available quantity.',
                'return_reason.required' => 'The return reason is required.',
                'return_reason.max' => 'The return reason must not exceed 255 characters.',
            ];

            $this->validate($rules, $customMessages);

            $branchStore = BranchStoreModel::where('invoice_items_id', $return->invoice_items_id)->first();

            if ($branchStore) {
                // Update the Bill Item
                $return->returned_quantity = $return->returned_quantity + $this->return_quantity;
                $return->quantity = $return->quantity - $this->return_quantity;
                $return->is_returned = 1;
                $return->return_reason = $this->return_reason;
                $return->save();

                // Update the Branch Store's Stock
                $branchStore->quantity += $this->return_quantity;
                $branchStore->save();
            }

            // Find the bill uand update the amount
            $bill = BillModel::find($return->bill_id);

            if ($bill) {
                $returned_quantity_value = $this->return_quantity * $return->sell_price;

                $bill->amount -= $returned_quantity_value;
                $bill->balance = $bill->paid_amount - $bill->amount;
                $bill->is_returned_bill = 1;
                $bill->save();
            }

            // // Find or create the expense type "Bill Return"
            // $expenseType = ExpenceTypeModel::firstOrCreate(['expence_type' => 'Bill Return']);

            $retuned_item_name = DB::table('bill_items')
                ->select('items.item_name')
                ->leftJoin('invoice_items', 'bill_items.invoice_items_id', '=', 'invoice_items.id')
                ->leftJoin('items', 'items.id', '=', 'invoice_items.item_id')
                ->where('bill_items.id', '=', $this->return_id)
                ->value('items.item_name');

            // // Create a new expense record in the Expence table
            // $expense = new ExpenceModel();
            // $expense->title = "Bill Return";
            // $expense->amount = $this->return_quantity * $return->sell_price;
            // $expense->description = $this->return_quantity . " " . $retuned_item_name . " - Returned from the bill, Invoice Number : " . str_pad($return->bill_id, 8, '0', STR_PAD_LEFT) . ", The reason for the return : " . $this->return_reason;
            // $expense->expence_date = date('Y-m-d');
            // $expense->user_id = Auth::user()->id;
            // $expense->expence_type_id = $expenseType->id;
            // $expense->property_id = Auth::user()->property_id;
            // $expense->save();

            // create the return history data
            $return_history = new ReturnHistoryModel();
            $return_history->bill_id = $return->bill_id;
            $return_history->date = date('Y-m-d');
            $return_history->time = now()->format('H:i:s');
            $return_history->return_history = $this->return_quantity . " " . $retuned_item_name . " - Returned from the bill, Invoice Number : " . str_pad($return->bill_id, 8, '0', STR_PAD_LEFT) . ", The reason for the return : " . $this->return_reason . ", The value for the returned item(s) : " . number_format($this->return_quantity * $return->sell_price, 2);
            $return_history->user_id = Auth::user()->id;
            $return_history->property_id = Auth::user()->property_id;
            $return_history->save();

            //show success message
            session()->flash('return_msg', 'Item returned and stock updated successfully!');

            // clear data
            $this->return_quantity = 0;
            $this->return_reason = null;
            $this->visibleReturnArea = false;
            $this->return_id = 0;
        }
    }

    public function closeReturnArea()
    {
        $this->return_id = 0;
        $this->visibleReturnArea = false;
        $this->return_item_data = [];
        $this->return_quantity = 0;
        $this->return_reason = null;
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

    public function clearFilter()
    {
        $this->select_user = 0;
        $this->select_payment_type = null;
        $this->select_counter = 0;
        $this->select_customer = 0;
        $this->show_credit_customer = false;
    }

    public function render()
    {
        $this->pageAction();

        $this->cashiersData = DB::table('users')
            ->select('users.id', 'users.name')
            ->where('user_type_id', '=', 5)
            ->get();

        $this->counterData = DB::table('counters')
            ->select('counters.id', 'counters.counter')
            ->where('counters.property_id', '=', $this->propertyId)
            ->get();

        $this->customerData = DB::table('customers')
            ->select('customers.*');

        if ($this->show_credit_customer == true) {
            $this->customerData = $this->customerData->where('customers.is_credit', 1);
        }

        if ($this->select_payment_type == "Credit") {
            $this->customerData = $this->customerData->where('customers.is_credit', 1);
        }

        $this->customerData = $this->customerData->latest()->get();

        // for sales summary
        // today sales
        $today_cash_sales = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Cash");
        if ($this->propertyId != 1) {
            $today_cash_sales = $today_cash_sales->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $today_cash_sales = $today_cash_sales->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $today_cash_sales = $today_cash_sales->where('property_id', $this->select_property);
        }
        $today_cash_sales = $today_cash_sales->sum('amount');


        $today_card_sales = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Card");
        if ($this->propertyId != 1) {
            $today_card_sales = $today_card_sales->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $today_card_sales = $today_card_sales->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $today_card_sales = $today_card_sales->where('property_id', $this->select_property);
        }
        $today_card_sales = $today_card_sales->sum('amount');

        $today_sales = $today_cash_sales + $today_card_sales;

        //////////////////////////

        // total sales
        $total_cash_sales = BillModel::where('payment_type', "Cash");
        if ($this->propertyId != 1) {
            $total_cash_sales = $total_cash_sales->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $total_cash_sales = $total_cash_sales->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $total_cash_sales = $total_cash_sales->where('property_id', $this->select_property);
        }
        $total_cash_sales = $total_cash_sales->sum('amount');


        $total_card_sales = BillModel::where('payment_type', "Card");
        if ($this->propertyId != 1) {
            $total_card_sales = $total_card_sales->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $total_card_sales = $total_card_sales->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $total_card_sales = $total_card_sales->where('property_id', $this->select_property);
        }
        $total_card_sales = $total_card_sales->sum('amount');

        $total_sales = $total_cash_sales + $total_card_sales;

        //////////////////////////

        // today bills
        $today_cash_bills = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Cash");
        if ($this->propertyId != 1) {
            $today_cash_bills = $today_cash_bills->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $today_cash_bills = $today_cash_bills->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $today_cash_bills = $today_cash_bills->where('property_id', $this->select_property);
        }
        $today_cash_bills = $today_cash_bills->count('id');


        $today_card_bills = BillModel::where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'), Carbon::now()->format('Y-m-d'))
            ->where('payment_type', "Card");
        if ($this->propertyId != 1) {
            $today_card_bills = $today_card_bills->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $today_card_bills = $today_card_bills->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $today_card_bills = $today_card_bills->where('property_id', $this->select_property);
        }
        $today_card_bills = $today_card_bills->count('id');

        $today_bills = $today_cash_bills + $today_card_bills;

        //////////////////////////

        // total bills
        $total_cash_bills = BillModel::where('payment_type', "Cash");
        if ($this->propertyId != 1) {
            $total_cash_bills = $total_cash_bills->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $total_cash_bills = $total_cash_bills->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $total_cash_bills = $total_cash_bills->where('property_id', $this->select_property);
        }
        $total_cash_bills = $total_cash_bills->count('id');


        $total_card_bills = BillModel::where('payment_type', "Card");
        if ($this->propertyId != 1) {
            $total_card_bills = $total_card_bills->where('property_id', $this->propertyId);
        }

        if (Auth::user()->user_type_id != 2) {
            $total_card_bills = $total_card_bills->where('auth_id', Auth::user()->id);
        }

        if ($this->select_property) {
            $total_card_bills = $total_card_bills->where('property_id', $this->select_property);
        }
        $total_card_bills = $total_card_bills->count('id');

        $total_bills = $total_cash_bills + $total_card_bills;

        //////////////////////////

        $this->filter_properties = DB::table('properties')
            ->select('id', 'property_name')
            ->where('id', '!=', 1)
            ->get();

        if ($this->select_property) {
            $this->property_name = DB::table('properties')
                ->select('properties.property_name')
                ->where('id', $this->select_property)
                ->value('properties.property_name');
        } else {
            $this->property_name = DB::table('properties')
                ->select('properties.property_name')
                ->where('id', $this->propertyId)
                ->value('properties.property_name');
        }

        if ($this->start_date && $this->end_date) {
            $list_data = DB::table('bills')
                ->select('bills.*', 'properties.property_name')
                ->leftJoin('properties', 'bills.property_id', '=', 'properties.id')
                ->leftJoin('customers', 'bills.customer_id', '=', 'customers.id')
                ->where('bills.date', '>=', $this->start_date)
                ->where('bills.date', '<=', $this->end_date);
            if ($this->propertyId != 1) {
                $list_data = $list_data->where('bills.property_id', $this->propertyId);
            }

            if (Auth::user()->user_type_id != 2) {
                $list_data = $list_data->where('bills.auth_id', Auth::user()->id);
            }

            if ($this->select_user != 0) {
                $list_data = $list_data->where('bills.auth_id', $this->select_user);
            }

            if ($this->select_payment_type) {
                $list_data = $list_data->where('bills.payment_type', $this->select_payment_type);
            }

            if ($this->select_counter) {
                $list_data = $list_data->where('bills.counter_id', $this->select_counter);
            }

            if (Auth::user()->user_type_id == 5 && $this->counter_id) {
                $list_data = $list_data->where('bills.counter_id', $this->counter_id);
            }

            if ($this->select_customer) {
                $list_data = $list_data->where('bills.customer_id', $this->select_customer);
            }

            if ($this->select_property) {
                $list_data = $list_data->where('bills.property_id', $this->select_property);
            }
            $list_data = $list_data->latest()->get();

        } else {
            $list_data = [];
        }

        if ($this->view_id != 0) {
            $this->bill_data = DB::table('bills')
                ->select('bills.id as invoice_number', 'bills.date', 'bills.paid_amount', 'bills.balance', 'bills.payment_type', 'bills.created_at', 'users.name as user_name', 'bills.is_returned_bill')
                ->leftJoin('users', 'bills.auth_id', 'users.id')
                ->where('bills.id', '=', $this->view_id)
                ->get();

            if (($this->bill_data)) {
                $this->bill_item_data = DB::table('bill_items')
                    ->select('bill_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
                    ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
                    ->leftJoin('invoice_items', 'bill_items.invoice_items_id', '=', 'invoice_items.id')
                    ->leftJoin('items', 'items.id', '=', 'invoice_items.item_id')
                    ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
                    ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
                    ->leftJoin('measurements', 'measurements.id', '=', 'items.measurement_id')
                    ->where('bill_items.bill_id', '=', $this->view_id)
                    // ->where('bill_items.auth_id', '=', Auth::user()->id)
                    ->latest()
                    ->get();

                $this->returned_item_data = $this->bill_item_data->filter(function ($item) {
                    return $item->is_returned == 1;
                });
            }
        } else {
            $this->bill_data = [];
            $this->bill_item_data = [];
            $this->returned_item_data = [];
        }

        if ($this->return_id != 0) {
            $this->return_item_data = DB::table('bill_items')
                ->select('bill_items.quantity as bill_item_quantity', 'items.item_name')
                ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
                ->leftJoin('invoice_items', 'bill_items.invoice_items_id', '=', 'invoice_items.id')
                ->leftJoin('items', 'items.id', '=', 'invoice_items.item_id')
                ->where('bill_items.id', '=', $this->return_id)
                ->get();
        } else {
            $this->return_item_data = [];
        }

        if ($this->bill_property_id != 0) {
            $this->property_data = PropertyModel::select('properties.*')
                ->where('properties.id', $this->bill_property_id)
                ->get();
        }

        if ($this->bill_customer_id != null) {
            $this->customer_data = CustomerModel::select('customers.*')
                ->where('customers.id', $this->bill_customer_id)
                ->get();
        } else {
            $this->customer_data = null;
        }

        return view(
            'livewire.sales-summary',
            compact(
                'list_data',
                'today_cash_sales',
                'today_card_sales',
                'today_sales',
                'total_cash_sales',
                'total_card_sales',
                'total_sales',
                'today_cash_bills',
                'today_card_bills',
                'today_bills',
                'total_cash_bills',
                'total_card_bills',
                'total_bills'
            )
        )
            ->layout('layouts.master');
    }
}