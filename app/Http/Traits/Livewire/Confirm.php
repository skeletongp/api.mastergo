<?php

namespace App\Http\Traits\Livewire;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;


trait Confirm
{
    use LivewireAlert;

    public function confirm($msg, $method, $data = null, $permission = null)
    {
        $user = auth()->user();
        if ($permission && !$user->hasPermissionTo($permission)) {
            $this->authorize('¿Autorizar esta acción?', 'validateAuthorization',  $method, $data, $permission);
            return;
        }
        $this->alert('question', $msg, [
            'position' => 'center',
            'allowOutsideClick' => false,
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => $method,
            'showDenyButton' => false,
            'data' => ['value' => $data],
            'onDenied' => '',
            'showCancelButton' => true,
            'onDismissed' => '',
            'timerProgressBar' => false,
            'confirmButtonText' => 'Proceder',
            'cancelButtonText' => 'Cancelar',
        ]);
    }
    public function authorize($msg, $event, $method, $data = null, $permission = null)
    {
        
        $user = auth()->user();
        if (!$user->hasPermissionTo('Autorizar')) {
            throw new AuthorizationException();
        }
        if ($permission && $user->hasPermissionTo($permission)) {
            $this->emit($method);
            return;
        }
        $this->alert('warning', $msg, [
            'position' => 'center',
            'allowOutsideClick' => false,
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => $event,
            'input' => 'password',
            'inputLabel' => 'Ingrese una contraseña de Admministrador',
            'showDenyButton' => false,
            'data' => ['value' => $data, 'method' => $method],
            'onDenied' => '',
            'showCancelButton' => true,
            'onDismissed' => '',
            'timerProgressBar' => false,
            'confirmButtonText' => 'Autorizar',
            'cancelButtonText' => 'Denegar',
        ]);
    }
    public function validateAuthorization($data)
    {
        $approved = false;
        foreach (admins() as $name => $pwd) {
            if (Hash::check($data['value'], $pwd)) {
                $approved = true;
                $this->emit($data['data']['method'], $data);
            }
        }
        if (!$approved) {
            throw new AuthorizationException();
        }
    }
}
