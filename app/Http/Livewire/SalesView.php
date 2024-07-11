<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use App\Models\TempBill as TempBillModel;
use App\Models\TemOrder as TempOrderModel;
use App\Models\Counter as CounterModel;
use App\Models\CashBalance as CashBalanceModel;
use App\Models\CounterActivityLog as CounterActivityLogModel;
use App\Models\CounterCashOutHistory as CounterCashOutHistoryModel;
use Illuminate\Support\Facades\DB;
use Route;
use Illuminate\Support\Facades\Auth;

class SalesView extends Component
{
    public $page_action = [];
    public $temp_bill_data = [];
    public $message = "";
    public $showError = false;

    public $data_count = 0;

    //for cash in
    public $cash_5000_Qty = 0;
    public $cash_1000_Qty = 0;
    public $cash_500_Qty = 0;
    public $cash_100_Qty = 0;
    public $cash_50_Qty = 0;
    public $cash_20_Qty = 0;
    public $cash_10_Qty = 0;
    public $cash_5_Qty = 0;
    public $cash_2_Qty = 0;
    public $cash_1_Qty = 0;

    public $cash_5000_Value = 0;
    public $cash_1000_Value = 0;
    public $cash_500_Value = 0;
    public $cash_100_Value = 0;
    public $cash_50_Value = 0;
    public $cash_20_Value = 0;
    public $cash_10_Value = 0;
    public $cash_5_Value = 0;
    public $cash_2_Value = 0;
    public $cash_1_Value = 0;

    public $total_cash_count_amount = 0;

    public $show_open_immediatly = true;
    public $open_immediatly = false;
    public $counter_is_opened = true;
    public $counter_want_to_close = false;
    public $immediate_open_amount;

    public $counter_balance = 0;
    public $counter_cashout_amount = 0;

    public $counter_cash_out_histories = [];

    public $counter_amount = 0;
    public $counter_id;
    protected $listeners = ['newBill', 'clearCash'];

    public $delete_id = 0;

    public function mount()
    {
        if (session('counter_id')) {
            $this->counter_id = session()->get('counter_id');

            $counter_open_status = CounterModel::find($this->counter_id);

            if ($counter_open_status->open_status == 0) {
                $this->counter_is_opened = false;
            } else {
                $this->counter_is_opened = true;
            }
        }
    }

    public function openCashoutModel()
    {
        $this->counter_cashout_amount = 0;
        $this->showError = false;
        $this->dispatchBrowserEvent('insert-show-form');
    }

    public function closeCashoutModel()
    {
        $this->dispatchBrowserEvent('insert-hide-form');
    }

    public function counterCashOut()
    {
        $this->showError = true;

        $this->validate([
            'counter_cashout_amount' => 'required|numeric|gt:0',
        ],[
            'counter_cashout_amount.gt' => 'The cashout amount must be greater than 0.'
        ]);

        $cash_balance = CashBalanceModel::where('property_id', '=', Auth::user()->property_id)
            ->where('counter_id', '=', $this->counter_id)->first();

        if ($cash_balance) {
            $cash_balance_amount = $cash_balance->amount;
            $minimum_balance = 3000;

            $cashout_amount = $this->counter_cashout_amount;

            // Calculate the maximum cashout amount
            $max_cashout_amount = max($cash_balance_amount - $minimum_balance, 0);

            if ($cashout_amount <= $max_cashout_amount) {
                $cash_balance->amount = $cash_balance_amount - $cashout_amount;
                $cash_balance->save();

                $cash_out_history = new CounterCashOutHistoryModel();
                $cash_out_history->amount = $cashout_amount;
                $cash_out_history->date = date('Y-m-d');
                $cash_out_history->counter_id = $this->counter_id;
                $cash_out_history->property_id = Auth::user()->property_id;
                $cash_out_history->user_id = Auth::user()->id;
                $cash_out_history->save();

                session()->flash('message', 'Cashout from counter completed successfully!');
            } else {
                session()->flash('error_message', 'Cashout amount exceeds the maximum allowable amount.');
            }
        }
    }


    public function deleteOpenModel($id)
    {
        $this->delete_id = $id;
        $this->dispatchBrowserEvent('delete-show-form');
    }

