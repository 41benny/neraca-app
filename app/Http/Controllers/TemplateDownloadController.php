<?php

namespace App\Http\Controllers;

// CSV endpoints removed; only XLSX templates are provided.
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TemplateDownloadController extends Controller
{
    public function coaXlsx(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('COA');

        $headers = [
            'code', 'name', 'parent_code', 'level', 'normal_balance', 'account_type', 'is_cash_account', 'description', 'report_type', 'group_name', 'side', 'sign', 'display_order', 'opening_debit', 'opening_credit', 'opening_as_of', 'branch_code',
        ];

        foreach ($headers as $i => $h) {
            $col = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col.'1', $h);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rows = [
            ['1100', 'Kas dan Bank', '1000', 2, 'debit', 'asset.cash', 1, 'Rekening kas utama', 'balance_sheet', 'Kas dan Setara Kas', 'asset', 1, 10, 25000000, 0, '2025-01-01', ''],
            ['2100', 'Hutang Usaha', '2000', 2, 'credit', 'liability.payable', 0, '', 'balance_sheet', 'Hutang Usaha', 'liability', -1, 40, 0, 8000000, '2025-01-01', ''],
        ];

        $rowIdx = 2;
        foreach ($rows as $row) {
            foreach ($row as $colIdx => $val) {
                $col = Coordinate::stringFromColumnIndex($colIdx + 1);
                $sheet->setCellValue($col.$rowIdx, $val);
            }
            $rowIdx++;
        }

        // Simple validations on a couple of columns for the first data row
        $this->setListValidation($sheet, 'E2', ['debit', 'credit']);
        $this->setListValidation($sheet, 'I2', ['balance_sheet', 'income_statement', 'cash_flow']);
        $this->setListValidation($sheet, 'K2', ['asset', 'liability', 'equity', 'revenue', 'expense']);

        // Instruction sheet
        $info = $spreadsheet->createSheet();
        $info->setTitle('Keterangan');
        $info->fromArray([
            ['Kolom', 'Tipe', 'Wajib', 'Contoh', 'Deskripsi'],
            ['code', 'text', 'Ya', '1100', 'Kode akun unik'],
            ['name', 'text', 'Ya', 'Kas dan Bank', 'Nama akun'],
            ['parent_code', 'text', 'Tidak', '1000', 'Kode induk (jika ada)'],
            ['level', 'number', 'Tidak', '2', 'Level akun (1-4)'],
            ['normal_balance', 'enum', 'Ya', 'debit', 'debit|credit'],
            ['account_type', 'text', 'Ya', 'asset.cash', 'Tipe akuntansi untuk grouping'],
            ['is_cash_account', 'boolean', 'Tidak', '1', '1/0 untuk akun kas'],
            ['description', 'text', 'Tidak', '', 'Catatan akun'],
            ['report_type', 'enum', 'Tidak', 'balance_sheet', 'Jenis laporan'],
            ['group_name', 'text', 'Tidak', 'Kas dan Setara Kas', 'Nama grup laporan'],
            ['side', 'enum', 'Tidak', 'asset', 'asset|liability|equity|revenue|expense'],
            ['sign', 'number', 'Tidak', '1', '1 untuk tambah, -1 untuk kurangi'],
            ['display_order', 'number', 'Tidak', '10', 'Urutan tampil'],
            ['opening_debit', 'number', 'Tidak', '25000000', 'Saldo awal debit'],
            ['opening_credit', 'number', 'Tidak', '0', 'Saldo awal kredit'],
            ['opening_as_of', 'date', 'Tidak', '2025-01-01', 'Tanggal saldo awal'],
            ['branch_code', 'text', 'Tidak', 'CBG01', 'Kode cabang'],
        ], null, 'A1');
        $info->getColumnDimension('A')->setAutoSize(true);
        $info->getColumnDimension('B')->setAutoSize(true);
        $info->getColumnDimension('C')->setAutoSize(true);
        $info->getColumnDimension('D')->setAutoSize(true);
        $info->getColumnDimension('E')->setAutoSize(true);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'coa_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function journalXlsx(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jurnal');

        $headers = ['date', 'account_code', 'debit', 'credit', 'description', 'branch', 'invoice_id', 'project_id', 'vehicle_id', 'party_type', 'party_code', 'party_name'];
        foreach ($headers as $i => $h) {
            $col = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col.'1', $h);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rows = [
            ['2025-01-10', '1100', 0, 250000, 'Transfer ke bank', 'CBG01', 'INV-001', '', '', 'customer', 'CUST-001', 'PT Pelanggan Jaya'],
            ['2025-01-10', '4100', 250000, 0, 'Penjualan', 'CBG01', 'INV-001', '', '', 'customer', 'CUST-001', 'PT Pelanggan Jaya'],
        ];
        $sheet->fromArray($rows, null, 'A2');

        $this->setListValidation($sheet, 'J2', ['customer', 'vendor']);

        $info = $spreadsheet->createSheet();
        $info->setTitle('Keterangan');
        $info->fromArray([
            ['Kolom', 'Tipe', 'Wajib', 'Contoh', 'Deskripsi'],
            ['date', 'date', 'Ya', '2025-01-10', 'Tanggal jurnal (YYYY-MM-DD atau tanggal Excel)'],
            ['account_code', 'text', 'Ya', '1100', 'Kode akun COA'],
            ['debit', 'number', 'Ya*', '250000', 'Angka; salah satu debit/kredit > 0'],
            ['credit', 'number', 'Ya*', '0', 'Angka; total debit=total kredit'],
            ['description', 'text', 'Ya', 'Penjualan', 'Keterangan jurnal'],
            ['branch', 'text', 'Tidak', 'CBG01', 'Kode cabang'],
            ['invoice_id', 'text', 'Tidak', 'INV-001', 'Nomor dokumen/invoice'],
            ['project_id', 'text', 'Tidak', '', 'Kode proyek'],
            ['vehicle_id', 'text', 'Tidak', '', 'Kode kendaraan'],
            ['party_type', 'enum', 'Tidak', 'customer', 'customer|vendor'],
            ['party_code', 'text', 'Tidak', 'CUST-001', 'Kode pihak'],
            ['party_name', 'text', 'Tidak', 'PT Pelanggan Jaya', 'Nama pihak'],
        ], null, 'A1');
        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
            $info->getColumnDimension($col)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'journal_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function setListValidation(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $cell, array $list): void
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"'.implode(',', $list).'"');
    }
}
