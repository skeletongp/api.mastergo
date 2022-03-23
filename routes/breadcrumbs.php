<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// users.index
Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home');
    $trail->push('Usuarios', route('users.index'));
});

Breadcrumbs::for('users_create', function ($trail) {
    $trail->parent('users');
    $trail->push('Crear', route('users.create'));
});