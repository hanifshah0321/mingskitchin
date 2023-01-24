<?php

namespace App\Http\Controllers\Branch;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Table;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function App\CentralLogics\translate;

class TableOrderController extends Controller
{
    public function order_list($status, Request $request)
    {
        Order::where(['checked' => 0, 'branch_id' => auth('branch')->id()])->update(['checked' => 1]);
        if ($status == 'all') {
            $orders = Order::with(['customer', 'branch', 'table'])
                ->where(['branch_id' => auth('branch')->id()]);
        } else {
            $orders = Order::with(['customer', 'branch', 'table'])
                ->where(['order_status' => $status, 'branch_id' => auth('branch')->id()]);
        }
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $orders = Order::where(['branch_id' => auth('branch')->id()])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_reference', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        }

        $orders = $orders->dineIn()->latest()->paginate(Helpers::getPagination())->appends($query_param);

        return view('branch-views.table.order.list', compact('orders','search'));
    }

    public function order_details($id)
    {
        $order = Order::with('details')->where(['id' => $id])->first();

        if(!isset($order)) {
            Toastr::info(translate('No more orders!'));
            return back();
        }

        //remaining delivery time
        $delivery_date_time =  $order['delivery_date']. ' ' .$order['delivery_time'];
        $ordered_time = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($delivery_date_time)));
        $remaining_time = $ordered_time->add($order['preparation_time'], 'minute')->format('Y-m-d H:i:s');
        $order['remaining_time'] = $remaining_time;

        return view('branch-views.order.order-view', compact('order'));
    }

    public function table_running_order(Request $request)
    {
        $table_id = $request->table_id;
        $tables = Table::with('order')->whereHas('order', function ($q){
            $q->whereHas('table_order', function($q){
                $q->where('branch_table_token_is_expired', 0);
            });
        })
            ->where(['branch_id' => auth('branch')->id()])
            ->get();

        $orders = Order::with('table_order')->whereHas('table_order', function($q){
            $q->where('branch_table_token_is_expired', 0);
        })
            ->where(['branch_id' => auth('branch')->id()])
            ->when(!is_null($table_id), function ($query) use ($table_id) {
                return $query->where('table_id', $table_id);
            })
            ->latest()->paginate(Helpers::getPagination());


        return view('branch-views.table.order.table_running_order', compact('tables', 'orders', 'table_id'));
    }

    public function running_order_invoice(Request $request)
    {
        $table_id = $request->table_id;
        $orders = Order::with('table_order')->whereHas('table_order', function($q){
            $q->where('branch_table_token_is_expired', 0);
        })
            ->when(!is_null($table_id), function ($query) use ($table_id) {
                return $query->where('table_id', $table_id);
            }) ->get();


        //dd($orders);
        return view('branch-views.table.order.running_order_invoice', compact( 'orders', 'table_id'));
    }

}
