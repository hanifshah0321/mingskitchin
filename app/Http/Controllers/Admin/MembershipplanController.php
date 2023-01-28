<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\AdminRole;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Membership;

class MembershipplanController extends Controller
{
    
    public function add_new()
    {
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.membership.add-new', compact('rls'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'duration' => 'required',

        ]);


        DB::table('membership_plan')->insert([
            'title' => $request->title,
            'des' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'discount' => $request->discount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('added successfully!'));
        return redirect()->route('admin.membership.list');
    }

    function list(Request $request)
    {
        $search = $request['search'];
        $key = explode(' ', $request['search']);
        $em = Membership::when($search!=null, function($query) use($key){
                        foreach ($key as $value) {
                            $query->where('f_name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%");
                        }
                    })
                    ->paginate(Helpers::getPagination());
        return view('admin-views.membership.list', compact('em','search'));
    }

    public function edit($id)
    {
        $e = Membership::where(['id' => $id])->first();
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.membership.edit', compact('rls', 'e'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'duration' => 'required',
        ]);

    

        $e = Membership::find($id);

        DB::table('membership_plan')->where(['id' => $id])->update([

            'title' => $request->title,
            'des' => $request->des,
            'price' => $request->price,
            'duration' => $request->duration,
            'discount' => $request->discount,
            'updated_at' => now(),
        ]);

        Toastr::success(translate('updated successfully!'));
        return back();
    }

    public function status(Request $request)
    {
        $employee = Admin::find($request->id);
        $employee->status = $request->status;
        $employee->save();

        Toastr::success(translate('Employee status updated!'));
        return back();
    }
}
