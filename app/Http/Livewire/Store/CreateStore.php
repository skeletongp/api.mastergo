<?php

namespace App\Http\Livewire\Store;

use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class CreateStore extends Component
{
    use WithFileUploads, AuthorizesRequests;
    public $form;
    public $logo, $photo_path, $photo_prev;
    protected $rules = [
        'form.name' => 'required|string|max:75',
        'form.address' => 'required|string|max:100',
        'form.lema' => 'string|max:75',
        'form.email' => 'required|string|max:100|unique:moso_master.stores,email',
        'form.phone' => 'required|string|max:15',
        'form.rnc' => 'max:12',
        'logo' => 'max:2048'
    ];

    public function render()
    {
        return view('livewire.store.create-store');
    }

    public function createStore()
    {
        $this->authorize('Crear Negocios');
        $this->validate();
        $store = Store::create($this->form);
        auth()->user()->stores()->save($store);
        if ($this->photo_path) {
            $store->image()->updateOrCreate(
                ['imageable_id' => $store->id, 'imageable_type' => 'App\Models\Store'],
                ['path' => $this->photo_path]
            );
        }
       /*  $this->createPlace($store);
        $this->createUnit($store);
        $this->createTax($store);
        $this->createClient($store);
        $this->createProvider($store);
        
        $this->setPreference($store->places()->first(), $store);
        $store->roles()->save(Role::find(1));
        $store->roles()->save(Role::find(2));
        $store->roles()->save(Role::find(3)); */
        $this->reset();
        $this->emit('reloadUsers');
        $this->emitUp('reloadSettingStore');
        Cache::forget('scopes_'.auth()->user()->store->id);
        $this->emit('showAlert', 'Negocio registrado exitosamente', 'success');
    }


    public function updatedLogo()
    {
        $this->reset('photo_path');
        $this->validate([
            'logo' => 'image|max:2048'
        ]);
        $ext = pathinfo($this->logo->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->logo->storeAs('logos', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
    public function createPlace(Store $store)
    {
        $data = [
            'name' => $store->name . ' | Sede Principal',
            'phone' => $store->phone,
            'user_id' => auth()->user()->id,
        ];
        $place = $store->places()->create($data);
        $this->setCounts($place);
       
    }
    public function setCounts($place)
    {
        setContable($place, '100', 'debit','Efectivo en Caja General', $place->id);
        setContable($place, '100', 'debit','Efectivo en Caja Chica', $place->id);
        setContable($place, '100', 'debit','Efectivo en Cheques', $place->id);
        setContable($place, '100', 'debit','Otros Efectivos', $place->id);
        setContable($place, '104', 'debit', 'Inventario general',  $place->id,);
        setContable($place, '400', 'credit','Ingresos por Ventas', $place->id);
        setContable($place, '401', 'debit','Devoluciones en Ventas', $place->id);
        setContable($place, '401', 'debit','Otras notas de crédito', $place->id);
        setContable($place, '401', 'debit','Descuentos en Ventas', $place->id);
        setContable($place, '402', 'credit','Otros Ingresos', $place->id);
        setContable($place, '500', 'debit','Compra de mercancías', $place->id);
        setContable($place, '501', 'debit','Devoluciones en compras', $place->id);
        setContable($place, '501', 'debit','Descuentos en compras', $place->id);
        setContable($place, '300', 'credit','Capital Sucrito y Pagado', $place->id);
    }
    public function setPreference($place, $store)
    {
       $unit_id= $store->units()->first()->id;
       $tax_id=$store->taxes()->first()->id;
        $place->preference()->create([
            'comprobante_type'=>'B02',
            'unit_id'=>$unit_id,
            'tax_id'=>$tax_id,
           ]);
    }
    public function createUnit(Store $store)
    {
        $data = [
            'name' => 'Unidad',
            'symbol' => 'UND',
        ];
        $store->units()->create($data);
    }
    public function createTax(Store $store)
    {
        $data = [
            'name' => 'ITBIS',
            'rate' => 0.18,
        ];
        $tax = $store->taxes()->create($data);
        setContable($tax, '203', $tax->name . ' por Pagar');
        setContable($tax, '103', $tax->name . ' por Cobrar');
    }
    public function createClient(Store $store)
    {
        $data = [
            'name' => 'Cliente',
            'lastname' => 'Genérico',
            'email' => 'generico@' . strtok($store->name, ' ') . '.com',
            'address' => 'SIN DIRECCIÓN',
            'phone' => '(000)000-0000',
            'rnc' => '000-00000-0',
            'limit' => 0.0,
        ];
       $client= $store->clients()->create($data);
       $client->update(['code'=>'001']);
        setContable($client, '101', 'debit');
    }
    public function createProvider(Store $store)
    {
        $provider = $store->providers()->create([
            'name' => 'Prov.',
            'lastname' => 'Genérico ',
            'email' => 'generic@provider.com',
            'address' => 'Sin Dirección',
            'phone' => '(000) 000-0000',
            'rnc' => '000-00000-0',
            'limit' => 0,
        ]);
        setContable($provider, '201', 'credit');
    }
}
