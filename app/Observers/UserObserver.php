<?php

namespace App\Observers;

use App\Models\User;
use Ramsey\Uuid\Uuid;

class UserObserver
{
    public function creating(User $user) {
        $user->uid = (string) Uuid::uuid4();
        $user->fullname = (string) rtrim($user->lastname).', '.$user->name;
    }
    public function saving(User $user)
    {
        $user->fullname = (string) rtrim($user->lastname).', '.$user->name;
    }
  
}
