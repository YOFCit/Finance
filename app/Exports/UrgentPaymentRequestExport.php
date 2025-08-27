<?php

namespace App\Exports;

use App\Models\UrgentPaymentRequest;
use App\Models\ApprovalPerson;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UrgentPaymentRequestExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithEvents
{
  private $requests;
  private $approver;

  public function collection()
  {
    $this->requests = UrgentPaymentRequest::all();
    $this->approver = ApprovalPerson::first();
    return $this->requests;
  }

  public function headings(): array
  {
    return [
      'Request Date',
      'Requestor',
      'Expense No',
      'Department',
      'Supplier',
      'Amount',
      'Currency',
      'Payment Due Date',
      'Description',
      'Justification',
      'Cause',
      'Risk',
      'Causing Department',
      'Approved by',
      'Reason',
      'Status',
    ];
  }

  public function map($request): array
  {
    // Normalizamos el status
    $status = match (strtolower($request->status ?? '')) {
      'approve' => 'Approved',
      'reject' => 'Rejected',
      'in review' => 'In review',
      default => '',
    };

    // Nombre del requestor desde el modelo Usuarios
    $requestorUser = \App\Models\Usuarios::where('requestor', $request->requestor)->first();
    $requestorName = $requestorUser->name ?? $request->requestor;

    // Nombre del aprobador desde el modelo Usuarios
    $approvedBy = '';
    if ($status === 'Approved' || $status === 'Rejected') {
      $approverUser = \App\Models\Usuarios::where('id', $this->approver->user_id ?? 0)->first();
      $approvedBy = $approverUser->name ?? $this->approver->approved_by ?? '';
    }

    return [
      $request->request_date ? date('d/m/Y', strtotime($request->request_date)) : '',
      $requestorName,
      $request->expense_no,
      $request->department,
      $request->supplier,
      number_format($request->amount, 2),
      $request->currency,
      $request->payment_due_date ? date('d/m/Y', strtotime($request->payment_due_date)) : '',
      $request->description,
      $request->justification,
      $request->cause,
      $request->risk,
      $request->causing_department,
      $approvedBy,
      $request->reason ?? '',
      $status,
    ];
  }


  public function columnWidths(): array
  {
    return [
      'A' => 15,
      'B' => 20,
      'C' => 15,
      'D' => 18,
      'E' => 20,
      'F' => 12,
      'G' => 10,
      'H' => 12,
      'I' => 18,
      'J' => 40,
      'K' => 40,
      'L' => 40,
      'M' => 30,
      'N' => 20,
      'O' => 40, // Reason
      'P' => 15, // Status
    ];
  }

  public function styles(Worksheet $sheet)
  {
    $sheet->getStyle('A1:P1')->applyFromArray([
      'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4F81BD'],
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ],
    ]);

    $sheet->getRowDimension(1)->setRowHeight(25);

    $count = $this->requests->count();
    $sheet->getStyle("A2:A" . ($count + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("F2:F" . ($count + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle("G2:G" . ($count + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("H2:H" . ($count + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    foreach (['I', 'J', 'K', 'L', 'M', 'N', 'O'] as $col) {
      $sheet->getStyle("{$col}2:{$col}" . ($count + 1))->applyFromArray([
        'alignment' => [
          'wrapText' => true,
          'vertical' => Alignment::VERTICAL_TOP,
          'horizontal' => Alignment::HORIZONTAL_LEFT,
        ]
      ]);
    }

    for ($row = 2; $row <= $count + 1; $row++) {
      $sheet->getRowDimension($row)->setRowHeight(35);
    }

    return [];
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();
        $lastRow = $this->requests->count() + 1;
        $range = 'A1:P' . $lastRow;

        // Bordes
        $sheet->getStyle($range)->applyFromArray([
          'borders' => [
            'allBorders' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => ['rgb' => 'CCCCCC'],
            ],
          ],
        ]);

        // AutoFilter
        $sheet->setAutoFilter($range);

        // AutoSize columnas
        foreach (range('A', 'H') as $col) {
          $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        foreach (['I', 'J', 'K', 'L', 'M', 'N', 'O'] as $col) {
          $sheet->getColumnDimension($col)->setWidth(40);
        }

        $sheet->getColumnDimension('P')->setWidth(15);

        // 🔹 Colorear status según valor
        for ($row = 2; $row <= $lastRow; $row++) {
          $statusCell = "P{$row}";
          $status = $sheet->getCell($statusCell)->getValue();

          if (strtolower($status) === 'rejected') {
            $sheet->getStyle($statusCell)->applyFromArray([
              'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
              'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF0000'], // rojo
              ],
              'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
              ],
            ]);
          } elseif (strtolower($status) === 'approved') {
            $sheet->getStyle($statusCell)->applyFromArray([
              'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
              'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '00B050'], // verde
              ],
              'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
              ],
            ]);
          } else {
            $sheet->getStyle($statusCell)->applyFromArray([
              'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
              'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFD700'], // amarillo
              ],
              'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
              ],
            ]);
          }
        }
      },
    ];
  }
}
