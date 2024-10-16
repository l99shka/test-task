<?php

namespace App\Controller;

use App\Model\User;

class MainController
{
    public function main(): array
    {
        session_start();

        if (isset($_SESSION['user_id'])) {
            $user = User::getUser($_SESSION['user_id']);
            $profileEmail = $user->getEmail();
        } else {
            $profileEmail = null;
        }

        return [
            'view' => 'main',
            'data' => ['profileEmail' => $profileEmail]
        ];
    }
}