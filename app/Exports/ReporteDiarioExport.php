<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ReporteDiarioExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    public function styles(Worksheet $sheet)
    {
        // Encabezados principales
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType('solid')->getStartColor()->setRGB('2563eb');
        $sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('FFFFFF');
        // Bordes para toda la hoja
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)
            ->getBorders()->getAllBorders()->setBorderStyle('thin')->getColor()->setRGB('2563eb');
        // Ajuste de ancho automático
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Logo empresa
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo Empresa');
                $drawing->setPath(public_path('images/logo.png'));
                $drawing->setHeight(60);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($event->sheet->getDelegate());

                // Información empresa
                $event->sheet->setCellValue('B1', config('app.name'));
                $event->sheet->setCellValue('B2', 'RUC: 20481234567');
                $event->sheet->setCellValue('B3', 'Av. Ejemplo 123, Lima, Perú');
                $event->sheet->setCellValue('B4', config('app.env') == 'local' ? 'Demo' : 'Producción');
                $event->sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('2563eb');
                $event->sheet->getStyle('B2:B4')->getFont()->setSize(12);

                // Colorear encabezados dinámicamente
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                for ($row = 1; $row <= $highestRow; $row++) {
                    $firstCell = $event->sheet->getCell('A'.$row)->getValue();
                    if (
                        stripos($firstCell, 'Año') !== false ||
                        stripos($firstCell, 'Ventas por Producto') !== false ||
                        stripos($firstCell, 'Compras por Producto') !== false ||
                        stripos($firstCell, 'Producto') !== false
                    ) {
                        $event->sheet->getStyle('A'.$row.':'.$highestColumn.$row)->getFont()->setBold(true);
                        $event->sheet->getStyle('A'.$row.':'.$highestColumn.$row)->getFill()->setFillType('solid')->getStartColor()->setRGB('2563eb');
                        $event->sheet->getStyle('A'.$row.':'.$highestColumn.$row)->getFont()->getColor()->setRGB('FFFFFF');
                    }
                }
            }
        ];
    }
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        // Quita los encabezados para WithHeadings
        return array_filter($this->data, function($row) {
            return is_array($row) && count($row) > 1;
        });
    }

    public function headings(): array
    {
        // Primer fila como encabezados
        return $this->data[0] ?? [];
    }
}
