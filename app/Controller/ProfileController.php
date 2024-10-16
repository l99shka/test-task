<?php

namespace App\Controller;


class ProfileController
{
    public function profile(): array
    {
        return [
            'view' => 'profile',
            'data' => []
        ];
    }
}