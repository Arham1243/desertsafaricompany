<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController
{
    // Map resource names to models
    protected $modelMap = [
        'users' => User::class,
        'orders' => Order::class,
    ];

    protected $headerMap = [
        'is_billable' => 'Non Billable',
    ];

    public function export(Request $request)
    {
        $resource = $request->route('resource');
        $format = strtolower($request->input('format', 'excel'));
        $columns = $request->input('columns', []);

        if (! in_array($format, ['excel', 'pdf'])) {
            return response()->json(['error' => 'Invalid format'], 400);
        }

        if (! isset($this->modelMap[$resource])) {
            return response()->json(['message' => 'Invalid resource'], 400);
        }

        $modelClass = $this->modelMap[$resource];

        $data = $this->getData(
            $modelClass,
            $columns,
        );

        if ($format === 'excel') {
            return $this->exportExcel($resource, $data, $columns);
        }
    }

    protected function getData(
        $modelClass,
        array $columns,
    ) {
        // Extract relations from columns (e.g., 'user.name' -> 'user')
        $relations = collect($columns)
            ->filter(fn($col) => Str::contains($col, '.'))
            ->map(fn($col) => explode('.', $col)[0])
            ->unique()
            ->toArray();

        $query = $modelClass::query();

        $items = $query->get();

        // Extract and transform data
        return $items->map(function ($item) use ($columns) {
            return $this->extractAndTransformColumns($item, $columns);
        })->toArray();
    }

    protected function extractAndTransformColumns($item, array $columns)
    {
        $row = [];
        foreach ($columns as $col) {
            $value = null;

            $value = $item->$col ?? null;

            // Apply transformations
            $value = $this->transformValue($col, $value, $item);

            $row[$col] = $value;
        }

        return $row;
    }

    protected function transformValue($column, $value, $item)
    {
        // Apply column-specific transformations
        $processors = $this->getColumnProcessors();

        if (isset($processors[$column])) {
            return call_user_func($processors[$column], $value, $item);
        }

        return $value;
    }

    protected function getColumnProcessors(): array
    {
        return [
            'dob' => fn($val) => $val ? formatDate($val) : null,
            'age' => fn($val) => $val !== null ? (string) $val : null,
            'driver' => fn($val, $item) => $item->driver ? $item->driver->name : 'N/A',
            'advance_amount' => fn($val, $item) => $item->advance_amount ?? '-',
            'remaining_amount' => fn($val, $item) => $item->remaining_amount ?? $item->total_amount,
        ];
    }

    protected function exportExcel($resource, $data, $columns)
    {
        $filename = $resource . '.xlsx';
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        if (! empty($data)) {
            $headers = ! empty($columns) ? $columns : array_keys((array) $data[0]);

            // Set headers
            $col = 1;
            foreach ($headers as $header) {
                // Format header: remove '_id' except for 'booking_id', replace '_' with space, handle dots
                if ($header === 'booking_id') {
                    $label = 'Booking ID'; // keep as proper label
                } else {
                    $label = str_replace('.', ' ', $header);
                    $label = str_replace('_id', '', $label); // remove _id for others
                    $label = str_replace('_', ' ', $label); // replace remaining underscores with space
                }

                // Override with headerMap if exists
                $label = $this->headerMap[$header] ?? $label;

                $sheet->setCellValue(Coordinate::stringFromColumnIndex($col) . '1', $label);
                $col++;
            }

            // Set data rows
            $rowNum = 2;
            foreach ($data as $row) {
                $col = 1;
                foreach ($headers as $key) {
                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($col) . $rowNum,
                        $row[$key] ?? ''
                    );
                    $ignoreNumericFormat = ['age', 'booking_id'];

                    if (is_numeric($row[$key]) && !in_array($key, $ignoreNumericFormat)) {
                        $sheet
                            ->getStyle(Coordinate::stringFromColumnIndex($col) . $rowNum)
                            ->getNumberFormat()
                            ->setFormatCode('0.00');
                    }

                    $col++;
                }
                $rowNum++;
            }
        }

        $writer = new Xlsx($spreadsheet);

        // Prevent corrupt XLSX
        if (ob_get_length()) {
            ob_end_clean();
        }

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
