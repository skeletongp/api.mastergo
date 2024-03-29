<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin=Role::create(['name'=>'Super Admin']);
        $admin=Role::create(['name'=>'Administrador']);
        $generico=Role::create(['name'=>'Generico']);
        $newPermissions=[ "Crear Permisos", "Borrar Permisos", "Crear Negocios", "Borrar Negocios","Crear Scopes",'Ver Scopes',"Editar Scopes","Borrar Scopes","Autorizar", "Crear Roles", "Asignar Roles", "Borrar Roles", "Ver Roles", "Asignar Permisos", "Ver Permisos", "Crear Usuarios", "Ver Usuarios", "Borrar Usuarios", "Editar Usuarios", "Crear Clientes", "Ver Clientes", "Editar Clientes", "Borrar Clientes", "Crear Productos", "Ver Productos", "Editar Productos", "Borrar Productos", "Ver Utilidad", "Crear Proveedores", "Ver Proveedores", "Editar Proveedores", "Crear Bancos", "Borrar Proveedores", "Crear Facturas", "Ver Facturas", "Editar Facturas", "Borrar Facturas", "Restaurar Facturas", "Fiar Facturas", "Cobrar Facturas", "Enviar Facturas", "Imprimir Facturas", "Crear Sucursales", "Borrar Surcursales", "Editar Sucursales", "Ver Sucursales",  "Editar Negocios", "Ver Negocios", 'Asignar Créditos', 'Crear Unidades', 'Crear Impuestos', 'Borrar Unidades', 'Borrar Impuestos','Crear Comprobantes','Borrar Comprobantes','Editar Comprobantes', 'Cambiar Precios','Asignar Impuestos','Asignar Precios','Crear Gastos','Borrar Gastos','Ver Gastos','Editar Gastos','Pagar Gastos', 'Crear Cotizaciones','Ver Cotizaciones','Enviar Cotizaciones','Editar Cotizaciones','Borrar Cotizaciones', 'Ver Recursos','Crear Recursos','Editar Recursos','Borrar Recursos','Sumar Productos','Cancelar Proceso', 'Añadir Recursos', 'Retirar Recursos','Añadir Resultados','Retirar Resultados','Crear Atributos','Editar Atributos','Borrar Atributos', 'Crear Producciones','Editar Producciones','Borrar Producciones','Ver Producciones','Crear Procesos','Editar Procesos','Borrar Procesos', 'Ver Procesos', 'Iniciar Procesos','Terminar Procesos', 'Aplicar Descuentos','Crear Transacciones','Ver Transacciones','Borrar Transacciones','Editar Transacciones', 'Crear Cuentas','Borrar Cuentas','Ver Catálogo','Editar Cuentas','Imprimir Catálogo','Ver Cuadre', 'Abrir Cuadre', 'Ver Comprobantes','Registrar Asientos', 'Subir Cheques','Borrar Cheques','Editar Cheques','Ver Cheques' , 'Asignar ITBIS','Manejar Fórmulas', 'Crear Obligaciones','Ver Obligaciones','Borrar Obligaciones','Editar Obligaciones','Manejar NC'];
        $adminPermissions=Arr::except($newPermissions, [0, 1, 2, 3,4 ,5 ,6 ,7]);
        foreach ($newPermissions as $perm) {
            Permission::create(['name'=>$perm]);
        }
        $permissions=Permission::pluck('name');
        $superAdmin->syncPermissions($permissions);
        $admin->syncPermissions($adminPermissions);
        $generico->syncPermissions(Arr::only($newPermissions,[16, 20, 24, 29, 34, 61, 65, 69, 85, 89]));

    }
}
