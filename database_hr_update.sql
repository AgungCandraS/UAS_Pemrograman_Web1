-- ============================================
-- UPDATE DATABASE FOR HR - SISTEM GAJI BORONGAN
-- ============================================
-- Rumus: 1 lusin = 12 pcs | Gaji = Σ(pcs × tarif_per_pcs)
-- ============================================

-- Table: product_types (Jenis Produk dengan Tarif Rajut & Linking)
CREATE TABLE IF NOT EXISTS product_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Nama produk: Belle, K5, K4, Kulot',
    rajut_rate DECIMAL(10, 4) NOT NULL COMMENT 'Tarif rajut per pcs (misal: 67/12 = 5.5833)',
    linking_rate DECIMAL(10, 4) NOT NULL COMMENT 'Tarif linking per pcs (misal: 30/12 = 2.5)',
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: work_records (Catatan Produksi Harian/Mingguan)
CREATE TABLE IF NOT EXISTS work_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    product_type_id INT NOT NULL,
    work_type ENUM('rajut', 'linking') NOT NULL COMMENT 'Jenis pekerjaan: rajut atau linking',
    date DATE NOT NULL,
    dozens DECIMAL(10, 2) DEFAULT 0 COMMENT 'Jumlah dalam lusin (optional)',
    pcs INT NOT NULL COMMENT 'Jumlah dalam pcs (otomatis: dozens × 12)',
    rate_per_pcs DECIMAL(10, 4) NOT NULL COMMENT 'Snapshot tarif saat input',
    subtotal DECIMAL(15, 2) NOT NULL COMMENT 'Subtotal = pcs × rate_per_pcs',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (product_type_id) REFERENCES product_types(id) ON DELETE CASCADE,
    INDEX idx_employee_date (employee_id, date),
    INDEX idx_product (product_type_id),
    INDEX idx_date (date),
    INDEX idx_work_type (work_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: payroll (Slip Gaji)
CREATE TABLE IF NOT EXISTS payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    period_start DATE NOT NULL COMMENT 'Awal periode (misal: Senin)',
    period_end DATE NOT NULL COMMENT 'Akhir periode (misal: Minggu)',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: payroll_details (Detail Per Item Produksi di Slip Gaji)
CREATE TABLE IF NOT EXISTS payroll_details (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert product types dengan tarif sesuai data
INSERT INTO product_types (name, rajut_rate, linking_rate, description) VALUES
('Belle', 67/12, 30/12, 'Produk Belle - Rajut: Rp 5.583/pcs, Linking: Rp 2.5/pcs'),
('K5', 57/12, 28/12, 'Produk K5 - Rajut: Rp 4.75/pcs, Linking: Rp 2.333/pcs'),
('K4', 42/12, 22/12, 'Produk K4 - Rajut: Rp 3.5/pcs, Linking: Rp 1.833/pcs'),
('Kulot', 50/12, 22/12, 'Produk Kulot - Rajut: Rp 4.167/pcs, Linking: Rp 1.833/pcs');

-- Modify employees table (jika belum ada kolom work_type)
-- ALTER TABLE employees ADD COLUMN work_type VARCHAR(50) DEFAULT 'borongan' COMMENT 'Tipe karyawan: borongan/tetap';
