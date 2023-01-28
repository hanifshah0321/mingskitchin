<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Booktable;
use Illuminate\Support\Facades\Validator;

class BooktableController extends Controller
{
    

     public function booktable(Request $request){
      
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
               
            ]);
            if ($validator->fails()) {
                $response=[
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response);
            }
            $lead= new Booktable();
            $lead->name=$request->name;
            $lead->email=$request->email;
            $lead->phone=$request->phone;
            $lead->time=$request->time;
            $lead->date=$request->date;
            $lead->no_of_person=$request->no_of_person;
            $lead->message=$request->message;
            $lead->save();
            $response=[
                'success' => true,
                'message' => 'Your request submit successful.',
            ];
            return response()->json($response);
        
    }
}
