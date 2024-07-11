<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use App\Models\BankHistory;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BranchStore as BranchStoreModel;
use App\Models\CashBalance;
use App\Models\InvoiceItem as InvoiceItemModel;
use App\Models\TemOrder as TemOrderModel;
use App\Models\Customer as CustomerModel;
use App\Models\TempBill as TempBillModel;
use App\Models\CashInHistory as CashInHistoryModel;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;

class Sale extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];
    public $search_item_data = [];
    public $branch_data = [];
    public $bill_item = [];
    public $searchKey;
    public $key = 0;
    public $message = "";
    public $tab = 1;
    //new variable data management
    public $new_brand = "";
    public $new_sel_quantity = 1;
    public $new_sel_price = 0;
    public $new_branch_quantity = 0;
    public $active_panal = false;
    public $active_window = false;
    //cal
    public $item_total = 0;
    public $buy_total = 0;
    //open model insert
    public $customers = [];
    public $customer_search;
    public $new_customer_id = 0;
    public $customer_data;
    //new variable data management
    public $new_name = "";
    public $new_contact;
    public $propery_data;
    public $selected_item_id;
    public $user_name;
    public $tem_bill_id;
    public $is_credit_data = 0;
    public $available_credit = 0;
    public $quantity_error = false;
    public $customer_error = false;
    public $payment_error = false;
    public $show_credit_customer = false;
    // payment secrion
    public $payment_option = "Cash";
    public $new_paid_cash = 0;
    public $new_bank;
    public $new_card_description;
    public $globle_tem_order_total = 0; //item total
    public $banks = [];
    public $final_bill_data;
    public $printBill = [];
    public $delete_id = 0;

    public $counter_id;

    protected $listeners = ['openModel', 'openModelPayment', 'clearCustomerData', 'closeModelPayment', 'closeModel', 'searchClear', 'goBack'];

    public function mount($id)
    {
        $this->tem_bill_id = $id;

        if (session('counter_id')) {
            $this->counter_id = session()->get('counter_id');
        }
    }

    public function openModel()
    {
        $this->customer_error = false;
        $this->active_panal = false;
        $this->dispatchBrowserEvent('insert-show-form');
        $this->dispatchBrowserEvent('focus-on-customer-field');
    }

    //close model insert
    public function closeModel()
    {
        $this->dispatchBrowserEvent('insert-hide-form');
    }

    public function goBack()
    {
        return redirect('/sales-view');
    }

    //open model delete
    public function deleteOpenModel($id)
    {
        $this->delete_id = $id;
        $this->dispatchBrowserEvent('delete-show-form');
    }

    public function activeView()
    {
        $this->active_panal = !$this->active_panal;
    }

    public function activeChange()
    {
        $this->active_window = !$this->active_window;
    }

    //close model close
    public function deleteCloseModel()
    {
        $this->dispatchBrowserEvent('delete-hide-form');
    }

    public function searchClear()
    {
        $this->searchKey = null;
        $this->new_sel_price = 0;
        $this->list_data = [];
        $this->selected_item_id = 0;
        $this->quantity_error = false;
        $this->dispatchBrowserEvent('change-focus-other-field');
    }

    #customer function
    public function selectedCustomer()
    {
        if ($this->new_customer_id != 0) {
            $this->customer_data = CustomerModel::find($this->new_customer_id);
        } else {
            $this->customer_data = null;
        }
    }

    // open model payment
    public function openModelPayment()
    {
        $this->payment_error = false;
        $this->new_paid_cash = $this->item_total;

        $this->dispatchBrowserEvent('insert-show-form-payment');
        $this->dispatchBrowserEvent('focus-on-payment-field');
    }

    // open model payment
    public function closeModelPayment()
    {
        $this->dispatchBrowserEvent('insert-hide-form-payment');
    }

    // pagination purpose code
    public $item_count = 0;
    public function add()
    {
        $this->item_count = $this->item_count + 10;
    }

    public function les()
    {
        $this->item_count = $this->item_count - 10;
    }

    //fetch data from db
    public function fetchData()
    {
        $this->is_credit_data = CustomerModel::where('id', $this->new_customer_id)
            ->value('is_credit');
        $this->user_name = DB::table('users')
            ->select('users.name')
            ->where('users.id', Auth::user()->id)
            ->value('name');

        # add item data
        $this->propery_data = Property::find(Auth::user()->property_id);
        $this->banks = Bank::all();

        $this->bill_item = DB::table('tem_orders')
            ->select('tem_orders.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
            ->join('invoice_items', 'tem_orders.invoice_items_id', '=', 'invoice_items.id')
            ->join('items', 'items.id', '=', 'invoice_items.item_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
            ->where('tem_orders.auth_id', '=', Auth::user()->id)
            ->where('tem_orders.temp_bill_id', '=', $this->tem_bill_id)
            ->latest()
            ->get();

        $item_total = 0;
        $buy_total = 0;

        foreach ($this->bill_item as $row) {
            $item_total = $item_total + ($row->quantity * $row->sell_price);
            $buy_total = $buy_total + ($row->quantity * $row->buy_price);
        }

        $this->item_total = $item_total;
        $this->buy_total = $buy_total;

        if ($this->final_bill_data) {

            $this->printBill = DB::table('bill_items')
                ->select('bill_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
                ->join('invoice_items', 'bill_items.invoice_items_id', '=', 'invoice_items.id')
                ->join('items', 'items.id', '=', 'invoice_items.item_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->where('bill_items.bill_id', '=', $this->final_bill_data->id)
                ->where('bill_items.auth_id', '=', Auth::user()->id)
                ->latest()
                ->get();
        }

        #if search active
        if ($this->searchKey) {
            $this->search_item_data = DB::table('invoice_items')
                ->select(
                    'invoice_items.*',
                    'items.item_name',
                    'items.measure',
                    'categories.category as category_name',
                    'brands.brand as brand_name',
                    'measurements.measurement as measurement_name',
                    'branch_stores.quantity as stock_quantity'
                )
                ->join('items', 'items.id', '=', 'invoice_items.item_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->leftJoin('branch_stores', 'branch_stores.invoice_items_id', 'invoice_items.id')
                ->where('items.item_name', 'LIKE', '%' . $this->searchKey . '%')
                ->where('branch_stores.quantity', '!=', 0)
                ->skip($this->item_count)
                ->limit(10)
                ->get();

            if ($this->selected_item_id != 0) {
                $this->list_data = DB::table('invoice_items')
                    ->select('invoice_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
                    ->join('items', 'items.id', '=', 'invoice_items.item_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                    ->where('invoice_items.id', '=', $this->selected_item_id)
                    ->latest()
                    ->get();

                $this->branch_data = DB::table('branch_stores')
                    ->select('branch_stores.*', 'invoice_items.barcode', DB::raw('SUM(branch_stores.quantity) as branch_total'))
                    ->join('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
                    ->where('branch_stores.quantity', '!=', 0)
                    ->where('invoice_items.id', '=', $this->selected_item_id)
                    ->where('branch_stores.property_id', '=', Auth::user()->property_id)
                    ->groupBy('branch_stores.invoice_items_id')
                    ->get();
            } else {

                if (strpos($this->searchKey, '0000') === 0) {
                    $searchKey = substr($this->searchKey, 0, 9);

                    $this->list_data = DB::table('invoice_items')
                        ->select('invoice_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
                        ->join('items', 'items.id', '=', 'invoice_items.item_id')
                        ->join('categories', 'categories.id', '=', 'items.category_id')
                        ->join('brands', 'brands.id', '=', 'items.brand_id')
                        ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                        ->where('invoice_items.barcode', '=', $searchKey)
                        ->latest()
                        ->get();

                    $lastFiveDigits = substr($this->searchKey, -5);

                    $quantity = intval($lastFiveDigits) / 1000;

                    $this->new_sel_quantity = $quantity;

                    $this->branch_data = DB::table('branch_stores')
                        ->select('branch_stores.*', 'invoice_items.barcode', DB::raw('SUM(branch_stores.quantity) as branch_total'))
                        ->join('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
                        ->where('branch_stores.quantity', '!=', 0)
                        ->where('invoice_items.barcode', '=', $searchKey)
                        ->where('branch_stores.property_id', '=', Auth::user()->property_id)
                        ->groupBy('branch_stores.invoice_items_id')
                        ->get();

                    // focus on the quantity input field
                    $this->dispatchBrowserEvent('focus-on-add-to-bill-field');
                } else {
                    $this->list_data = DB::table('invoice_items')
                        ->select('invoice_items.*', 'items.item_name', 'items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
                        ->join('items', 'items.id', '=', 'invoice_items.item_id')
                        ->join('categories', 'categories.id', '=', 'items.category_id')
                        ->join('brands', 'brands.id', '=', 'items.brand_id')
                        ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
                        ->where('invoice_items.barcode', '=', $this->searchKey)
                        ->latest()
                        ->get();

                    $this->branch_data = DB::table('branch_stores')
                        ->select('branch_stores.*', 'invoice_items.barcode', DB::raw('SUM(branch_stores.quantity) as branch_total'))
                        ->join('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id')
                        ->where('branch_stores.quantity', '!=', 0)
                        ->where('invoice_items.barcode', '=', $this->searchKey)
                        ->where('branch_stores.property_id', '=', Auth::user()->property_id)
                        ->groupBy('branch_stores.invoice_items_id')
                        ->get();

                    // focus on the quantity input field
                    $this->dispatchBrowserEvent('focus-on-add-to-bill-field');
                }
            }

            if (sizeof($this->list_data) == 0) {
                $this->list_data = [];
                $this->branch_data = [];
            }
        } else {
            $this->quantity_error = false;
            $this->list_data = [];
            $this->branch_data = [];
            $this->search_item_data = [];
        }
    }

    // insert and update data here
    public function addToTemBIll()
    {
        $this->quantity_error = true;

        $this->fetchData();
        //here update data
        if (sizeOf($this->branch_data) != 0) {
            //validate data

            if ($this->new_sel_price == 0) {
                $this->validate(
                    [
                        'new_sel_quantity' => 'required|numeric|min:0|max:' . ($this->branch_data[0]->quantity),
                        'new_sel_price' => 'required|numeric|min:0|'
                    ],
                    [
                        'new_sel_quantity.required' => 'Quantity is required',
                        'new_sel_quantity.min' => 'The quantity must be at least 1.',
                        'new_sel_quantity.max' => 'The Selling quantity must not be greater than available quantity.',
                    ]
                );
            } else {
                $this->validate(
                    [
                        'new_sel_quantity' => 'required|numeric|min:0|max:' . ($this->branch_data[0]->quantity),
                        'new_sel_price' => 'required|numeric|'
                    ],
                    [
                        'new_sel_quantity.required' => 'Quantity is required',
                        'new_sel_quantity.min' => 'The quantity must be at least 1.',
                        'new_sel_quantity.max' => 'The Selling quantity must not be greater than available quantity.',
                    ]
                );
            }

            // check orderl olredy exit
            $tem_order = DB::table('tem_orders')
                ->select('tem_orders.*')
                ->where('tem_orders.branch_store_id', '=', $this->branch_data[0]->id)
                ->where('auth_id', '=', Auth::user()->id)
                ->where('temp_bill_id', '=', $this->tem_bill_id)
                ->get();

            if (sizeof($tem_order) != 0) {
                // update tem order data
                $temdata = TemOrderModel::find($tem_order[0]->id);
                $temdata->quantity = $temdata->quantity + $this->new_sel_quantity;
                if ($this->new_sel_price == 0) {
                    $temdata->sell_price = $this->list_data[0]->sell;
                } else {
                    $temdata->sell_price = $this->new_sel_price;
                }

                $temdata->buy_price = $this->list_data[0]->buy;
                $temdata->discount = $this->list_data[0]->sell - $this->new_sel_price;
                $temdata->save();

                //updte quantity in branch store when sale
                $data = BranchStoreModel::find($this->branch_data[0]->id);
                $data->quantity = $data->quantity - $this->new_sel_quantity;
                $data->save();

            } else {
                // insert tem oreder data
                $temdata = new TemOrderModel();
                $temdata->quantity = $temdata->quantity + $this->new_sel_quantity;
                if ($this->new_sel_price == 0) {
                    $temdata->sell_price = $this->list_data[0]->sell;
                } else {
                    $temdata->sell_price = $this->new_sel_price;
                }
                $temdata->buy_price = $this->list_data[0]->buy;
                $temdata->discount = $this->list_data[0]->sell - $this->new_sel_price;
                $temdata->invoice_items_id = $this->branch_data[0]->invoice_items_id;
                $temdata->auth_id = Auth::user()->id;
                $temdata->branch_store_id = $this->branch_data[0]->id;
                $temdata->property_id = Auth::user()->property_id;
                $temdata->temp_bill_id = $this->tem_bill_id;
                $temdata->save();

                //updte quantity in branch store when sale
                $data = BranchStoreModel::find($this->branch_data[0]->id);
                $data->quantity = $data->quantity - $this->new_sel_quantity;
                $data->save();
            }
            //show success message

            session()->flash('main_message', 'Item(s) Added Successfuly!');
            $this->searchClear();
            //clear data
            $this->clearData();
        }
    }

    public function doBill()
    {
        $this->payment_error = true;

        if (($this->payment_option == 'Cash')) {
            $this->validate(
                [
                    'new_paid_cash' => 'required|numeric|min:' . ($this->item_total)
                ],
                [
                    'new_paid_cash.min' => "Payment amount should not be less than " . ($this->item_total) . "."
                ]
            );
        }

        if (($this->payment_option == 'Card')) {
            $this->validate(
                [
                    'new_bank' => 'required'
                ]
            );
        }

        // store bill data
        $bill_data = new Bill();
        $bill_data->amount = $this->item_total;
        $bill_data->buy_total = $this->buy_total;
        $bill_data->auth_id = Auth::user()->id;
        $bill_data->date = date('Y-m-d');
        $bill_data->property_id = Auth::user()->property_id;
        $bill_data->counter_id = $this->counter_id;
        $bill_data->bank_id = $this->new_bank;
        $bill_data->payment_type = $this->payment_option;
        $bill_data->customer_id = $this->new_customer_id;
        $bill_data->card_description = $this->new_card_description;
        $bill_data->discount = 0;
        if (($this->new_customer_id != 0) && ($this->payment_option == 'Credit')) {
            $bill_data->credit_amount = $this->item_total;
        }
        $bill_data->paid_amount = $this->new_paid_cash;
        $bill_data->balance = $this->new_paid_cash - $this->item_total;
        $bill_data->balance = ($this->payment_option == 'Credit') ? 0 : ($this->new_paid_cash - $this->item_total);
        $bill_data->save();

        if (($this->new_customer_id != 0) && ($this->payment_option == 'Credit')) {
            $credit_customer_data = CustomerModel::find($this->new_customer_id);
            $credit_customer_data->cridit = $credit_customer_data->cridit + $this->item_total;
            $credit_customer_data->save();
        }

        $this->final_bill_data = $bill_data;

        // update bill items
        // if do bank payment
        if ($this->payment_option == 'Card') {
            $bank_history = new BankHistory();
            $bank_history->auth_id = Auth::user()->id;
            $bank_history->deposit_amount = $this->globle_tem_order_total;
            $bank_history->bank_id = $this->new_bank;
            $bank_history->discription = "Invoice number:" . $this->final_bill_data->id . " " . $this->new_card_description;
            $bank_history->date = date('Y-m-d');
            $bank_history->property_id = Auth::user()->property_id;
            $bank_history->save();

            //  bank data balance

            $bank_update = Bank::find($this->new_bank);
            $bank_update->opening_balance = $bank_update->opening_balance + $this->item_total;
            $bank_update->save();

            if ($this->new_customer_id) {
                // credit
                $cus = CustomerModel::find($this->new_customer_id);
                $cus->loyality = $cus->loyality + ($this->item_total * 0.2 / 100);
                $cus->save();
            }
        } elseif ($this->payment_option == 'Cash') {
            // if credit bill
            // if ($this->new_customer_id) {
            //     // credit
            //     if ($this->new_paid_cash - $this->item_total < 0) {
            //         //customer credit bill
            //         // $cus = CustomerModel::find($this->new_customer_id);
            //         // $cus->cridit = $cus->cridit + $this->new_paid_cash - $this->item_total;
            //         // $cus->save();

            //         $new_cash = new CashBalance();
            //         $new_cash->amount = $this->new_paid_cash;
            //         $new_cash->property_id = Auth::user()->property_id;
            //         $new_cash->save();

            //     } else {
            //         $cus = CustomerModel::find($this->new_customer_id);
            //         $cus->loyality = $cus->loyality + ($this->item_total * 0.2 / 100);
            //         $cus->save();
            //     }
            // } else {
            //     $cash_data = CashBalance::where('property_id', '=', Auth::user()->property_id)->first();
            //     if ($cash_data) {
            //         $cash_data->amount = $cash_data->amount + $this->item_total;
            //         $cash_data->save();
            //     } else {
            //         $new_cash = new CashBalance();
            //         $new_cash->amount = $this->item_total;
            //         $new_cash->property_id = Auth::user()->property_id;
            //         $new_cash->save();
            //     }
            // }

            //////////
            if ($this->new_customer_id) {
                $cus = CustomerModel::find($this->new_customer_id);
                $cus->loyality = $cus->loyality + ($this->item_total * 0.2 / 100);
                $cus->save();
            }

            $cash_data = CashBalance::where('property_id', '=', Auth::user()->property_id)
                ->where('counter_id', '=', $this->counter_id)->first();
            $cash_data->amount = $cash_data->amount + $this->item_total;
            $cash_data->save();

            ///for sales cash in
            $cash_in = CashInHistoryModel::where('property_id', '=', Auth::user()->property_id)
                ->where('cash_in_type', '=', "Cash Sales")
                ->where('date', '=', date('Y-m-d'))
                ->where('staff_id', '=', Auth::user()->id)
                ->where('counter_id', '=', $this->counter_id)
                ->first();

            if ($cash_in) {
                $cash_in->amount = $cash_in->amount + $this->item_total;
                $cash_in->save();
            } else {
                $new_cash_in = new CashInHistoryModel();
                $new_cash_in->cash_in_type = "Cash Sales";
                $new_cash_in->amount = $this->item_total;
                $new_cash_in->date = date('Y-m-d');
                $new_cash_in->property_id = Auth::user()->property_id;
                $new_cash_in->staff_id = Auth::user()->id;
                $new_cash_in->counter_id = $this->counter_id;
                $new_cash_in->save();
            }

            //////////
        }

        // trnfer data into bill items and clear tem order
        for ($i = 0; $i < sizeof($this->bill_item); $i++) {
            $info = new BillItem();
            $info->bill_id = $bill_data->id;
            $info->sell_price = $this->bill_item[$i]['sell_price'];
            $info->buy_price = $this->bill_item[$i]['buy_price'];
            $info->quantity = $this->bill_item[$i]['quantity'];
            $info->discount = $this->bill_item[$i]['discount'];
            $info->invoice_items_id = $this->bill_item[$i]['invoice_items_id'];
            $info->auth_id = Auth::user()->id;
            $info->branch_store_id = $this->bill_item[$i]['branch_store_id'];
            $info->property_id = Auth::user()->property_id;

            if ($this->bill_item[$i]['property_id'] != 0) {
                $info->property_id = $this->bill_item[$i]['property_id'];
            }
            $info->save();

            $delete = TemOrderModel::find($this->bill_item[$i]['id']);
            $delete->delete();
        }

        // fetch bill data
        $this->new_paid_cash = 0;
        // $this->new_customer_id=0;

        $this->dispatchBrowserEvent('do-print');

        $tempBill = TempBillModel::find($this->tem_bill_id);
        $tempBill->delete();

        $this->redirectWithDelay('/sales-view');
    }

    private function redirectWithDelay($url, $delay = 3000)
    {
        $this->emit('redirect', $url, $delay);
    }

    public function doZero()
    {
        $this->new_paid_cash = 0;
    }

    public function payOptions($payment)
    {
        if ($payment == 'Card') {
            $this->new_paid_cash = $this->item_total;
        } elseif ($payment == 'Credit') {
            $this->new_paid_cash = 0;
        }
        $this->payment_option = $payment;
    }

    public function setItem($id)
    {
        $this->selected_item_id = $id;
        $this->dispatchBrowserEvent('focus-on-add-to-bill-field');
    }

    public function customerFind()
    {
        if ($this->customer_search) {
            $this->customers = CustomerModel::where('customer_name', 'LIKE', "%{$this->customer_search}%")
                ->orWhere('tp', '=', $this->customer_search)
                ->get();

            if (sizeof($this->customers) == 1) {
                $this->new_customer_id = $this->customers[0]->id;
                $this->selectedCustomer();
            }

        } elseif ($this->show_credit_customer == true) {
            $this->customers = CustomerModel::where('is_credit', 1)
                ->get();
        } else {
            $this->customers = CustomerModel::all();
        }

        $credit_limit = DB::table('customers')
            ->select('customers.credit_limit')
            ->where('customers.id', $this->new_customer_id)
            ->value('credit_limit');

        $credit_spent = DB::table('customers')
            ->select('customers.cridit')
            ->where('customers.id', $this->new_customer_id)
            ->value('cridit');

        $received_credit = DB::table('customers')
            ->select('customers.received_credit')
            ->where('customers.id', $this->new_customer_id)
            ->value('received_credit');

        $this->available_credit = $credit_limit - ($credit_spent - $received_credit);
    }

    // insert and update data here
    public function saveData()
    {
        $this->customer_error = true;
        //validate data
        $this->validate(
            [
                'new_name' => 'required|max:255',
                'new_contact' => 'required|max:255|unique:customers,tp'
            ],
            [
                'new_contact.unique' => 'This contact number is already taken.'
            ]
        );
        // $bar = (Carbon::now()->timestamp) - 1600000000;
        //here insert data
        $data = new CustomerModel();
        $data->customer_name = $this->new_name;
        $data->tp = $this->new_contact;
        // $data->code=$bar;
        $data->save();


        $this->customer_search = $this->new_contact;
        // \Storage::disk('public')->put($bar.'.png', base64_decode(DNS1D::getBarcodePNG($bar, "I25")));
        //show success message
        session()->flash('message', 'Saved Successfully!');
        //clear data
        $this->clearNewCustomerData();
    }

    public function clearCustomerData()
    {
        $this->new_customer_id = 0;
        $this->customer_data = null;
        $this->customer_search = null;
    }

    // insert and update data here
    public function branchToMainTransfer()
    {
        $this->fetchData();
        //validate data
        $this->validate(
            [
                'new_branch_quantity' => 'required|numeric|min:1|max:' . ($this->branch_data[0]->quantity)
            ]
        );

        //here update data
        if (sizeOf($this->branch_data) != 0) {
            //update main store balance
            $main = InvoiceItemModel::find($this->list_data[0]->id);
            $main->quantity = $main->quantity + $this->new_branch_quantity;
            $main->save();

            //updte quantity in branch store
            $data = BranchStoreModel::find($this->branch_data[0]->id);
            $data->quantity = $data->quantity - $this->new_branch_quantity;
            $data->save();

            //show success message
            session()->flash('branch_message', ' Stock Transfered Successfuly!');
            //clear data
            $this->clearData();
        }
        $this->searchClear();
    }

    #delete Data
    public function deleteRecord($id)
    {
        $this->delete_id = $id;

        if ($this->delete_id != 0) {
            $delete_data = TemOrderModel::find($this->delete_id);
            //find branch store
            $branch_item = BranchStoreModel::where('invoice_items_id', '=', $delete_data->invoice_items_id)->first();
            $branch_item->quantity = $branch_item->quantity + $delete_data->quantity;
            $branch_item->save();

            $delete_data->delete();
            $this->dispatchBrowserEvent('change-focus-other-field');
        }
    }

    //clear data
    public function clearData()
    {
        # emty field
        $this->key = 0;
        $this->new_sel_quantity = 1;
    }

    public function clearNewCustomerData()
    {
        $this->new_contact = '';
        $this->new_name = '';
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
        $this->fetchData();
        $this->pageAction();
        $this->customerFind();
        return view('livewire.sale')->layout('layouts.master');
    }
}
