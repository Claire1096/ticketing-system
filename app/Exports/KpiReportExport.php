<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Support\BusinessHours;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KpiReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Ticket::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Ticket ID',
            'Submitted By',
            'Department',
            'Category',
            'Priority',
            'Status',
            'Submitted On',
            'First Response',
            'Resolved On',
            'Response Time (hrs, business hours)',
           'Resolution Time (hrs, business hours, from acknowledgment)',
        ];
    }

   public function map($ticket): array
{
    return [
        '#' . str_pad($ticket->id, 4, '0', STR_PAD_LEFT),
        $ticket->submittedBy->name ?? '—',
        $ticket->department ?? '—',
        $ticket->category,
        $ticket->priority,
        $ticket->status,
        $ticket->created_at->format('Y-m-d H:i'),
        $ticket->first_response_at?->format('Y-m-d H:i') ?? '—',
        $ticket->resolved_at?->format('Y-m-d H:i') ?? '—',
        $ticket->first_response_at
            ? BusinessHours::diffInHours($ticket->created_at, $ticket->first_response_at)
            : '—',
        ($ticket->resolved_at && $ticket->first_response_at)
            ? BusinessHours::diffInHours($ticket->first_response_at, $ticket->resolved_at)
            : '—',
    ];
}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}