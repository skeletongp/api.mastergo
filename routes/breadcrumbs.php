<?php

// Home

use Carbon\Carbon;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

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
Breadcrumbs::for('clients.show', function ($trail, $client) {
    $trail->parent('clients');
    $trail->push($client->name, route('clients.show', $client), ['icon'=>'fas fa-user']);
});
Breadcrumbs::for('clients.paymany', function ($trail,  $invoices) {
    $trail->parent('clients');
    $trail->push('Cobrar facturas', route('clients.paymany', $invoices), ['icon'=>'fas fa-dollar-sign']);
});
// Proveedores
Breadcrumbs::for('providers', function ($trail) {
    $trail->parent('home');
    $trail->push('Proveedores', route('providers.index'), ['icon'=>'fas fa-user-tie']);
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
    $trail->push('Fct. '.$invoice->number, route('invoices.show', $invoice));
});

/* Cotizaciones */
Breadcrumbs::for('cotizes', function ($trail) {
    $trail->parent('home');
    $trail->push('Cotizaciones', route('cotizes.index'), ['icon'=>'fas fa-file-invoice']);
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
    $trail->push($product->code.'-'.$product->name, route('products.show', $product));
});
Breadcrumbs::for('products.sum', function ($trail) {
    $trail->parent('products');
    $trail->push('Sumar Productos', route('products.sum'));
});
Breadcrumbs::for('products.edit', function ($trail, $product) {
    $trail->parent('products.show', $product);
    $trail->push('Editar Producto', route('products.edit', $product));
});

Breadcrumbs::for('products.catalogue', function ($trail) {
    $trail->parent('products');
    $trail->push('Catálogo', route('products.catalogue'),['icon'=>'fas fa-layer-group']);
});

/* Recursos */
Breadcrumbs::for('recursos', function ($trail) {
    $trail->parent('home');
    $trail->push('Recursos', route('recursos.index'), ['icon'=>'fas fa-warehouse-alt']);
});
Breadcrumbs::for('recursos.show', function ($trail, $recurso) {
    $trail->parent('recursos');
    $trail->push($recurso->name, route('recursos.show', $recurso));
});
Breadcrumbs::for('recursos.sum', function ($trail) {
    $trail->parent('recursos');
    $trail->push('Sumar recursos', route('recursos.sum'));
});

/* Procesos */
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
    $trail->push($proceso->name, route('procesos.show', $proceso));
});
Breadcrumbs::for('procesos.formula', function ($trail, $proceso) {
    $trail->parent('procesos.show', $proceso);
    $trail->push('Fórmula de '.$proceso->name, route('procesos.formula', $proceso), ['icon'=>'fas fa-flask']);
});

/* Producciones */
Breadcrumbs::for('productions', function ($trail, $production) {
    $trail->parent('procesos.show', $production->proceso);
    $trail->push('Producción de '.$production->proceso->name, route('productions.show', $production));
});
/* Ajustes */
Breadcrumbs::for('settings', function ($trail) {
    $trail->parent('home');
    $trail->push('Ajustes', route('settings.index'));
});
/* Comprobantes */
Breadcrumbs::for('comprobantes', function ($trail) {
    $trail->parent('home');
    $trail->push('Comprobantes', route('comprobantes.index'),['icon'=>'far fa-receipt']);
});

/* Cuadres */
Breadcrumbs::for('cuadres', function ($trail) {
    $trail->parent('home');
    $trail->push('Cuadre Diario', route('cuadres.index'),['icon'=>'far fa-receipt']);
});
Breadcrumbs::for('cuadres.show', function ($trail, $cuadre) {
    $trail->parent('finances', 'finances.cuadres-hist');
    $trail->push('Cuadre Diario '.Carbon::parse($cuadre->day)->format('d/m/Y'), route('cuadres.show', $cuadre),['icon'=>'fas fa-receipt']);
});

/* Finanzas */
Breadcrumbs::for('finances', function ($trail, $componentName=null) {
    $trail->parent('home');
    $trail->push('Cuentas ', route('finances.index', ['componentName'=>$componentName]),['icon'=>'fas fa-dollar-sign']);
});
Breadcrumbs::for('bank_show', function ($trail, $bank, $type) {
    $trail->parent('finances');
    $trail->push('Cta. Nro. '.$bank->bank_number, route('finances.bank_show', ['bank'=>$bank,'type'=> $type]),['icon'=>'fas fa-dollar-sign']);
});

/* Contables */
Breadcrumbs::for('general-daily', function ($trail) {
    $trail->parent('home');
    $trail->push('Diario General', route('contables.general_daily'),['icon'=>'far  fa-calendar-day']);
});
Breadcrumbs::for('general-mayor', function ($trail) {
    $trail->parent('general-daily');
    $trail->push('Balance General', route('contables.general_mayor'),['icon'=>'far  fa-calendar-alt']);
});
Breadcrumbs::for('catalogue', function ($trail) {
    $trail->parent('general-mayor');
    $trail->push('Catálogo de Cuentas', route('contables.catalogue'),['icon'=>'far  fa-list']);
});
Breadcrumbs::for('results', function ($trail) {
    $trail->parent('general-mayor');
    $trail->push('Estado de Ganancias y Pérdidas', route('contables.results'),['icon'=>'far  fa-chart-bar']);
});

/* Reportes */
Breadcrumbs::for('incomes', function($trial){
    $trial->parent('home');
    $trial->push('Ingresos',route('reports.incomes',['icon'=>'']));
});
Breadcrumbs::for('outcomes', function($trial){
    $trial->parent('home');
    $trial->push('Gastos',route('reports.outcomes',['icon'=>'']));
});
Breadcrumbs::for('invoice-historial', function($trial){
    $trial->parent('invoices.orders');
    $trial->push('Hist. de Facturas',route('reports.invoices',['icon'=>'fas fa-file-alt']));
});
Breadcrumbs::for('invoice-por-cobrar', function($trial){
    $trial->parent('invoices.orders');
    $trial->push('Facturas por cobrar',route('reports.invoices_por_cobrar',['icon'=>'fas fa-file-alt']));
});

/* Provisiones */
Breadcrumbs::for('provisions', function($trial){
    $trial->parent('home');
    $trial->push('Compras y Provisiones',route('provisions.index',['icon'=>'']));
});

/* Recurrentes */
Breadcrumbs::for('recurrents', function($trial){
    $trial->parent('home');
    $trial->push('Obligaciones',route('recurrents.index',['icon'=>'far fa-donate fa-lg']));
});


