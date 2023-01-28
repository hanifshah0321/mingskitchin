<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Model\Admin;
use App\Model\AdminRole;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Membership;
use App\UserMembership;

class UserMembershipController extends Controller
{
     public function index()
    {

        $em = UserMembership::with('usermembershipplan', 'user')->paginate(Helpers::getPagination());;
        return view('admin-views.usermembership.list', compact('em'));
    }
}
