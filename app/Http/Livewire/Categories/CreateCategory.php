<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class CreateCategory extends Component
{
    public $form = [
        'name' => '',
        'description' => '',
    ];

    protected $rules = [
        'form.name' => 'required',
        'form.description' => 'required',
    ];

    public function render()
    {
        return view('livewire.categories.create-category');
    }

    public function createCategory()
    {
        $this->validate();
        $this->form['store_id'] = env('STORE_ID');
        Category::create($this->form);
        $this->reset('form');
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Categoría creada con éxito', 'success');
        $this->reset();
    }
}
