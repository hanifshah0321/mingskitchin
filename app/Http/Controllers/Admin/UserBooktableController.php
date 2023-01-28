<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Booktable;
use App\CentralLogics\Helpers;
use App\Model\Admin;
use App\Model\AdminRole;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Membership;
use App\UserMembership;

class UserBooktableController extends Controller
{
     public function index()
    {

        $em = Booktable::orderBy('id', 'DESC')->paginate(Helpers::getPagination());;
        return view('admin-views.booktable.list', compact('em'));
    }
}
