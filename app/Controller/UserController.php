<?php

namespace App\Controller;



class UserController
{
    public function signup(): array
    {
        return [
            'view' => 'signup',
            'data' => []
        ];
    }

    public function login(): array
    {
        return [
            'view' => 'login',
            'data' => []
        ];
    }

    public function logout()
    {

    }
}