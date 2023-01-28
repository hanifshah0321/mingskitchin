<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\CustomerAddress;
use App\Model\DMReview;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Auth;

use App\Membership;
use App\UserMembership;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderLogic::track_order($request['order_id']), 200);
    }

    public function place_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'delivery_address_id' => 'required',
            'order_type' => 'required',
            'branch_id' => 'required',
            'delivery_time' => 'required',
            'delivery_date' => 'required',
            'distance' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        //order scheduling
        if ($request['delivery_time'] == 'now') {
            $del_date = Carbon::now()->format('Y-m-d');
            $del_time = Carbon::now()->format('H:i:s');
        } else {
            $del_date = $request['delivery_date'];
            $del_time = $request['delivery_time'];
        }

        try {
            $or = [
                'id' => 100000 + Order::all()->count() + 1,
                'user_id' => $request->user()->id,
                'order_amount' => Helpers::set_price($request['order_amount']),
                'coupon_discount_amount' => Helpers::set_price($request->coupon_discount_amount),
                'coupon_discount_title' => $request->coupon_discount_title == 0 ? null : 'coupon_discount_title',
                'payment_status' => ($request->payment_method=='cash_on_delivery')?'unpaid':'paid',
                'order_status' => ($request->payment_method=='cash_on_delivery')?'pending':'confirmed',
                'coupon_code' => $request['coupon_code'],
                'payment_method' => $request->payment_method,
                'transaction_reference' => $request->transaction_reference ?? null,

                'order_note' => $request['order_note'],

                'order_type' => $request['order_type'],
                'branch_id' => $request['branch_id'],
                'delivery_address_id' => $request->delivery_address_id,

                'delivery_date' => $del_date,
                'delivery_time' => $del_time,
                'delivery_address' => json_encode(CustomerAddress::find($request->delivery_address_id) ?? null),

                'delivery_charge' => Helpers::get_delivery_charge($request['distance']),
                'preparation_time' => Helpers::get_business_settings('default_preparation_time') ?? 0,

                'created_at' => now(),
                'updated_at' => now()
            ];

            $o_id = DB::table('orders')->insertGetId($or);

            foreach ($request['cart'] as $c) {
                $product = Product::find($c['product_id']);
                $usermem = UserMembership::where('user_id', $request->user()->id)->first();
                $plandiscount;
                if (!empty($usermem) && $usermem != null) {
                    
                    $pack = Membership::where('id', $usermem->membership_id)->first();
                    $plandiscount= $pack->discount;

                    
                }
                if (array_key_exists('variation', $c) && count(json_decode($product['variations'], true)) > 0) {
                    $price = Helpers::variation_price($product, json_encode($c['variation']));
                    if (!empty($plandiscount)) {
                        $ninuvalue = $price / 100;
                        $discountamount = $ninuvalue * $plandiscount;
                        $price = $price - $discountamount;
                    }
                    

                } else {
                    $price = Helpers::set_price($product['price']);
                    if (!empty($plandiscount)) {
                        $ninuvalue = $price / 100;
                        $discountamount = $ninuvalue * $plandiscount;
                        $price = $price - $discountamount;
                    }
                    
                }
                $or_d = [
                    'order_id' => $o_id,
                    'product_id' => $c['product_id'],
                    'product_details' => $product,
                    'quantity' => $c['quantity'],
                    'price' => $price,
                    'tax_amount' => Helpers::tax_calculate($product, $price),
                    'discount_on_product' => Helpers::discount_calculate($product, $price),
                    'discount_type' => 'discount_on_product',
                    'variant' => json_encode($c['variant']),
                    'variation' => array_key_exists('variation', $c) ? json_encode($c['variation']) : json_encode([]),
                    'add_on_ids' => json_encode($c['add_on_ids']),
                    'add_on_qtys' => json_encode($c['add_on_qtys']),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                DB::table('order_details')->insert($or_d);

                //update product popularity point
                Product::find($c['product_id'])->increment('popularity_count');
            }

            $fcm_token = $request->user()->cm_firebase_token;
            $value = Helpers::order_status_update_message(($request->payment_method=='cash_on_delivery')?'pending':'confirmed');
            try {
                //send push notification
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $o_id,
                        'image' => '',
                        'type'=>'order_status',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }

                //send email
                $emailServices = Helpers::get_business_settings('mail_config');
                if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                    Mail::to($request->user()->email)->send(new \App\Mail\OrderPlaced($o_id));
                }

            } catch (\Exception $e) {

            }

            return response()->json([
                'message' => translate('order_success'),
                'order_id' => $o_id
            ], 200);

        } catch (\Exception $e) {
            return response()->json([$e], 403);
        }
    }

    public function get_order_list(Request $request)
    {
        $orders = Order::with(['customer', 'delivery_man.rating'])
            ->withCount('details')
            ->where(['user_id' => $request->user()->id])->get();

        $orders->map(function ($data) {
            $data['deliveryman_review_count'] = DMReview::where(['delivery_man_id' => $data['delivery_man_id'], 'order_id' => $data['id']])->count();
            return $data;
        });

        return response()->json($orders->map(function ($data) {
            $data->details_count = (integer)$data->details_count;
            return $data;
        }), 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = OrderDetail::with('order')->where(['order_id' => $request['order_id']])->get();

        if ($details->count() > 0) {
            foreach ($details as $det) {
                $det['add_on_ids'] = json_decode($det['add_on_ids']);
                $det['add_on_qtys'] = json_decode($det['add_on_qtys']);

                $det['variation'] = json_decode($det['variation'], true);
                if ($det->order->order_type == 'pos') {
                    if(isset($det['variation'][0])) {
                        $det['variation'] = implode('-', array_values($det['variation'][0])) ?? null;
                    } else {
                        $det['variation'] = implode('-', array_values($det['variation'])) ?? null;
                    }
                }
                else {
                    if (isset($det['variation'][0])) {
                        $det['variation'] = !empty($det['variation'][0]) ? (string)$det['variation'][0]['type'] : null;
                    } else {
                        $det['variation'] = !empty($det['variation']) ? (string)$det['variation']['type'] : null;
                    }
                }

                $det['review_count'] = Review::where(['order_id' => $det['order_id'], 'product_id' => $det['product_id']])->count();
                $product = Product::where('id', $det['product_id'])->first();
                $det['product_details'] = isset($product) ? Helpers::product_data_formatting($product) : '';
            }
            return response()->json($details, 200);
        } else {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('not found!')]
                ]
            ], 401);
        }
    }

    public function cancel_order(Request $request)
    {
        if (Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'order_status' => 'canceled'
            ]);
            return response()->json(['message' => translate('order_canceled')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('no_data_found')]
            ]
        ], 401);
    }

    public function update_payment_method(Request $request)
    {
        if (Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'payment_method' => $request['payment_method']
            ]);
            return response()->json(['message' => translate('payment_method_updated')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('no_data_found')]
            ]
        ], 401);
    }
    


     public function buyplan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'amount' => 'required',
            'membership_id' => 'required',
        
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $userid = $request->user_id;
        $amount = $request->amount;
        $membership_id = $request->membership_id;

        $usermembership =    new   UserMembership();
        $usermembership->user_id = $userid;
        $usermembership->membership_id  = $membership_id;
        $usermembership->buy_at = "2022-08-21";
        $usermembership->expire_at = "2022-12-01";
        $usermembership->pay_status = 1;
        $usermembership->save();

        return redirect(route('planpayWithpaypal',[$userid, $amount, $membership_id]));
    }
    
    
       public function checkmembership(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $plan = UserMembership::with('usermembershipplan')->where('user_id', $request->user_id)->first();

        // if (!empty($plan->usermembershipplan->discount) && $plan->usermembershipplan->discount != null) {

        //     $plan =$plan->usermembershipplan->discount;
        // }else{
        //     $plan = null;
        // }
        
        return response()->json($plan, 200);
    
    }
    
    public function loginuserid(Request $request){
        
         $user = auth()->user()->id;
         return response()->json($user, 200);
         
    }
}
