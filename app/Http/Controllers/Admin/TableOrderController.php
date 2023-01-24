<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\Notification;
use App\Model\Order;
use App\Model\Table;
use App\Model\TableOrder;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function App\CentralLogics\translate;

class TableOrderController extends Controller
{
    public function order_list(Request $request, $status)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $orders = Order::where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_reference', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        }
        else{
            if (session()->has('branch_filter') == false) {
                session()->put('branch_filter', 0);
            }
            Order::where(['checked' => 0])->update(['checked' => 1]);

            //all branch
            if (session('branch_filter') == 0) {
                if ($status == 'all') {
                    $orders = Order::with(['customer', 'branch', 'table']);
                }
                else {
                    $orders = Order::with(['customer', 'branch', 'table'])
                        ->where(['order_status' => $status]);
                }
            } //selected branch
            else {

                if ($status == 'all') {
                    $orders = Order::with(['customer', 'branch', 'table'])->where('branch_id', session('branch_filter'));
                }
                else {
                    $orders = Order::with(['customer', 'branch', 'table'])
                        ->where(['order_status' => $status, 'branch_id' => session('branch_filter')]);
                }
            }
        }
        $orders = $orders->dineIn()->latest()->paginate(Helpers::getPagination())->appends($query_param);

        return view('admin-views.table.order.list', compact('orders', 'search'));
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

        return view('admin-views.order.order-view', compact('order'));
    }

    public function table_running_order(Request $request)
    {
        $branch_id = session('branch_filter') ? session('branch_filter') : 1;
        $table_id = $request->table_id;

        $tables = Table::with('order')->whereHas('order', function ($q){
                $q->whereHas('table_order', function($q){
                    $q->where('branch_table_token_is_expired', 0);
                });
            })
            ->where(['branch_id' => $branch_id])
            ->get();

        $orders = Order::with('table_order')->whereHas('table_order', function($q){
                $q->where('branch_table_token_is_expired', 0);
            })
            ->where(['branch_id' => $branch_id])
            ->when(!is_null($table_id), function ($query) use ($table_id) {
                    return $query->where('table_id', $table_id);
            })
            ->latest()->paginate(Helpers::getPagination());

        //return $orders;

        return view('admin-views.table.order.table_running_order', compact('tables', 'orders', 'table_id'));
    }

    public function running_order_invoice(Request $request)
    {
        $table_id = $request->table_id;
        $orders = Order::with('table_order')->whereHas('table_order', function($q){
            $q->where('branch_table_token_is_expired', 0);
        })
            ->when(!is_null($table_id), function ($query) use ($table_id) {
                return $query->where('table_id', $table_id);
            })->get();


//        return $orders;
        //dd($orders);
        return view('admin-views.table.order.running_order_invoice', compact( 'orders', 'table_id'));
    }

    public function branch_filter($id)
    {
        session()->put('branch_filter', $id);
        return back();
    }


}
