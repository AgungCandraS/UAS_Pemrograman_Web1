<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $sale['sale_number'] ?> - <?= APP_NAME ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        
        .company-info h1 {
            color: #2563eb;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #666;
            font-size: 11px;
            margin: 2px 0;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-meta h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .invoice-meta p {
            color: #666;
            font-size: 11px;
            margin: 3px 0;
        }
        
        .invoice-number {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .info-box {
            flex: 1;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            margin-right: 15px;
        }
        
        .info-box:last-child {
            margin-right: 0;
        }
        
        .info-box h3 {
            font-size: 12px;
            color: #2563eb;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .info-box p {
            margin: 5px 0;
            font-size: 11px;
        }
        
        .info-box strong {
            color: #333;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .badge-online {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-offline {
            background: #e5e7eb;
            color: #374151;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #f1f5f9;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            border-bottom: 2px solid #cbd5e1;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        
        table tbody tr:hover {
            background: #f8fafc;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary-box {
            float: right;
            width: 350px;
            margin-top: 20px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 15px;
            font-size: 12px;
        }
        
        .summary-row.total {
            background: #2563eb;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border-radius: 6px;
            margin-top: 10px;
        }
        
        .summary-row.admin-fee {
            background: #fef2f2;
            color: #991b1b;
            border-radius: 4px;
        }
        
        .summary-row.final-total {
            background: #dcfce7;
            color: #166534;
            font-weight: bold;
            font-size: 16px;
            border-radius: 6px;
            margin-top: 5px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            clear: both;
        }
        
        .qr-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .qr-code {
            text-align: center;
        }
        
        .qr-code img {
            width: 120px;
            height: 120px;
            border: 2px solid #e5e7eb;
            padding: 8px;
            border-radius: 8px;
        }
        
        .qr-code p {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        
        .notes-section {
            flex: 1;
            margin-right: 20px;
        }
        
        .notes-section h4 {
            font-size: 12px;
            color: #2563eb;
            margin-bottom: 8px;
        }
        
        .notes-section p {
            font-size: 11px;
            color: #666;
            line-height: 1.5;
        }
        
        .print-date {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #999;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                padding: 0;
            }
            
            @page {
                margin: 20mm;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1><?= APP_NAME ?></h1>
                <p>Management System</p>
                <p>Email: admin@bisnisku.com</p>
                <p>Telp: +62 812-3456-7890</p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p class="invoice-number"><?= $sale['sale_number'] ?></p>
                <p>Tanggal: <?= date('d F Y', strtotime($sale['created_at'])) ?></p>
                <p>Jam: <?= date('H:i', strtotime($sale['created_at'])) ?> WIB</p>
            </div>
        </div>
        
        <!-- Info Section -->
        <div class="info-section">
            <div class="info-box">
                <h3>Customer</h3>
                <p><strong><?= htmlspecialchars($sale['customer_name']) ?></strong></p>
                <?php if (!empty($sale['customer_phone'])): ?>
                <p>Telp: <?= htmlspecialchars($sale['customer_phone']) ?></p>
                <?php endif; ?>
                <?php if (!empty($sale['customer_address'])): ?>
                <p><?= htmlspecialchars($sale['customer_address']) ?></p>
                <?php endif; ?>
            </div>
            
            <div class="info-box">
                <h3>Detail Penjualan</h3>
                <p>
                    Tipe: 
                    <?php if ($sale['sale_type'] === 'online'): ?>
                        <span class="badge badge-online">ONLINE</span>
                    <?php else: ?>
                        <span class="badge badge-offline">OFFLINE</span>
                    <?php endif; ?>
                </p>
                <?php if ($sale['sale_type'] === 'online' && !empty($sale['platform_name'])): ?>
                <p>Platform: <strong><?= htmlspecialchars($sale['platform_name']) ?></strong></p>
                <?php endif; ?>
                <p>Dibuat oleh: <?= htmlspecialchars($sale['created_by_name'] ?? 'System') ?></p>
            </div>
        </div>
        
        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 45%;">Produk</th>
                    <th style="width: 20%;" class="text-right">Harga</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 20%;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($items as $item): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                        <?php if (!empty($item['product_sku'])): ?>
                        <br><small style="color: #666;">SKU: <?= htmlspecialchars($item['product_sku']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?= format_currency($item['price']) ?></td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-right"><strong><?= format_currency($item['subtotal']) ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="summary-box">
            <div class="summary-row">
                <span>Subtotal:</span>
                <strong><?= format_currency($sale['subtotal']) ?></strong>
            </div>
            
            <?php if ($sale['tax'] > 0): ?>
            <div class="summary-row">
                <span>Pajak:</span>
                <strong><?= format_currency($sale['tax']) ?></strong>
            </div>
            <?php endif; ?>
            
            <?php if ($sale['discount'] > 0): ?>
            <div class="summary-row">
                <span>Diskon:</span>
                <strong style="color: #dc2626;">- <?= format_currency($sale['discount']) ?></strong>
            </div>
            <?php endif; ?>
            
            <div class="summary-row total">
                <span>Total:</span>
                <span><?= format_currency($sale['total']) ?></span>
            </div>
            
            <?php if ($sale['sale_type'] === 'online' && isset($sale['admin_fee']) && $sale['admin_fee'] > 0): ?>
            <div class="summary-row admin-fee">
                <span>
                    Potongan Admin
                    <?php if (!empty($sale['platform_fee_percentage'])): ?>
                        (<?= $sale['platform_fee_percentage'] ?>%)
                    <?php endif; ?>
                </span>
                <strong>- <?= format_currency($sale['admin_fee']) ?></strong>
            </div>
            <?php endif; ?>
            
            <div class="summary-row final-total">
                <span>Total Diterima:</span>
                <span><?= format_currency($sale['final_total']) ?></span>
            </div>
        </div>
        
        <div style="clear: both;"></div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="qr-section">
                <?php if (!empty($sale['notes'])): ?>
                <div class="notes-section">
                    <h4>Catatan:</h4>
                    <p><?= nl2br(htmlspecialchars($sale['notes'])) ?></p>
                </div>
                <?php endif; ?>
                
                <div class="qr-code">
                    <?php
                    // Build invoice data for QR code
                    $qrData = [
                        'invoice' => $sale['sale_number'],
                        'date' => date('d/m/Y H:i', strtotime($sale['created_at'])),
                        'customer' => $sale['customer_name'],
                        'type' => $sale['sale_type'] === 'online' ? 'Online' : 'Offline',
                        'items' => count($items),
                        'subtotal' => 'Rp ' . number_format($sale['subtotal'], 0, ',', '.'),
                        'total' => 'Rp ' . number_format($sale['total'], 0, ',', '.'),
                        'received' => 'Rp ' . number_format($sale['final_total'], 0, ',', '.'),
                    ];
                    
                    if ($sale['sale_type'] === 'online' && !empty($sale['platform_name'])) {
                        $qrData['platform'] = $sale['platform_name'];
                    }
                    
                    if (isset($sale['admin_fee']) && $sale['admin_fee'] > 0) {
                        $qrData['admin_fee'] = 'Rp ' . number_format($sale['admin_fee'], 0, ',', '.');
                    }
                    
                    // Format QR data as readable text
                    $qrText = "=== INVOICE BISNISKU ===\n";
                    $qrText .= "No: " . $qrData['invoice'] . "\n";
                    $qrText .= "Tanggal: " . $qrData['date'] . "\n";
                    $qrText .= "Customer: " . $qrData['customer'] . "\n";
                    $qrText .= "Tipe: " . $qrData['type'] . "\n";
                    if (isset($qrData['platform'])) {
                        $qrText .= "Platform: " . $qrData['platform'] . "\n";
                    }
                    $qrText .= "Jumlah Item: " . $qrData['items'] . "\n";
                    $qrText .= "---\n";
                    $qrText .= "Subtotal: " . $qrData['subtotal'] . "\n";
                    if (isset($qrData['admin_fee'])) {
                        $qrText .= "Potongan: -" . $qrData['admin_fee'] . "\n";
                    }
                    $qrText .= "Total Diterima: " . $qrData['received'] . "\n";
                    $qrText .= "---\n";
                    $qrText .= "Terima kasih!\n";
                    $qrText .= "Verifikasi: " . base_url('sales/view/' . $sale['id']);
                    
                    $qrEncoded = urlencode($qrText);
                    ?>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $qrEncoded ?>" alt="QR Code">
                    <p>Scan untuk detail invoice</p>
                </div>
            </div>
            
            <div class="print-date">
                Dicetak pada: <?= date('d F Y H:i:s') ?> WIB
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
