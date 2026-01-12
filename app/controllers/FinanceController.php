<?php
/**
 * Finance Controller
 */

class FinanceController {
    private $transactionModel;
    
    public function __construct() {
        require_auth();
        $this->transactionModel = new Transaction();
    }
    
    public function index() {
        $type = get('type', '');
        $dateFrom = get('date_from', '');
        $dateTo = get('date_to', '');
        
        $startDate = $dateFrom ?: date('Y-m-01');
        $endDate = $dateTo ?: date('Y-m-d');
        
        $summary = $this->transactionModel->getSummary($startDate, $endDate);
        $transactions = $this->transactionModel->getAll($type, $startDate, $endDate, 50);
        
        ob_start();
        include APP_PATH . '/views/finance/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Keuangan';
        $activePage = 'finance';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function create() {
        ob_start();
        include APP_PATH . '/views/finance/create.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Tambah Transaksi';
        $activePage = 'finance';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function edit($id = null) {
        if (!$id) {
            redirect(base_url('finance'));
        }
        
        $transaction = $this->transactionModel->getById($id);
        if (!$transaction) {
            flash('error', 'Transaksi tidak ditemukan');
            redirect(base_url('finance'));
        }
        
        ob_start();
        include APP_PATH . '/views/finance/edit.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Edit Transaksi';
        $activePage = 'finance';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function income() {
        $transactions = $this->transactionModel->getAll('income');
        
        ob_start();
        include APP_PATH . '/views/finance/income.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Pemasukan';
        $activePage = 'finance';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function expense() {
        $transactions = $this->transactionModel->getAll('expense');
        
        ob_start();
        include APP_PATH . '/views/finance/expense.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Pengeluaran';
        $activePage = 'finance';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function storeTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('finance'));
        }
        
        $data = [
            'type' => post('type'),
            'category' => post('category'),
            'amount' => post('amount'),
            'description' => post('description'),
            'transaction_date' => post('transaction_date'),
            'payment_method' => post('payment_method'),
            'created_by' => auth_user()['id']
        ];
        
        if ($this->transactionModel->create($data)) {
            flash('success', 'Transaksi berhasil ditambahkan');
        } else {
            flash('error', 'Gagal menambahkan transaksi');
        }
        
        redirect(base_url('finance'));
    }
    
    public function updateTransaction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('finance'));
        }
        
        if (!$id) {
            flash('error', 'ID Transaksi tidak valid');
            redirect(base_url('finance'));
        }
        
        $data = [
            'type' => post('type'),
            'category' => post('category'),
            'amount' => post('amount'),
            'description' => post('description'),
            'transaction_date' => post('transaction_date'),
            'payment_method' => post('payment_method')
        ];
        
        if ($this->transactionModel->update($id, $data)) {
            flash('success', 'Transaksi berhasil diperbarui');
        } else {
            flash('error', 'Gagal memperbarui transaksi');
        }
        
        redirect(base_url('finance'));
    }
    
    public function deleteTransaction($id) {
        if (!$id) {
            flash('error', 'ID Transaksi tidak valid');
            redirect(base_url('finance'));
        }
        
        if ($this->transactionModel->delete($id)) {
            flash('success', 'Transaksi berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus transaksi');
        }
        
        redirect(base_url('finance'));
    }
    
    public function report() {
        $startDate = get('start_date', date('Y-m-01'));
        $endDate = get('end_date', date('Y-m-d'));
        
        $summary = $this->transactionModel->getSummary($startDate, $endDate);
        $transactions = $this->transactionModel->getAll(null, $startDate, $endDate);
        
        ob_start();
        include APP_PATH . '/views/finance/report.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Laporan Keuangan';
        $activePage = 'finance';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function export() {
        $format = strtolower(get('format', 'excel'));
        $type = get('type', '');
        $startDate = get('date_from', date('Y-m-01'));
        $endDate = get('date_to', date('Y-m-d'));
        $transactions = $this->transactionModel->getAll($type, $startDate, $endDate, null, 0);
        $escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        require_once ROOT_PATH . '/vendor/autoload.php';

        try {
            if ($format === 'pdf') {
                $pdf = new \TCPDF();
                $pdf->SetCreator(APP_NAME);
                $pdf->SetAuthor(APP_NAME);
                $pdf->SetTitle('Financial Report');
                $pdf->AddPage();

                $html = '<h2>Laporan Keuangan</h2>';
                $html .= '<p>Periode: ' . $escape($startDate) . ' s.d. ' . $escape($endDate) . '</p>';
                $html .= '<table border="1" cellpadding="5"><thead><tr>';
                $html .= '<th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Nominal</th><th>Metode</th><th>Deskripsi</th>';
                $html .= '</tr></thead><tbody>';
                
                foreach ($transactions as $trx) {
                    $html .= '<tr>';
                    $html .= '<td>' . $escape(format_date($trx['transaction_date'])) . '</td>';
                    $html .= '<td>' . $escape(ucfirst($trx['type'])) . '</td>';
                    $html .= '<td>' . $escape($trx['category']) . '</td>';
                    $html .= '<td>' . $escape(format_currency($trx['amount'])) . '</td>';
                    $html .= '<td>' . $escape($trx['payment_method']) . '</td>';
                    $html .= '<td>' . $escape($trx['description']) . '</td>';
                    $html .= '</tr>';
                }
                
                $html .= '</tbody></table>';
                $pdf->writeHTML($html);
                $pdf->Output('finance_' . date('Ymd_His') . '.pdf', 'D');
                exit();
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Finance');
            $sheet->fromArray(['Tanggal', 'Tipe', 'Kategori', 'Nominal', 'Metode', 'Deskripsi'], null, 'A1');

            $row = 2;
            foreach ($transactions as $trx) {
                $sheet->setCellValue("A{$row}", $trx['transaction_date']);
                $sheet->setCellValue("B{$row}", $trx['type']);
                $sheet->setCellValue("C{$row}", $trx['category']);
                $sheet->setCellValue("D{$row}", $trx['amount']);
                $sheet->setCellValue("E{$row}", $trx['payment_method']);
                $sheet->setCellValue("F{$row}", $trx['description']);
                $row++;
            }

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'finance_' . date('Ymd_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit();
        } catch (Exception $e) {
            flash('error', 'Gagal mengekspor data: ' . $e->getMessage());
            redirect(base_url('finance'));
        }
    }
}
