<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    protected $table ="user_membership";
    
    
     public function usermembershipplan()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