    public function cashCalculate()
    {
        $this->cash_5000_Value = (int) $this->cash_5000_Qty * 5000;
        $this->cash_1000_Value = (int) $this->cash_1000_Qty * 1000;
        $this->cash_500_Value = (int) $this->cash_500_Qty * 500;
        $this->cash_100_Value = (int) $this->cash_100_Qty * 100;
        $this->cash_50_Value = (int) $this->cash_50_Qty * 50;
        $this->cash_20_Value = (int) $this->cash_20_Qty * 20;
        $this->cash_10_Value = (int) $this->cash_10_Qty * 10;
        $this->cash_5_Value = (int) $this->cash_5_Qty * 5;
        $this->cash_2_Value = (int) $this->cash_2_Qty * 2;
        $this->cash_1_Value = (int) $this->cash_1_Qty * 1;

        $this->total_cash_count_amount =
            $this->cash_5000_Value +
            $this->cash_1000_Value +
            $this->cash_500_Value +
            $this->cash_100_Value +
            $this->cash_50_Value +
            $this->cash_20_Value +
            $this->cash_10_Value +
            $this->cash_5_Value +
            $this->cash_2_Value +
            $this->cash_1_Value;

        if ($this->total_cash_count_amount != 0) {
            $this->show_open_immediatly = false;
            $this->immediate_open_amount = null;
        } else {
            $this->show_open_immediatly = true;
        }
    }

    public function openImmediately()
    {
        $this->dispatchBrowserEvent('focus-on-amount-field');
        if (!$this->open_immediatly) {
            $this->immediate_open_amount = null;
        }
    }

    public function openCounter()
    {
        if ($this->immediate_open_amount) {
            $this->counter_amount = $this->immediate_open_amount;
        } elseif ($this->total_cash_count_amount != 0) {
            $this->counter_amount = $this->total_cash_count_amount;
        }

        $this->validate(
            [
                'cash_5000_Qty' => 'min:0|numeric',
                'cash_1000_Qty' => 'min:0|numeric',
                'cash_500_Qty' => 'min:0|numeric',
                'cash_100_Qty' => 'min:0|numeric',
                'cash_50_Qty' => 'min:0|numeric',
                'cash_20_Qty' => 'min:0|numeric',
                'cash_10_Qty' => 'min:0|numeric',
                'cash_5_Qty' => 'min:0|numeric',
                'cash_2_Qty' => 'min:0|numeric',
                'cash_1_Qty' => 'min:0|numeric',

                'immediate_open_amount' => $this->open_immediatly == true ? 'required|min:0|numeric' : '',
            ],
            [
                'cash_5000_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_1000_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_500_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_100_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_50_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_20_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_10_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_5_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_2_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_1_Qty.min' => 'Quantity of cash should not be less than 0',

                'immediate_open_amount.min' => 'Opening amount must be at least 0.'
            ]
        );

        if ($this->counter_id) {
            $counter = CounterModel::find($this->counter_id);
            $counter->open_status = 1;
            $counter->save();

            $cash_data = CashBalanceModel::where('property_id', '=', Auth::user()->property_id)
                ->where('counter_id', '=', $this->counter_id)->first();
            if ($cash_data) {
                $cash_data->amount = $this->counter_amount;
                $cash_data->save();
            } else {
                $new_cash = new CashBalanceModel();
                $new_cash->amount = $this->counter_amount;
                $new_cash->property_id = Auth::user()->property_id;
                $new_cash->counter_id = $this->counter_id;
                $new_cash->save();
            }

            $counter_activity_log = new CounterActivityLogModel();
            $counter_activity_log->amount = $this->counter_amount;
            $counter_activity_log->date = date('Y-m-d');
            $counter_activity_log->activity = "opened";
            $counter_activity_log->property_id = Auth::user()->property_id;
            $counter_activity_log->counter_id = $this->counter_id;
            $counter_activity_log->auth_id = Auth::user()->id;
            $counter_activity_log->save();

            $this->counter_is_opened = true;
        }

    }

    public function showCloseCounter()
    {
        $this->counter_is_opened = false;
        $this->open_immediatly = false;
        $this->counter_want_to_close = true;
        $this->clearCash();
    }

    public function cancelCounterClosing()
    {
        $this->counter_is_opened = true;
        $this->counter_want_to_close = false;
    }

