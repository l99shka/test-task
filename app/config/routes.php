<?php

use App\Controller\MainController;
use App\Controller\ProfileController;
use App\Controller\UserController;

return [
    '/' => [MainController::class, 'main'],
    '/signup' => [UserController::class, 'signup'],
    '/login' => [UserController::class, 'login'],
    '/logout' => [UserController::class, 'logout'],
    '/profile' => [ProfileController::class, 'profile'],
    '/profile/edit' => [ProfileController::class, 'editProfile']
];