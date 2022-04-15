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
Breadcrumbs::for('invoices.create', function ($trail) {
    $trail->parent('invoices');
    $trail->push('Nueva Factura', route('invoices.create'));
});
Breadcrumbs::for('invoices.orders', function ($trail) {
    $trail->parent('invoices');
    $trail->push('Órdenes', route('orders'));
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
    $trail->push('Detalles -> '.$product->name, route('products.show', $product));
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
Breadcrumbs::for('recursos.show', function ($trail, $recurso) {
    $trail->parent('recursos');
    $trail->push('Detalles -> '.$recurso->name, route('recursos.show', $recurso));
});

/* Recursos */
Breadcrumbs::for('procesos', function ($trail) {
    $trail->parent('home');
    $trail->push('Procesos', route('procesos.index'));
});
Breadcrumbs::for('procesos.create', function ($trail) {
    $trail->parent('procesos');
    $trail->push('Create Proceso', route('procesos.create'));
});
Breadcrumbs::for('procesos.show', function ($trail, $proceso) {
    $trail->parent('procesos');
    $trail->push('Detalles -> '.$proceso->name, route('procesos.show', $proceso));
});
/* Ajustes */
Breadcrumbs::for('settings', function ($trail) {
    $trail->parent('home');
    $trail->push('Ajustes', route('settings.index'));
});
