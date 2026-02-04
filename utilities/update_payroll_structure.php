<?php
/**
 * Script untuk mengupdate struktur tabel payroll ke format baru
 */

require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();

echo "Updating payroll table structure...\n\n";

try {
    // Drop foreign key constraints dari payroll_details dulu
    echo "1. Dropping foreign key constraints...\n";
    $db->query("ALTER TABLE payroll_details DROP FOREIGN KEY IF EXISTS payroll_details_ibfk_1");
    echo "   ✓ Done\n";
    
    // Drop tabel payroll_details dan payroll
    echo "\n2. Dropping old tables...\n";
    $db->query("DROP TABLE IF EXISTS payroll_details");
    echo "   ✓ payroll_details dropped\n";
    $db->query("DROP TABLE IF EXISTS payroll");
    echo "   ✓ payroll dropped\n";
    
    // Buat tabel payroll dengan struktur baru
    echo "\n3. Creating new payroll table...\n";
    $createPayroll = "CREATE TABLE payroll (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL,
        period_start DATE NOT NULL COMMENT 'Awal periode',
        period_end DATE NOT NULL COMMENT 'Akhir periode',
        total_pcs INT DEFAULT 0 COMMENT 'Total pcs semua produk',
        total_salary DECIMAL(15, 2) DEFAULT 0 COMMENT 'Total gaji periode ini',
        payment_date DATE,
        payment_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
        INDEX idx_employee (employee_id),
        INDEX idx_period (period_start, period_end),
        INDEX idx_payment_status (payment_status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->query($createPayroll);
    echo "   ✓ payroll table created\n";
    
    // Buat tabel payroll_details dengan struktur baru
    echo "\n4. Creating new payroll_details table...\n";
    $createDetails = "CREATE TABLE payroll_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        payroll_id INT NOT NULL,
        product_type_id INT NOT NULL,
        product_name VARCHAR(100) NOT NULL COMMENT 'Snapshot nama produk',
        work_type ENUM('rajut', 'linking') NOT NULL,
        pcs INT NOT NULL COMMENT 'Total pcs untuk item ini',
        rate_per_pcs DECIMAL(10, 4) NOT NULL COMMENT 'Snapshot tarif per pcs',
        subtotal DECIMAL(15, 2) NOT NULL COMMENT 'Subtotal = pcs × rate_per_pcs',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (payroll_id) REFERENCES payroll(id) ON DELETE CASCADE,
        FOREIGN KEY (product_type_id) REFERENCES product_types(id) ON DELETE CASCADE,
        INDEX idx_payroll (payroll_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->query($createDetails);
    echo "   ✓ payroll_details table created\n";
    
    echo "\n✓ Tables updated successfully!\n";
    echo "\nNew payroll structure:\n";
    $columns = $db->fetchAll("DESCRIBE payroll");
    foreach ($columns as $col) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
}