    public function closeCounter()
    {
        if ($this->counter_want_to_close == true) {
            $this->counter_amount = $this->total_cash_count_amount;
        }

        $this->validate(
            [
                'cash_5000_Qty' => 'min:0|numeric',
                'cash_1000_Qty' => 'min:0|numeric',
                'cash_500_Qty' => 'min:0|numeric',
                'cash_100_Qty' => 'min:0|numeric',
                'cash_50_Qty' => 'min:0|numeric',
                'cash_20_Qty' => 'min:0|numeric',
                'cash_10_Qty' => 'min:0|numeric',
                'cash_5_Qty' => 'min:0|numeric',
                'cash_2_Qty' => 'min:0|numeric',
                'cash_1_Qty' => 'min:0|numeric'
            ],
            [
                'cash_5000_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_1000_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_500_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_100_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_50_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_20_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_10_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_5_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_2_Qty.min' => 'Quantity of cash should not be less than 0',
                'cash_1_Qty.min' => 'Quantity of cash should not be less than 0'
            ]
        );

        if ($this->counter_id) {
            $counter = CounterModel::find($this->counter_id);
            $counter->open_status = 0;
            $counter->save();

            $cash_data = CashBalanceModel::where('property_id', '=', Auth::user()->property_id)
                ->where('counter_id', '=', $this->counter_id)->first();

            if ($cash_data) {
                $cash_data->amount = $this->counter_amount;
                $cash_data->save();
            }

            $counter_activity_log = new CounterActivityLogModel();
            $counter_activity_log->amount = $this->counter_amount;
            $counter_activity_log->date = date('Y-m-d');
            $counter_activity_log->activity = "closed";
            $counter_activity_log->property_id = Auth::user()->property_id;
            $counter_activity_log->counter_id = $this->counter_id;
            $counter_activity_log->auth_id = Auth::user()->id;
            $counter_activity_log->save();

            $this->counter_is_opened = false;
            $this->counter_want_to_close = false;
            $this->clearCash();

            $this->dispatchBrowserEvent('counter-close-alert-show');
        }
    }

    public function hideCounterCloseAlert()
    {
        $this->dispatchBrowserEvent('counter-close-alert-hide');
    }

    public function clearCash()
    {
        $this->cash_5000_Qty = 0;
        $this->cash_1000_Qty = 0;
        $this->cash_500_Qty = 0;
        $this->cash_100_Qty = 0;
        $this->cash_50_Qty = 0;
        $this->cash_20_Qty = 0;
        $this->cash_10_Qty = 0;
        $this->cash_5_Qty = 0;
        $this->cash_2_Qty = 0;
        $this->cash_1_Qty = 0;
    }

    #delete Data
    public function deleteRecord()
    {
        if ($this->delete_id != 0) {
            $temp_bill_item = TempOrderModel::where('tem_orders.temp_bill_id', $this->delete_id);
            $temp_bill_item->delete();

            $temp_bill = TempBillModel::find($this->delete_id);
            $temp_bill->delete();
            $this->deleteCloseModel();

            //show Delete message
            session()->flash('del_message', 'Pending Bill Deleted!');
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

    // pagination purpose code
    public $d_count = 0;
    public function next()
    {
        $this->d_count = $this->d_count + 10;
    }

    public function prev()
    {
        $this->d_count = $this->d_count - 10;
    }

    public function newBill()
    {
        $tem_bill = new TempBillModel();
        $tem_bill->total = 0;
        $tem_bill->auth_id = Auth::user()->id;
        $tem_bill->property_id = Auth::user()->property_id;
        $tem_bill->counter_id = $this->counter_id;
        $tem_bill->save();

        return Redirect::to('/sale/' . $tem_bill->id);
    }

    //fetch data from db
    public function fetchData()
    {
        $this->temp_bill_data = DB::table('temp_bills')
            ->select(
                'temp_bills.*',
                DB::raw('COUNT(tem_orders.id) as item_count'),
                DB::raw('SUM(tem_orders.quantity * tem_orders.sell_price) as total_amount')
            )
            ->leftJoin('tem_orders', 'temp_bills.id', '=', 'tem_orders.temp_bill_id')
            ->where('temp_bills.auth_id', Auth::user()->id)
            ->where('temp_bills.property_id', Auth::user()->property_id)
            ->groupBy('temp_bills.id')
            ->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        $this->data_count = DB::table('temp_bills')
            ->where('temp_bills.auth_id', Auth::user()->id)
            ->where('temp_bills.property_id', Auth::user()->property_id)
            ->count('id');
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
        if ($this->counter_id) {
            $counter_name = CounterModel::find($this->counter_id)->value('counter');
            $this->counter_balance = DB::table('cash_balances')
                ->where('cash_balances.property_id', '=', Auth::user()->property_id)
                ->where('cash_balances.counter_id', '=', $this->counter_id)
                ->value('amount');

            $this->counter_cash_out_histories = DB::table('counter_cash_out_histories')
                ->select('counter_cash_out_histories.*', 'users.name as user_name')
                ->join('users', 'counter_cash_out_histories.user_id', '=', 'users.id')
                ->where('counter_cash_out_histories.property_id', '=', Auth::user()->property_id)
                ->where('counter_cash_out_histories.counter_id', '=', $this->counter_id)
                ->latest()
                ->skip($this->d_count)
                ->limit(10)
                ->get();

        } else {
            $counter_name = null;
            $this->counter_balance = 0;
            $this->counter_cash_out_histories = [];
        }

        $this->fetchData();
        $this->pageAction();
        $this->cashCalculate();
        return view('livewire.sales-view', compact('counter_name'))->layout('layouts.master');
    }
}
