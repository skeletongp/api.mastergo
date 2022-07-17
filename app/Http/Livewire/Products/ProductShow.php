<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductShow extends Component
{
    use WithFileUploads;
    public Product $product;

    public $componentName = 'products.product-price';

    protected $queryString = ['componentName'];

    protected $listeners = [
        'productPhotoChange' => 'productPhotoChange',
    ];

    public function render()
    {
        return view('livewire.products.product-show');
    }
    public function setComponent($name)
    {
        $this->componentName = $name;
    }
    public function productPhotoChange($photo)
    {
        if(!strpos($photo, 'image/jpeg')&&  !strpos($photo, 'image/png')){
         $this->emit('showAlert','La imagen debe ser en formato jpg o png','error');  
         return;
        }

        $path = cloudinary()->upload($photo,
        [
            'folder' => 'carnibores/products',
            'transformation' => [
                      'height' => 250
             ]
        ])->getSecurePath();
        if ($path) {
            $this->product->image()->updateOrCreate(['imageable_id'=>$this->product->id],[
                'path'=>$path
            ]);
           
        }
        return redirect()->route('products.show',$this->product->id);

    }
}
