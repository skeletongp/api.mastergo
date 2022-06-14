<?php

namespace App\Http\Controllers;

use App\Models\CountMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ReportController extends Controller
{
   public function general_daily()
   {
       return view('pages.reports.general_daily');
   }
   public function general_mayor()
   {
       return view('pages.reports.general_mayor');
   }
   public function catalogue()
   {
    return view('pages.reports.catalogue');
   }
   public function view_catalogue()
   {
    $PDF = App::make('dompdf.wrapper');
    $counts=CountMain::with('counts')->get();
    $data = [
        'ctaControls'=>$counts,
     
    ];
    $pdf = $PDF->loadView('pages.reports.pdf-catalogue', $data);
    file_put_contents('storage/cuadres/' . 'catalogo'.date('YmdHi'). '.pdf', $pdf->output());
    $path = asset('storage/cuadres/' . 'catalogo'.date('YmdHi'). '.pdf');
    
    return view('pages.reports.view_catalogue', ['pdf'=>$path]);
   }
}
