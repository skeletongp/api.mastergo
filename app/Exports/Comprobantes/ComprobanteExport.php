<?php

namespace App\Exports\Comprobantes;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ComprobanteExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $store = auth()->user()->store;
       $invoices= $store->invoices()->has('comprobante')
       ->where('type', '!=', 'B02')
       ->orWhereHas('payment', function ($query) {
           $query->where('total', '>', 250000);
       })
       ->with('comprobante','client','payment','payments')->get();
        return $invoices;
    }
    public function headings(): array
    {
        return [
            'RNC/Cédula',
            'Tipo de Identificación',
            'NCF Asignado',
            'NCF Modificado',
            'Tipo de Ingreso',
            'Fecha Comprobante',
            'Fecha Retención',
            'Monto Facturado',
            'ITBIS Facturado',
            'Ret. Rentas  por Terceros',
            'ISR Percibido',
            'Impuesto Selectivo al Consumo',
            'Otros Impuestos',
            'Propina Legal',
            'Efectivo',
            'Cheque/Transferencia',
            'Tarjeta de Crédito',
            'Crédito',
            'Bonos de Regalo',
            'Permutas',
            'Otras Formas de Venta',
        ];
    }
    public function map($invoice): array
    {
        $docId = str_replace('-', '', $invoice->rnc ?: $invoice->client->rnc);
        return [
            $docId,
            strlen($docId) == 9 ? 1 : 2,
            $invoice->comprobante->ncf,
            '',
            1,
            Carbon::parse($invoice->day)->format('Ymd'),
            Carbon::parse($invoice->day)->format('Ymd'),
            $invoice->payment->total - $invoice->payment->tax,
           $invoice->payment->tax,
           '',
           '',
           '',
           '',
           '',
           $invoice->payments()->sum('efectivo'),
           $invoice->payments()->sum('transferencia'),
           $invoice->payments()->sum('tarjeta'),
           $invoice->rest,
           '',
           '',
           '',
        ];
    }
    public function stylesArray(): array
    {
        return [
            // Style the first row as bold text.
            'A1:U1'   => [
                'font' => [
                    'bold' => false,

                    'size' => '12'
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],


            ],
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Reporte 607');
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->getColumnDimension('Q')->setWidth(15);
        $sheet->getColumnDimension('R')->setWidth(15);
        $sheet->getColumnDimension('S')->setWidth(15);
        $sheet->getColumnDimension('T')->setWidth(15);
        $sheet->getColumnDimension('U')->setWidth(15);
        /* 
        $sheet->setCellValue('A', 4, ' CÁLCULO FINAL '); */

        /* Estilos de Bordes */
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '1EB8CE'],
                ],
            ],

        ];
        /* Estilos de Encabezados Fondo Oscuro */
        $headerStyle = [
            'fillType' => 'solid', 'rotation' => 0,
            'color' => ['rgb' => '5FCDDC'],
        ];

        /* Estilo de Encabeados Fondo Claro */
        $titleStyle = [
            'fillType' => 'solid', 'rotation' => 0,
            'color' => ['rgb' => 'B7DEE8'],
        ];

        $sheet->getStyle('A1:U1')->getFill()->applyFromArray($titleStyle);
        $sheet->getStyle('A1:U1')->getAlignment()->setWrapText(true);
        return $this->stylesArray();
    }
  
}
