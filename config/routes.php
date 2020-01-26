<?php

return [
    'home' => [
        'path' => '/',
        'controller' => \Project\Controller\BookController::class,
        'action' => 'index'
    ],

    'sign-in' => [
        'path' => 'sign-in',
        'controller' => \Project\Controller\SecurityController::class,
        'action' => 'signIn'
    ],

    'sign-up' => [
        'path' => 'sign-up',
        'controller' => \Project\Controller\SecurityController::class,
        'action' => 'signUp'
    ],

    'sign-out' => [
        'path' => 'sign-out',
        'controller' => \Project\Controller\SecurityController::class,
        'action' => 'signOut'
    ],

    'book-view' => [
        'path' => 'book',
        'controller' => \Project\Controller\BookController::class,
        'action' => 'view'
    ],

    'book-create' => [
        'path' => 'create',
        'controller' => \Project\Controller\BookController::class,
        'action' => 'create'
    ],
    'book-update' => [
        'path' => 'update/{id:\d+}',
        'controller' => \Project\Controller\BookController::class,
        'action' => 'update'
    ],
    'book-delete' => [
        'path' => 'delete/{id:\d+}',
        'controller' => \Project\Controller\BookController::class,
        'action' => 'delete'
    ]
];
