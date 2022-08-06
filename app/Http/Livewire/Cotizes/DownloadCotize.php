<?php

namespace App\Http\Livewire\Cotizes;

use App\Models\Cotize;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DownloadCotize extends Component
{
    public $cotize;
    public function render()
    {
        return view('livewire.cotizes.download-cotize');
    }
    public function downloadCotize()
    {
        $cotize=Cotize::find($this->cotize);
        return $this->download($cotize);
    }
    public function download($cotize){
        $data = [
            'cotize' => $cotize->load('details','user','client','place','store'),
        ];
        $PDF = App::make('dompdf.wrapper');
        $pdf = $PDF->setOptions([
            'logOutputFile' => null,
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled'=> true
        ])->loadView('pages.cotizes.letter', $data);
        $store=$cotize->store;
        $name='files'.$store->id.'/cotizes/cotize'.$cotize->id.'.pdf';
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url= Storage::url($name);
        $cotize->pdf->update([
            'pathLetter'=>$url,
            'note' => 'Cotización Nº. ' . $cotize->id,
        ]);
        return redirect()->route('cotizes.show', $cotize->id);
    }
}
