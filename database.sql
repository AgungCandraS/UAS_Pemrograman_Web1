-- ============================================
-- Bisnisku Database Schema
-- MySQL Database for Bisnisku Web Application
-- ============================================

-- Drop database if exists and create new
DROP DATABASE IF EXISTS bisnisku_db;
CREATE DATABASE bisnisku_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bisnisku_db;

-- ============================================
-- Table: users
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    avatar VARCHAR(255),
    role ENUM('admin', 'manager', 'employee') DEFAULT 'employee',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: categories (for products)
-- ============================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: products (inventory)
-- ============================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    sku VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    price DECIMAL(15, 2) NOT NULL DEFAULT 0,
    cost_price DECIMAL(15, 2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    min_stock INT NOT NULL DEFAULT 10,
    unit VARCHAR(20) DEFAULT 'pcs',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_sku (sku),
    INDEX idx_category (category_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: stock_movements
-- ============================================
CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    type ENUM('in', 'out', 'adjustment') NOT NULL,
    quantity INT NOT NULL,
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_product (product_id),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: customers
-- ============================================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: orders
-- ============================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    customer_address TEXT,
    subtotal DECIMAL(15, 2) NOT NULL DEFAULT 0,
    tax DECIMAL(15, 2) NOT NULL DEFAULT 0,
    discount DECIMAL(15, 2) NOT NULL DEFAULT 0,
    total DECIMAL(15, 2) NOT NULL DEFAULT 0,
    payment_method ENUM('cash', 'transfer', 'credit_card', 'e-wallet') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'partial', 'refunded') DEFAULT 'pending',
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order_number (order_number),
    INDEX idx_customer (customer_id),
    INDEX idx_status (order_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: order_items
-- ============================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    subtotal DECIMAL(15, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: employees
-- ============================================
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    employee_id VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    position VARCHAR(100),
    department VARCHAR(100),
    hire_date DATE,
    salary DECIMAL(15, 2) DEFAULT 0,
    status ENUM('active', 'inactive', 'resigned') DEFAULT 'active',
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_employee_id (employee_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: attendance
-- ============================================
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME,
    check_out TIME,
    status ENUM('present', 'absent', 'late', 'leave', 'sick') DEFAULT 'present',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (employee_id, date),
    INDEX idx_date (date),
    INDEX idx_employee (employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: payroll
-- ============================================
CREATE TABLE payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    period_month INT NOT NULL,
    period_year INT NOT NULL,
    basic_salary DECIMAL(15, 2) NOT NULL DEFAULT 0,
    allowances DECIMAL(15, 2) NOT NULL DEFAULT 0,
    deductions DECIMAL(15, 2) NOT NULL DEFAULT 0,
    net_salary DECIMAL(15, 2) NOT NULL DEFAULT 0,
    payment_date DATE,
    status ENUM('pending', 'paid') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    UNIQUE KEY unique_payroll (employee_id, period_month, period_year),
    INDEX idx_period (period_year, period_month),
    INDEX idx_employee (employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: transactions (finance)
-- ============================================
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('income', 'expense') NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    description TEXT,
    reference_type VARCHAR(50),
    reference_id INT,
    transaction_date DATE NOT NULL,
    payment_method ENUM('cash', 'transfer', 'credit_card', 'e-wallet') DEFAULT 'cash',
    attachment VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_type (type),
    INDEX idx_date (transaction_date),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: ai_conversations
-- ============================================
CREATE TABLE ai_conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    response TEXT,
    context JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: settings
-- ============================================
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Default Data
-- ============================================

-- Default Admin User (password: admin123)
INSERT INTO users (full_name, email, password, phone, role, status) VALUES
('Administrator', 'admin@bisnisku.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5UpODRVKEQKzm', '081234567890', 'admin', 'active');

-- Default Categories
INSERT INTO categories (name, description, icon) VALUES
('Elektronik', 'Produk elektronik dan gadget', 'fa-laptop'),
('Fashion', 'Pakaian dan aksesoris', 'fa-tshirt'),
('Makanan & Minuman', 'Produk konsumsi', 'fa-utensils'),
('Furniture', 'Perabot rumah tangga', 'fa-couch'),
('Kesehatan', 'Produk kesehatan', 'fa-heartbeat');

-- Default Settings
INSERT INTO settings (key_name, value, description) VALUES
('company_name', 'Bisnisku', 'Nama perusahaan'),
('company_email', 'info@bisnisku.com', 'Email perusahaan'),
('company_phone', '081234567890', 'Telepon perusahaan'),
('company_address', 'Jl. Bisnis No. 123, Jakarta', 'Alamat perusahaan'),
('tax_rate', '11', 'Persentase pajak (PPN)'),
('currency', 'IDR', 'Mata uang'),
('timezone', 'Asia/Jakarta', 'Zona waktu');

-- ============================================
-- Views for Quick Reports
-- ============================================

-- View: Daily Sales
CREATE VIEW daily_sales AS
SELECT 
    DATE(created_at) as sale_date,
    COUNT(*) as total_orders,
    SUM(total) as total_sales,
    AVG(total) as avg_order_value
FROM orders
WHERE payment_status = 'paid'
GROUP BY DATE(created_at);

-- View: Product Stock Status
CREATE VIEW product_stock_status AS
SELECT 
    p.id,
    p.name,
    p.sku,
    p.stock,
    p.min_stock,
    CASE 
        WHEN p.stock = 0 THEN 'Out of Stock'
        WHEN p.stock <= p.min_stock THEN 'Low Stock'
        ELSE 'In Stock'
    END as stock_status,
    c.name as category_name
FROM products p
LEFT JOIN categories c ON p.category_id = c.id;

-- View: Monthly Finance Summary
CREATE VIEW monthly_finance AS
SELECT 
    YEAR(transaction_date) as year,
    MONTH(transaction_date) as month,
    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
    SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as net_profit
FROM transactions
GROUP BY YEAR(transaction_date), MONTH(transaction_date);

-- ============================================
-- Stored Procedures
-- ============================================

DELIMITER //

-- Procedure: Generate Order Number
CREATE PROCEDURE generate_order_number(OUT order_num VARCHAR(50))
BEGIN
    DECLARE num INT;
    DECLARE date_prefix VARCHAR(10);
    
    SET date_prefix = DATE_FORMAT(NOW(), '%Y%m%d');
    
    SELECT COUNT(*) + 1 INTO num 
    FROM orders 
    WHERE order_number LIKE CONCAT(date_prefix, '%');
    
    SET order_num = CONCAT('ORD-', date_prefix, '-', LPAD(num, 4, '0'));
END //

-- Procedure: Calculate Monthly Salary
CREATE PROCEDURE calculate_monthly_salary(
    IN emp_id INT,
    IN month INT,
    IN year INT
)
BEGIN
    DECLARE basic_sal DECIMAL(15,2);
    DECLARE present_days INT;
    DECLARE working_days INT;
    DECLARE calculated_salary DECIMAL(15,2);
    
    -- Get basic salary
    SELECT salary INTO basic_sal FROM employees WHERE id = emp_id;
    
    -- Count present days
    SELECT COUNT(*) INTO present_days 
    FROM attendance 
    WHERE employee_id = emp_id 
    AND MONTH(date) = month 
    AND YEAR(date) = year
    AND status IN ('present', 'late');
    
    -- Assume 26 working days per month
    SET working_days = 26;
    
    -- Calculate proportional salary
    SET calculated_salary = (basic_sal / working_days) * present_days;
    
    -- Insert or update payroll
    INSERT INTO payroll (employee_id, period_month, period_year, basic_salary, net_salary)
    VALUES (emp_id, month, year, basic_sal, calculated_salary)
    ON DUPLICATE KEY UPDATE 
        basic_salary = basic_sal,
        net_salary = calculated_salary;
END //

DELIMITER ;

-- ============================================
-- Triggers
-- ============================================

DELIMITER //

-- Trigger: Update stock after order
CREATE TRIGGER after_order_item_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock - NEW.quantity 
    WHERE id = NEW.product_id;
    
    INSERT INTO stock_movements (product_id, type, quantity, notes)
    VALUES (NEW.product_id, 'out', NEW.quantity, CONCAT('Order #', NEW.order_id));
END //

-- Trigger: Record transaction on paid order
CREATE TRIGGER after_order_paid
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.payment_status = 'paid' AND OLD.payment_status != 'paid' THEN
        INSERT INTO transactions (type, category, amount, description, reference_type, reference_id, transaction_date, payment_method)
        VALUES ('income', 'Sales', NEW.total, CONCAT('Order ', NEW.order_number), 'order', NEW.id, NOW(), NEW.payment_method);
    END IF;
END //

DELIMITER ;

-- ============================================
-- Indexes for Performance
-- ============================================

-- Additional indexes for better query performance
CREATE INDEX idx_created_at ON orders(created_at);
CREATE INDEX idx_transaction_date ON transactions(transaction_date);
CREATE INDEX idx_attendance_date ON attendance(date);
CREATE INDEX idx_product_stock ON products(stock);

-- ============================================
-- End of Schema
-- ============================================
