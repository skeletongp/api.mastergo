<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Usuarios
Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home');
    $trail->push('Usuarios', route('users.index'));
});

// Clientes
Breadcrumbs::for('clients', function ($trail) {
    $trail->parent('home');
    $trail->push('Clientes', route('clients.index'));
});

/* Facturas */
Breadcrumbs::for('invoices', function ($trail) {
    $trail->parent('home');
    $trail->push('Facturas', route('invoices.index'));
});

/* Productos */
Breadcrumbs::for('products', function ($trail) {
    $trail->parent('home');
    $trail->push('Productos', route('products.index'));
});
Breadcrumbs::for('products.create', function ($trail) {
    $trail->parent('products');
    $trail->push('Crear Producto', route('products.create'));
});
Breadcrumbs::for('products.show', function ($trail, $product) {
    $trail->parent('products');
    $trail->push('Detalles', route('products.show', $product));
});
Breadcrumbs::for('products.sum', function ($trail) {
    $trail->parent('products');
    $trail->push('Sumar Productos', route('products.sum'));
});
Breadcrumbs::for('products.edit', function ($trail, $product) {
    $trail->parent('products.show', $product);
    $trail->push('Editar Producto', route('products.edit', $product));
});

/* Recursos */
Breadcrumbs::for('recursos', function ($trail) {
    $trail->parent('home');
    $trail->push('Recursos', route('recursos.index'));
});
/* Ajustes */
Breadcrumbs::for('settings', function ($trail) {
    $trail->parent('home');
    $trail->push('Ajustes', route('settings.index'));
});
