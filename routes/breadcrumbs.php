<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'),['icon'=>'fas fa-home']);
});

// Usuarios
Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home');
    $trail->push('Usuarios', route('users.index'),['icon'=>'fas fa-users']);
});
Breadcrumbs::for('users.set-permissions', function ($trail, $user) {
    $trail->parent('users');
    $trail->push('Gestionar Permisos', route('users.setPermissions', $user),['icon'=>'far fa-shield-alt']);
});

// Clientes
Breadcrumbs::for('clients', function ($trail) {
    $trail->parent('home');
    $trail->push('Clientes', route('clients.index'), ['icon'=>'fas fa-users']);
});

/* Facturas */
Breadcrumbs::for('invoices', function ($trail) {
    $trail->parent('home');
    $trail->push('Facturas', route('invoices.index'), ['icon'=>'fas fa-file-invoice']);
});
Breadcrumbs::for('invoices.create', function ($trail) {
    $trail->parent('invoices');
    $trail->push('Nueva Factura', route('invoices.create'));
});
Breadcrumbs::for('invoices.orders', function ($trail) {
    $trail->parent('invoices');
    $trail->push('Órdenes', route('orders'));
});
Breadcrumbs::for('invoices.show', function ($trail, $invoice) {
    $trail->parent('invoices');
    $trail->push('Detalles -> Fct. '.$invoice->number, route('invoices.show', $invoice));
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
    $trail->push('Recursos', route('recursos.index'), ['icon'=>'fas fa-warehouse-alt']);
});
Breadcrumbs::for('recursos.show', function ($trail, $recurso) {
    $trail->parent('recursos');
    $trail->push('Detalles -> '.$recurso->name, route('recursos.show', $recurso));
});
Breadcrumbs::for('recursos.sum', function ($trail) {
    $trail->parent('recursos');
    $trail->push('Sumar recursos', route('recursos.sum'));
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
