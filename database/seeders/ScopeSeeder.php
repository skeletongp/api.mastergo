<?php

namespace Database\Seeders;

use App\Models\Scope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScopeSeeder extends Seeder
{
    
    public function run()
    {
        $scopes=[
            'Facturas','Cotizaciones','Pedidos','Clientes','Usuarios','Proveedores',
            'Productos','Recursos','Procesos','Ingresos','Gastos','Estadísticas','Reportes',
            'Importaciones','Comprobantes','Sucursales','Permisos','Unidades','Impuestos'
        ];
        foreach($scopes as $scope){
            Scope::create([
                'name'=>$scope
            ]);
        }
    }
}
