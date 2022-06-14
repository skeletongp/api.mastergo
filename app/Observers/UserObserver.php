<?php

namespace App\Observers;

use App\Models\User;
use Ramsey\Uuid\Uuid;

class UserObserver
{
    public function creating(User $user) {
        $user->uid = (string) Uuid::uuid4();
        $user->fullname =$user->name.' '. (string) rtrim($user->lastname);
    }
    public function saving(User $user)
    {
        $user->fullname = $user->name.' '. (string) rtrim($user->lastname);
    }
  
}
