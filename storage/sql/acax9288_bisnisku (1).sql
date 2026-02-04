-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 05 Feb 2026 pada 01.37
-- Versi server: 11.4.9-MariaDB-cll-lve
-- Versi PHP: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acax9288_bisnisku`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`acax9288`@`localhost` PROCEDURE `calculate_admin_fee` (IN `p_sale_id` INT)   BEGIN
    DECLARE v_subtotal DOUBLE;
    DECLARE v_tax DOUBLE;
    DECLARE v_discount DOUBLE;
    DECLARE v_fee_percentage DOUBLE;
    DECLARE v_total DOUBLE;
    DECLARE v_admin_fee DOUBLE;
    DECLARE v_final_total DOUBLE;
    
    SELECT subtotal, tax, discount, admin_fee_percentage
    INTO v_subtotal, v_tax, v_discount, v_fee_percentage
    FROM sales WHERE id = p_sale_id;
    
    SET v_total = v_subtotal + v_tax - v_discount;
    SET v_admin_fee = v_total * (v_fee_percentage / 100);
    SET v_final_total = v_total - v_admin_fee;
    
    UPDATE sales 
    SET 
        total = v_total,
        admin_fee_amount = v_admin_fee,
        final_total = v_final_total
    WHERE id = p_sale_id;
END$$

CREATE DEFINER=`acax9288`@`localhost` PROCEDURE `calculate_monthly_salary` (IN `emp_id` INT, IN `month` INT, IN `year` INT)   BEGIN
    DECLARE basic_sal DECIMAL(15,2);
    DECLARE present_days INT;
    DECLARE working_days INT;
    DECLARE calculated_salary DECIMAL(15,2);
    
    
    SELECT salary INTO basic_sal FROM employees WHERE id = emp_id;
    
    
    SELECT COUNT(*) INTO present_days 
    FROM attendance 
    WHERE employee_id = emp_id 
    AND MONTH(date) = month 
    AND YEAR(date) = year
    AND status IN ('present', 'late');
    
    
    SET working_days = 26;
    
    
    SET calculated_salary = (basic_sal / working_days) * present_days;
    
    
    INSERT INTO payroll (employee_id, period_month, period_year, basic_salary, net_salary)
    VALUES (emp_id, month, year, basic_sal, calculated_salary)
    ON DUPLICATE KEY UPDATE 
        basic_salary = basic_sal,
        net_salary = calculated_salary;
END$$

CREATE DEFINER=`acax9288`@`localhost` PROCEDURE `generate_order_number` (OUT `order_num` VARCHAR(50))   BEGIN
    DECLARE num INT;
    DECLARE date_prefix VARCHAR(10);
    
    SET date_prefix = DATE_FORMAT(NOW(), '%Y%m%d');
    
    SELECT COUNT(*) + 1 INTO num 
    FROM orders 
    WHERE order_number LIKE CONCAT(date_prefix, '%');
    
    SET order_num = CONCAT('ORD-', date_prefix, '-', LPAD(num, 4, '0'));
END$$

CREATE DEFINER=`acax9288`@`localhost` PROCEDURE `sp_ai_dashboard_stats` (IN `p_user_id` INT)   BEGIN
    SELECT 
        (SELECT COUNT(*) FROM ai_conversations WHERE user_id = p_user_id) as total_conversations,
        (SELECT COUNT(*) FROM ai_insights_history WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as recent_insights,
        (SELECT COUNT(*) FROM ai_recommendations_log WHERE status = 'pending') as pending_recommendations,
        (SELECT AVG(user_rating) FROM ai_query_stats WHERE user_rating IS NOT NULL AND last_queried >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as avg_satisfaction;
END$$

CREATE DEFINER=`acax9288`@`localhost` PROCEDURE `sp_clean_ai_cache` ()   BEGIN
    DELETE FROM ai_analytics_cache WHERE expires_at < NOW();
    DELETE FROM ai_conversations WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
    DELETE FROM ai_query_stats WHERE created_at < DATE_SUB(NOW(), INTERVAL 180 DAY);
END$$

CREATE DEFINER=`acax9288`@`localhost` PROCEDURE `sp_record_ai_query` (IN `p_query_text` VARCHAR(500), IN `p_category` VARCHAR(50), IN `p_response_time` INT, IN `p_success` BOOLEAN)   BEGIN
    INSERT INTO ai_query_stats (query_text, query_category, response_time_ms, success)
    VALUES (p_query_text, p_category, p_response_time, p_success)
    ON DUPLICATE KEY UPDATE 
        query_count = query_count + 1,
        last_queried = NOW();
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_fee_settings`
--

CREATE TABLE `admin_fee_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `fee_percentage` double NOT NULL DEFAULT 0,
  `fee_fixed` decimal(15,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin_fee_settings`
--

INSERT INTO `admin_fee_settings` (`id`, `name`, `description`, `fee_percentage`, `fee_fixed`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tokopedia', 'Platform Tokopedia', 2.5, 0.00, 1, '2026-02-01 17:16:31', '2026-02-01 17:16:31'),
(2, 'Shopee', 'Platform Shopee', 8.25, 1250.00, 1, '2026-02-01 17:16:31', '2026-02-04 16:48:44'),
(3, 'Bukalapak', 'Platform Bukalapak', 2, 0.00, 1, '2026-02-01 17:16:31', '2026-02-01 17:16:31'),
(4, 'Lazada', 'Platform Lazada', 3.5, 0.00, 1, '2026-02-01 17:16:31', '2026-02-01 17:16:31'),
(5, 'TikTok Shop', 'Platform TikTok Shop', 16.5, 1250.00, 1, '2026-02-01 17:16:31', '2026-02-01 18:26:20'),
(6, 'Website', 'Website Sendiri', 1.5, 0.00, 1, '2026-02-01 17:16:31', '2026-02-01 17:16:31'),
(7, 'WhatsApp', 'Order via WhatsApp', 0, 0.00, 1, '2026-02-01 17:16:31', '2026-02-01 17:16:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_analytics_cache`
--

CREATE TABLE `ai_analytics_cache` (
  `id` int(11) NOT NULL,
  `cache_key` varchar(100) NOT NULL,
  `cache_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cache_data`)),
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_conversations`
--

CREATE TABLE `ai_conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `response` text DEFAULT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ai_conversations`
--

INSERT INTO `ai_conversations` (`id`, `user_id`, `message`, `response`, `context`, `created_at`) VALUES
(1, 1, 'Tampilkan produk dengan stok rendah', 'Analisis inventory: Terdapat 0 produk dengan stok rendah.. Segera lakukan restok.', NULL, '2026-02-01 18:44:58'),
(2, 1, 'Bagaimana penjualan bulan ini?', 'Berikut analisis penjualan Anda: Penjualan bulan ini menunjukkan tren positif dengan peningkatan 15% dibanding bulan lalu. Produk terlaris adalah kategori Elektronik. Saya sarankan untuk meningkatkan stok produk populer.', NULL, '2026-02-01 18:44:59'),
(3, 1, 'Tampilkan produk dengan stok rendah', 'Analisis inventory: Terdapat 0 produk dengan stok rendah.. Segera lakukan restok.', NULL, '2026-02-01 18:45:06'),
(4, 1, 'Berikan saya laporan keuangan', 'Laporan Keuangan bulan ini: Pemasukan Rp 0, Pengeluaran Rp 0, Profit Rp 0. Perlu evaluasi pengeluaran.', NULL, '2026-02-01 18:45:06'),
(5, 1, 'Bagaimana penjualan bulan ini?', 'Berikut analisis penjualan Anda: Penjualan bulan ini menunjukkan tren positif dengan peningkatan 15% dibanding bulan lalu. Produk terlaris adalah kategori Elektronik. Saya sarankan untuk meningkatkan stok produk populer.', NULL, '2026-02-01 18:45:14'),
(6, 1, 'Tampilkan produk dengan stok rendah', 'Analisis inventory: Terdapat 0 produk dengan stok rendah.. Segera lakukan restok.', NULL, '2026-02-01 18:54:06'),
(7, 1, 'Berikan saya laporan keuangan', 'Laporan Keuangan bulan ini: Pemasukan Rp 0, Pengeluaran Rp 0, Profit Rp 0. Perlu evaluasi pengeluaran.', NULL, '2026-02-01 18:54:35'),
(8, 1, 'berikan saya laporan keuangan', 'ðŸ’° <strong>Analisis Keuangan</strong><br><br>â€¢ Pemasukan: Rp 0<br>â€¢ Pengeluaran: Rp 0<br>â€¢ <strong>Profit: Rp 0</strong><br>â€¢ Profit Margin: 0.0%<br>â€¢ Total Transaksi: 0 transaksi<br><br>ðŸ’¡ Perlu perhatian serius! Segera evaluasi strategi bisnis dan kurangi pengeluaran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"keuangan\",\"uang\"],\"timestamp\":\"2026-02-02 02:17:03\"}', '2026-02-01 19:17:03'),
(9, 1, 'Analisis penjualan bulan ini', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\",\"analisis\"],\"timestamp\":\"2026-02-02 02:17:10\"}', '2026-02-01 19:17:10'),
(10, 1, 'laporan keuangan', 'ðŸ’° <strong>Analisis Keuangan</strong><br><br>â€¢ Pemasukan: Rp 0<br>â€¢ Pengeluaran: Rp 0<br>â€¢ <strong>Profit: Rp 0</strong><br>â€¢ Profit Margin: 0.0%<br>â€¢ Total Transaksi: 0 transaksi<br><br>ðŸ’¡ Perlu perhatian serius! Segera evaluasi strategi bisnis dan kurangi pengeluaran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"keuangan\",\"uang\"],\"timestamp\":\"2026-02-02 02:19:08\"}', '2026-02-01 19:19:08'),
(11, 1, 'laporan hr karyawan', 'ðŸ‘¥ <strong>Analisis HR & Karyawan</strong><br><br>â€¢ Karyawan Aktif: <strong>1 orang</strong><br>â€¢ Rata-rata Gaji: Rp 0<br>â€¢ Tingkat Kehadiran: 0.0%<br><br>ðŸ“Š <strong>Distribusi Departemen:</strong><br>â€¢ Produksi: 1 karyawan<br><br>ðŸ’¡ Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"hr\",\"karyawan\"],\"timestamp\":\"2026-02-02 02:19:28\"}', '2026-02-01 19:19:28'),
(12, 1, 'laporan penggajian bulan kemaren', 'ðŸ‘‹ <strong>Halo! Saya AI Assistant Bisnisku</strong><br><br>Saya dapat membantu Anda dengan:<br>ðŸ“Š Analisis Penjualan & Performance<br>ðŸ“¦ Monitoring Inventory & Stock<br>ðŸ’° Laporan Keuangan & Profit<br>ðŸ‘¥ Manajemen HR & Karyawan<br>ðŸ›’ Tracking Orders & Fulfillment<br>ðŸ’¡ Business Insights & Recommendations<br><br>Silakan tanyakan tentang aspek bisnis yang ingin Anda ketahui!', '{\"sentiment\":\"neutral\",\"keywords\":[],\"timestamp\":\"2026-02-02 02:19:53\"}', '2026-02-01 19:19:53'),
(13, 1, 'laporan penggajian karyawan', 'ðŸ‘¥ <strong>Analisis HR & Karyawan</strong><br><br>â€¢ Karyawan Aktif: <strong>1 orang</strong><br>â€¢ Rata-rata Gaji: Rp 0<br>â€¢ Tingkat Kehadiran: 0.0%<br><br>ðŸ“Š <strong>Distribusi Departemen:</strong><br>â€¢ Produksi: 1 karyawan<br><br>ðŸ’¡ Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"karyawan\"],\"timestamp\":\"2026-02-02 02:20:15\"}', '2026-02-01 19:20:15'),
(14, 1, 'Bagaimana performa karyawan?', 'ðŸ‘¥ <strong>Analisis HR & Karyawan</strong><br><br>â€¢ Karyawan Aktif: <strong>1 orang</strong><br>â€¢ Rata-rata Gaji: Rp 0<br>â€¢ Tingkat Kehadiran: 0.0%<br><br>ðŸ“Š <strong>Distribusi Departemen:</strong><br>â€¢ Produksi: 1 karyawan<br><br>ðŸ’¡ Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"karyawan\",\"performa\"],\"timestamp\":\"2026-02-02 02:20:24\"}', '2026-02-01 19:20:24'),
(15, 1, 'Bagaimana performa karyawan?', 'ðŸ‘¥ <strong>Analisis HR & Karyawan</strong><br><br>â€¢ Karyawan Aktif: <strong>1 orang</strong><br>â€¢ Rata-rata Gaji: Rp 0<br>â€¢ Tingkat Kehadiran: 0.0%<br><br>ðŸ“Š <strong>Distribusi Departemen:</strong><br>â€¢ Produksi: 1 karyawan<br><br>ðŸ’¡ Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"karyawan\",\"performa\"],\"timestamp\":\"2026-02-02 02:20:30\"}', '2026-02-01 19:20:30'),
(16, 1, 'Bagaimana performa karyawan?', 'ðŸ‘¥ <strong>Analisis HR & Karyawan</strong><br><br>â€¢ Karyawan Aktif: <strong>1 orang</strong><br>â€¢ Rata-rata Gaji: Rp 0<br>â€¢ Tingkat Kehadiran: 0.0%<br><br>ðŸ“Š <strong>Distribusi Departemen:</strong><br>â€¢ Produksi: 1 karyawan<br><br>ðŸ’¡ Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"karyawan\",\"performa\"],\"timestamp\":\"2026-02-02 02:20:30\"}', '2026-02-01 19:20:30'),
(17, 1, 'Bagaimana penjualan bulan ini?', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-02 02:20:33\"}', '2026-02-01 19:20:33'),
(18, 1, 'Bandingkan penjualan bulan ini vs bulan lalu', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-02 02:20:38\"}', '2026-02-01 19:20:38'),
(19, 1, 'Analisis penjualan per kategori', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\",\"analisis\"],\"timestamp\":\"2026-02-02 02:20:49\"}', '2026-02-01 19:20:49'),
(20, 1, 'Bagaimana penjualan bulan ini?', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-02 02:28:16\"}', '2026-02-01 19:28:16'),
(21, 1, 'Analisis penjualan per kategori', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\",\"analisis\"],\"timestamp\":\"2026-02-02 02:28:20\"}', '2026-02-01 19:28:20'),
(22, 1, 'Analisis penjualan per kategori', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\",\"analisis\"],\"timestamp\":\"2026-02-02 02:28:44\"}', '2026-02-01 19:28:44'),
(23, 1, 'Bandingkan penjualan bulan ini vs bulan lalu', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-02 02:28:48\"}', '2026-02-01 19:28:48'),
(24, 1, 'Bagaimana penjualan bulan ini?', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 94.000 dari 2 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-02 23:14:49\"}', '2026-02-02 16:14:49'),
(25, 1, 'Berikan saya laporan keuangan', 'ðŸ’° <strong>Analisis Keuangan</strong><br><br>â€¢ Pemasukan: Rp 0<br>â€¢ Pengeluaran: Rp 0<br>â€¢ <strong>Profit: Rp 0</strong><br>â€¢ Profit Margin: 0.0%<br>â€¢ Total Transaksi: 0 transaksi<br><br>ðŸ’¡ Perlu perhatian serius! Segera evaluasi strategi bisnis dan kurangi pengeluaran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"keuangan\",\"uang\"],\"timestamp\":\"2026-02-02 23:14:56\"}', '2026-02-02 16:14:56'),
(26, 1, 'Berikan saya laporan keuangan', 'ðŸ’° <strong>Analisis Keuangan</strong><br><br>â€¢ Pemasukan: Rp 2.000.000<br>â€¢ Pengeluaran: Rp 0<br>â€¢ <strong>Profit: Rp 2.000.000</strong><br>â€¢ Profit Margin: 100.0%<br>â€¢ Total Transaksi: 1 transaksi<br><br>ðŸ’¡ Profit margin sangat sehat! Bisnis Anda berkembang dengan baik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"keuangan\",\"uang\"],\"timestamp\":\"2026-02-02 23:15:45\"}', '2026-02-02 16:15:45'),
(27, 1, 'Analisis performa keseluruhan', 'ðŸ“ˆ <strong>Performance Overview</strong><br><br>â€¢ Penjualan Hari Ini: Rp 0<br>â€¢ Karyawan Aktif: 1 orang<br>â€¢ Orders Hari Ini: 0 transaksi<br>â€¢ Produk Low Stock: 0 items<br><br>ðŸ’¡ Dashboard menunjukkan performa bisnis Anda secara real-time. Gunakan menu Analytics untuk insight lebih detail.', '{\"sentiment\":\"neutral\",\"keywords\":[\"performa\",\"analisis\"],\"timestamp\":\"2026-02-04 00:24:24\"}', '2026-02-03 17:24:24'),
(28, 1, 'Bagaimana performa karyawan?', 'ðŸ‘¥ <strong>Analisis HR & Karyawan</strong><br><br>â€¢ Karyawan Aktif: <strong>1 orang</strong><br>â€¢ Rata-rata Gaji: Rp 0<br>â€¢ Tingkat Kehadiran: 0.0%<br><br>ðŸ“Š <strong>Distribusi Departemen:</strong><br>â€¢ Produksi: 1 karyawan<br><br>ðŸ’¡ Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"karyawan\",\"performa\"],\"timestamp\":\"2026-02-04 00:24:29\"}', '2026-02-03 17:24:29'),
(29, 1, 'Berikan saya laporan keuangan', 'ðŸ’° <strong>Analisis Keuangan</strong><br><br>â€¢ Pemasukan: Rp 2.000.000<br>â€¢ Pengeluaran: Rp 0<br>â€¢ <strong>Profit: Rp 2.000.000</strong><br>â€¢ Profit Margin: 100.0%<br>â€¢ Total Transaksi: 1 transaksi<br><br>ðŸ’¡ Profit margin sangat sehat! Bisnis Anda berkembang dengan baik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"keuangan\",\"uang\"],\"timestamp\":\"2026-02-04 00:24:34\"}', '2026-02-03 17:24:34'),
(30, 1, 'Bagaimana penjualan bulan ini?', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 0 dari 0 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-04 00:24:45\"}', '2026-02-03 17:24:45'),
(31, 1, 'Bagaimana penjualan bulan ini?', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 0 dari 0 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-04 00:26:44\"}', '2026-02-03 17:26:44'),
(32, 1, 'Analisis penjualan per kategori', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 0 dari 0 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\",\"analisis\"],\"timestamp\":\"2026-02-04 00:26:48\"}', '2026-02-03 17:26:48'),
(33, 1, 'Bandingkan penjualan bulan ini vs bulan lalu', 'ðŸ“Š <strong>Analisis Penjualan</strong><br><br>â€¢ Hari ini: Rp 0 dari 0 transaksi<br>â€¢ Bulan ini: Rp 94.000 dari 2 transaksi<br>â€¢ Pertumbuhan: +0.0% vs bulan lalu<br>â€¢ Produk terlaris: <strong>Cardigan Belle</strong> (2 terjual)<br><br>ðŸ’¡ Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.', '{\"sentiment\":\"neutral\",\"keywords\":[\"penjualan\",\"jual\"],\"timestamp\":\"2026-02-04 00:26:51\"}', '2026-02-03 17:26:51'),
(34, 1, 'Berikan rekomendasi untuk bisnis saya', 'ðŸ’¡ <strong>Rekomendasi AI untuk Bisnis Anda:</strong><br><br>ðŸŽ¯ 1. Tingkatkan stok kategori <strong>Fashion</strong> yang sedang trending<br>ðŸ“Š 2. Lakukan <strong>review bulanan</strong> terhadap performa semua departemen<br>ðŸ’¼ 3. Gunakan <strong>data analytics</strong> untuk decision making yang lebih akurat<br>ðŸš€ 4. Tingkatkan <strong>customer satisfaction</strong> dengan layanan yang lebih responsif<br><br>Implementasikan rekomendasi ini untuk meningkatkan performa bisnis Anda.', '{\"sentiment\":\"neutral\",\"keywords\":[\"rekomendasi\",\"bisnis\"],\"timestamp\":\"2026-02-04 00:30:41\"}', '2026-02-03 17:30:41'),
(35, 1, 'Tampilkan produk dengan stok rendah', 'ðŸ“¦ <strong>Analisis Inventory</strong><br><br>Maaf, terjadi kesalahan saat mengambil data inventory. Silakan coba lagi nanti.', '{\"sentiment\":\"neutral\",\"keywords\":[\"stok\",\"produk\",\"rendah\"],\"timestamp\":\"2026-02-04 00:30:47\"}', '2026-02-03 17:30:47'),
(0, 2, 'hr', '👥 <strong>Analisis HR & Karyawan</strong><br><br>• Karyawan Aktif: <strong>1 orang</strong><br>• Rata-rata Gaji: Rp 0<br>• Tingkat Kehadiran: 0.0%<br><br>📊 <strong>Distribusi Departemen:</strong><br>• Produksi: 1 karyawan<br><br>💡 Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.', '{\"sentiment\":\"neutral\",\"keywords\":[\"hr\"],\"timestamp\":\"2026-02-04 13:20:01\"}', '2026-02-04 06:20:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_insights_history`
--

CREATE TABLE `ai_insights_history` (
  `id` int(11) NOT NULL,
  `insight_type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `metric_value` varchar(100) DEFAULT NULL,
  `status` enum('positive','negative','neutral','warning','critical') DEFAULT 'neutral',
  `action_required` text DEFAULT NULL,
  `data_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_snapshot`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ai_insights_history`
--

INSERT INTO `ai_insights_history` (`id`, `insight_type`, `title`, `description`, `metric_value`, `status`, `action_required`, `data_snapshot`, `created_at`) VALUES
(1, 'sales', 'Penjualan Meningkat', 'Penjualan bulan ini menunjukkan peningkatan signifikan', '+15%', 'positive', 'Pertahankan strategi marketing saat ini', '{\"growth\": 15, \"period\": \"month\"}', '2026-02-01 19:06:56'),
(2, 'inventory', 'Stok Optimal', 'Inventory dalam kondisi baik', 'Optimal', 'positive', 'Lanjutkan monitoring rutin', '{\"low_stock_count\": 0}', '2026-02-01 19:06:56'),
(3, 'finance', 'Profit Margin Sehat', 'Profit margin mencapai target', '32%', 'positive', 'Pertimbangkan ekspansi', '{\"profit_margin\": 32}', '2026-02-01 19:06:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_query_stats`
--

CREATE TABLE `ai_query_stats` (
  `id` int(11) NOT NULL,
  `query_text` varchar(500) NOT NULL,
  `query_category` varchar(50) DEFAULT NULL,
  `response_time_ms` int(11) DEFAULT NULL,
  `success` tinyint(1) DEFAULT 1,
  `user_rating` tinyint(4) DEFAULT NULL,
  `feedback_text` text DEFAULT NULL,
  `query_count` int(11) DEFAULT 1,
  `last_queried` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_recommendations_log`
--

CREATE TABLE `ai_recommendations_log` (
  `id` int(11) NOT NULL,
  `recommendation_text` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `priority` enum('low','medium','high','critical') DEFAULT 'medium',
  `status` enum('pending','implemented','dismissed','expired') DEFAULT 'pending',
  `impact_score` decimal(5,2) DEFAULT 0.00,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `implemented_at` datetime DEFAULT NULL,
  `dismissed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ai_recommendations_log`
--

INSERT INTO `ai_recommendations_log` (`id`, `recommendation_text`, `category`, `priority`, `status`, `impact_score`, `metadata`, `created_at`, `implemented_at`, `dismissed_at`) VALUES
(1, 'Tingkatkan stok produk kategori Elektronik yang sedang trending', 'inventory', 'high', 'pending', 8.50, '{\"category\": \"Elektronik\", \"reason\": \"high_demand\"}', '2026-02-01 19:06:56', NULL, NULL),
(2, 'Pertimbangkan memberikan diskon untuk produk slow-moving', 'sales', 'medium', 'pending', 6.00, '{\"target\": \"slow_moving_products\"}', '2026-02-01 19:06:56', NULL, NULL),
(3, 'Rekrut 2 karyawan baru untuk department sales', 'hr', 'medium', 'pending', 7.00, '{\"department\": \"sales\", \"count\": 2}', '2026-02-01 19:06:56', NULL, NULL),
(4, 'Optimalkan pengeluaran operasional dengan efisiensi 10%', 'finance', 'high', 'pending', 8.00, '{\"target_efficiency\": 10}', '2026-02-01 19:06:56', NULL, NULL),
(5, 'Implementasikan program loyalty untuk meningkatkan customer retention', 'sales', 'high', 'pending', 9.00, '{\"type\": \"customer_retention\"}', '2026-02-01 19:06:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_user_preferences`
--

CREATE TABLE `ai_user_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`preferences`)),
  `notification_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_settings`)),
  `last_interaction` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','absent','late','leave','sick') DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `check_in`, `check_out`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-31', '00:20:00', '00:00:00', 'present', '', '2026-01-30 17:20:58', '2026-01-30 17:20:58'),
(2, 0, '2026-02-04', '00:00:00', '06:06:00', 'present', '', '2026-02-04 17:00:16', '2026-02-04 17:00:16'),
(3, 0, '2026-02-05', '00:07:00', '00:00:00', 'present', '', '2026-02-04 17:07:22', '2026-02-04 17:07:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', 'Produk elektronik dan gadget', 'fa-laptop', '2026-01-11 15:53:14', '2026-01-11 15:53:14'),
(2, 'Fashion', 'Pakaian dan aksesoris', 'fa-tshirt', '2026-01-11 15:53:14', '2026-01-11 15:53:14'),
(3, 'Makanan & Minuman', 'Produk konsumsi', 'fa-utensils', '2026-01-11 15:53:14', '2026-01-11 15:53:14'),
(4, 'Furniture', 'Perabot rumah tangga', 'fa-couch', '2026-01-11 15:53:14', '2026-01-11 15:53:14'),
(5, 'Kesehatan', 'Produk kesehatan', 'fa-heartbeat', '2026-01-11 15:53:14', '2026-01-11 15:53:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_id` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(15,2) DEFAULT 0.00,
  `status` enum('active','inactive','resigned') DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_id`, `full_name`, `email`, `phone`, `address`, `position`, `department`, `hire_date`, `salary`, `status`, `photo`, `created_at`, `updated_at`) VALUES
(1, NULL, 'EMP0001', 'agung Candra saputra', 'candraagung627@gmail.com', '+62 812 3456 7890', 'binongj ati', 'Lingking', 'Produksi', '2026-01-31', 0.00, 'active', '697ce86297aa6_1769793634.png', '2026-01-30 17:20:34', '2026-01-30 17:20:34'),
(2, NULL, 'EMP0002', 'abdul rojakzz', 'abdul@email.com', '+62 812 3456 7890', 'binong jati', 'Lingking', 'Produksi', '2026-02-04', 0.00, 'active', '698374f143cbb_1770222833.png', '2026-02-04 16:33:53', '2026-02-04 18:08:20'),
(3, NULL, 'EMP0003', 'abdul rojak', 'abdul@email.com', '+62 812 3456 7890', 'binong jati', 'Lingking', 'Produksi', '2026-02-04', 0.00, 'active', '698383d6838f4_1770226646.png', '2026-02-04 17:37:26', '2026-02-04 18:04:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `login_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_at` timestamp NULL DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `ip_address`, `user_agent`, `login_at`, `logout_at`, `status`) VALUES
(1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 17:46:08', '2026-02-03 17:47:08', 'success'),
(2, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 17:47:17', '2026-02-03 17:50:14', 'success'),
(3, 1, '2404:c0:a301:3a1d:c55b:26e2:70ec:ab9f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 05:56:35', NULL, 'success'),
(4, 2, '2404:c0:a301:3a1d:fa4a:7c9c:2a4e:79b2', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-02-04 06:19:09', NULL, 'success'),
(5, 1, '2404:c0:a301:3a1d:65b9:9128:5a7f:5158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 07:28:17', NULL, 'success'),
(6, 1, '2404:c0:a301:3a1d:d59:bb4d:4f9c:2f6b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 15:45:32', NULL, 'success');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `period_start` date NOT NULL COMMENT 'Awal periode',
  `period_end` date NOT NULL COMMENT 'Akhir periode',
  `total_pcs` int(11) DEFAULT 0 COMMENT 'Total pcs semua produk',
  `total_salary` decimal(15,2) DEFAULT 0.00 COMMENT 'Total gaji periode ini',
  `payment_date` date DEFAULT NULL,
  `payment_status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payroll`
--

INSERT INTO `payroll` (`id`, `employee_id`, `period_start`, `period_end`, `total_pcs`, `total_salary`, `payment_date`, `payment_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-25', '2026-01-31', 28, 127166.67, '2026-01-31', 'paid', '', '2026-01-30 18:25:40', '2026-01-30 18:31:42'),
(2, 3, '2026-02-01', '2026-02-05', 14, 78166.67, '2026-02-05', 'paid', '', '2026-02-04 18:14:34', '2026-02-04 18:25:40'),
(3, 1, '2026-01-05', '2026-02-05', 59, 261083.34, NULL, 'pending', '', '2026-02-04 18:14:52', '2026-02-04 18:14:52'),
(4, 1, '2026-01-31', '2026-02-05', 59, 261083.34, NULL, 'pending', '', '2026-02-04 18:15:28', '2026-02-04 18:15:28'),
(6, 1, '2026-01-30', '2026-02-05', 59, 261083.34, NULL, 'pending', '', '2026-02-04 18:21:23', '2026-02-04 18:21:23'),
(7, 1, '2026-01-05', '2026-02-05', 59, 261083.34, NULL, 'pending', '', '2026-02-04 18:22:04', '2026-02-04 18:22:04'),
(8, 2, '2026-02-05', '2026-02-05', 14, 28000.00, '2026-02-05', 'paid', '', '2026-02-04 18:24:15', '2026-02-04 18:25:36'),
(9, 1, '2026-02-05', '2026-02-05', 14, 35000.00, '2026-02-05', 'paid', '', '2026-02-04 18:25:09', '2026-02-04 18:25:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_details`
--

CREATE TABLE `payroll_details` (
  `id` int(11) NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL COMMENT 'Snapshot nama produk',
  `work_type` enum('rajut','linking') NOT NULL,
  `pcs` int(11) NOT NULL COMMENT 'Total pcs untuk item ini',
  `rate_per_pcs` decimal(10,4) NOT NULL COMMENT 'Snapshot tarif per pcs',
  `subtotal` decimal(15,2) NOT NULL COMMENT 'Subtotal = pcs Ã— rate_per_pcs',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payroll_details`
--

INSERT INTO `payroll_details` (`id`, `payroll_id`, `product_type_id`, `product_name`, `work_type`, `pcs`, `rate_per_pcs`, `subtotal`, `created_at`) VALUES
(1, 1, 1, 'Belle', 'rajut', 14, 5583.3333, 78166.67, '2026-01-30 18:25:40'),
(2, 1, 3, 'K4', 'rajut', 14, 3500.0000, 49000.00, '2026-01-30 18:25:40'),
(3, 1, 1, 'Belle', 'rajut', 14, 5583.3333, 78166.67, '2026-02-04 18:14:34'),
(4, 1, 6, 'breshka', 'rajut', 17, 4583.3333, 77916.67, '2026-02-04 18:14:52'),
(5, 1, 5, 'Andin', 'rajut', 14, 4000.0000, 56000.00, '2026-02-04 18:14:52'),
(6, 1, 1, 'Belle', 'rajut', 14, 5583.3333, 78166.67, '2026-02-04 18:14:52'),
(7, 1, 3, 'K4', 'rajut', 14, 3500.0000, 49000.00, '2026-02-04 18:14:52'),
(8, 1, 6, 'breshka', 'rajut', 17, 4583.3333, 77916.67, '2026-02-04 18:15:28'),
(9, 1, 5, 'Andin', 'rajut', 14, 4000.0000, 56000.00, '2026-02-04 18:15:28'),
(10, 1, 1, 'Belle', 'rajut', 14, 5583.3333, 78166.67, '2026-02-04 18:15:28'),
(11, 1, 3, 'K4', 'rajut', 14, 3500.0000, 49000.00, '2026-02-04 18:15:28'),
(14, 6, 6, 'breshka', 'rajut', 17, 4583.3333, 77916.67, '2026-02-04 18:21:23'),
(15, 6, 5, 'Andin', 'rajut', 14, 4000.0000, 56000.00, '2026-02-04 18:21:23'),
(16, 6, 1, 'Belle', 'rajut', 14, 5583.3333, 78166.67, '2026-02-04 18:21:23'),
(17, 6, 3, 'K4', 'rajut', 14, 3500.0000, 49000.00, '2026-02-04 18:21:23'),
(18, 7, 6, 'breshka', 'rajut', 17, 4583.3333, 77916.67, '2026-02-04 18:22:04'),
(19, 7, 5, 'Andin', 'rajut', 14, 4000.0000, 56000.00, '2026-02-04 18:22:04'),
(20, 7, 1, 'Belle', 'rajut', 14, 5583.3333, 78166.67, '2026-02-04 18:22:04'),
(21, 7, 3, 'K4', 'rajut', 14, 3500.0000, 49000.00, '2026-02-04 18:22:04'),
(22, 8, 5, 'Andin', 'linking', 14, 2000.0000, 28000.00, '2026-02-04 18:24:15'),
(23, 9, 1, 'Belle', 'linking', 14, 2500.0000, 35000.00, '2026-02-04 18:25:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cost_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 10,
  `unit` varchar(20) DEFAULT 'pcs',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `description`, `image`, `price`, `cost_price`, `stock`, `min_stock`, `unit`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'PROD-001', 'Cardigan Belle', 'Cardigan Belle Rajut', '69653a25f14c4_1768241701.jpeg', 47000.00, 35000.00, 96, 10, 'pcs', 'active', '2026-01-12 18:15:01', '2026-02-04 17:33:05'),
(2, 2, 'PROD-002', 'merissa', 'merissa', '6982317b2c3aa_1770140027.jpg', 44000.00, 32000.00, 94, 10, 'pcs', 'active', '2026-02-03 17:33:47', '2026-02-04 18:22:51'),
(3, 2, 'PROD-003', 'tes', 'tes', '6982e037bba2d_1770184759.png', 50000.00, 40000.00, 100, 10, 'pcs', 'active', '2026-02-04 05:59:19', '2026-02-04 05:59:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_types`
--

CREATE TABLE `product_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Nama produk: Belle, K5, K4, Kulot',
  `rajut_rate` decimal(10,4) NOT NULL COMMENT 'Tarif rajut per pcs (misal: 67/12 = 5.5833)',
  `linking_rate` decimal(10,4) NOT NULL COMMENT 'Tarif linking per pcs (misal: 30/12 = 2.5)',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `product_types`
--

INSERT INTO `product_types` (`id`, `name`, `rajut_rate`, `linking_rate`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Belle', 5583.3333, 2500.0000, 'Produk Belle - Rajut: Rp 5.583/pcs, Linking: Rp 2.5/pcs', 1, '2026-01-12 18:43:38', '2026-01-30 18:12:57'),
(2, 'K5', 4750.0000, 2333.3333, 'Produk K5 - Rajut: Rp 4.75/pcs, Linking: Rp 2.333/pcs', 1, '2026-01-12 18:43:38', '2026-01-30 18:14:15'),
(3, 'K4', 3500.0000, 1833.3333, 'Produk K4 - Rajut: Rp 3.5/pcs, Linking: Rp 1.833/pcs', 1, '2026-01-12 18:43:38', '2026-01-30 18:13:51'),
(4, 'Kulot', 4166.6667, 1833.3333, 'Produk Kulot - Rajut: Rp 4.167/pcs, Linking: Rp 1.833/pcs', 1, '2026-01-12 18:43:38', '2026-01-30 18:18:13'),
(5, 'Andin', 4000.0000, 2000.0000, 'andin', 1, '2026-01-30 18:17:46', '2026-01-30 18:17:46'),
(6, 'breshka', 4583.3333, 1833.3333, 'breshka', 1, '2026-02-03 17:31:28', '2026-02-03 17:31:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `sale_number` varchar(50) NOT NULL,
  `sale_type` enum('offline','online') NOT NULL DEFAULT 'offline',
  `admin_fee_setting_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `subtotal` double NOT NULL DEFAULT 0,
  `tax` double NOT NULL DEFAULT 0,
  `discount` double NOT NULL DEFAULT 0,
  `admin_fee_percentage` double NOT NULL DEFAULT 0,
  `admin_fee_amount` double NOT NULL DEFAULT 0,
  `total` double NOT NULL DEFAULT 0,
  `final_total` double NOT NULL DEFAULT 0,
  `profit` double NOT NULL DEFAULT 0 COMMENT 'Keuntungan bersih',
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sale_status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'completed',
  `payment_status` enum('pending','paid','refunded') NOT NULL DEFAULT 'paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sales`
--

INSERT INTO `sales` (`id`, `sale_number`, `sale_type`, `admin_fee_setting_id`, `customer_id`, `customer_name`, `customer_phone`, `customer_address`, `subtotal`, `tax`, `discount`, `admin_fee_percentage`, `admin_fee_amount`, `total`, `final_total`, `profit`, `notes`, `created_by`, `created_at`, `updated_at`, `sale_status`, `payment_status`) VALUES
(2, 'OFFLINE-20260202-C783', 'offline', NULL, NULL, 'abdul', '0828281', 'binong', 47000, 0, 0, 0, 0, 47000, 47000, 12000, '', 1, '2026-02-01 18:11:30', '2026-02-04 18:33:14', 'completed', 'paid'),
(3, 'ONLINE-20260202-1ABF', 'online', 5, NULL, 'abdulxx', '0828281', 'binong', 47000, 0, 0, 16.5, 7755, 47000, 39245, 4245, '', 1, '2026-02-01 18:29:17', '2026-02-04 18:33:14', 'completed', 'paid'),
(4, 'ONLINE-20260204-F02D', 'online', 2, NULL, 'abdul rojak', '0828281', 'binong', 88000, 0, 0, 8.25, 7260, 88000, 80740, 0, '', 1, '2026-02-04 16:49:21', '2026-02-04 16:49:21', 'completed', 'paid'),
(5, 'ONLINE-20260204-F022', 'online', 2, NULL, 'abdul rojak', '0828281', 'binong', 47000, 0, 0, 8.25, 3877.5, 47000, 43122.5, 0, '', 1, '2026-02-04 16:50:00', '2026-02-04 16:50:00', 'completed', 'paid'),
(6, 'ONLINE-20260205-42B7', 'online', 2, NULL, 'abdul rojak z', '082828122', 'binong jati', 44000, 0, 0, 8.25, 3630, 44000, 40370, 0, '', 1, '2026-02-04 17:04:45', '2026-02-04 17:04:45', 'completed', 'paid'),
(7, 'ONLINE-20260205-831A', 'online', 2, NULL, 'rojax', '112233', 'bandung', 91000, 0, 0, 8.25, 7507.5, 91000, 83492.5, 0, '', 1, '2026-02-04 17:33:05', '2026-02-04 17:33:05', 'completed', 'paid'),
(9, 'OFFLINE-20260205-EB5B', 'offline', NULL, NULL, 'rojax', '112233', 'bandung', 44000, 0, 0, 0, 0, 44000, 44000, 12000, '', 1, '2026-02-04 18:13:06', '2026-02-04 18:33:14', 'completed', 'paid'),
(10, 'ONLINE-20260205-4757', 'online', 2, NULL, 'bebe', '0828281', 'bandung', 44000, 0, 0, 8.25, 3630, 44000, 40370, 8370, '', 1, '2026-02-04 18:22:51', '2026-02-04 18:33:14', 'completed', 'paid');

--
-- Trigger `sales`
--
DELIMITER $$
CREATE TRIGGER `before_sale_insert` BEFORE INSERT ON `sales` FOR EACH ROW BEGIN
    DECLARE v_fee_percentage DOUBLE DEFAULT 0;
    
    
    IF NEW.sale_type = 'online' AND NEW.admin_fee_setting_id IS NOT NULL THEN
        SELECT fee_percentage INTO v_fee_percentage 
        FROM admin_fee_settings 
        WHERE id = NEW.admin_fee_setting_id AND is_active = 1;
        
        SET NEW.admin_fee_percentage = v_fee_percentage;
    ELSE
        SET NEW.admin_fee_percentage = 0;
    END IF;
    
    
    SET NEW.total = NEW.subtotal + NEW.tax - NEW.discount;
    SET NEW.admin_fee_amount = NEW.total * (NEW.admin_fee_percentage / 100);
    SET NEW.final_total = NEW.total - NEW.admin_fee_amount;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_sale_update` BEFORE UPDATE ON `sales` FOR EACH ROW BEGIN
    DECLARE v_fee_percentage DOUBLE DEFAULT 0;
    
    
    IF NEW.sale_type = 'online' AND NEW.admin_fee_setting_id IS NOT NULL THEN
        SELECT fee_percentage INTO v_fee_percentage 
        FROM admin_fee_settings 
        WHERE id = NEW.admin_fee_setting_id AND is_active = 1;
        
        SET NEW.admin_fee_percentage = v_fee_percentage;
    ELSE
        SET NEW.admin_fee_percentage = 0;
    END IF;
    
    
    SET NEW.total = NEW.subtotal + NEW.tax - NEW.discount;
    SET NEW.admin_fee_amount = NEW.total * (NEW.admin_fee_percentage / 100);
    SET NEW.final_total = NEW.total - NEW.admin_fee_amount;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_backup`
--

CREATE TABLE `sales_backup` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `subtotal` double NOT NULL,
  `tax` double NOT NULL,
  `discount` double NOT NULL,
  `total` double NOT NULL,
  `payment_method` enum('cash','transfer','credit_card','e-wallet','cod') DEFAULT NULL,
  `payment_status` enum('pending','paid','partial','refunded') DEFAULT NULL,
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `cost_price` double NOT NULL DEFAULT 0 COMMENT 'Modal harga produk untuk kalkulasi keuntungan',
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `price`, `cost_price`, `subtotal`) VALUES
(1, 2, 1, 'Cardigan Belle', 'PROD-001', 1, 47000, 35000, 47000),
(2, 3, 1, 'Cardigan Belle', 'PROD-001', 1, 47000, 35000, 47000),
(3, 0, 2, 'merissa', 'PROD-002', 2, 44000, 32000, 88000),
(4, 0, 1, 'Cardigan Belle', 'PROD-001', 1, 47000, 35000, 47000),
(5, 0, 2, 'merissa', 'PROD-002', 1, 44000, 32000, 44000),
(6, 0, 2, 'merissa', 'PROD-002', 1, 44000, 32000, 44000),
(7, 0, 1, 'Cardigan Belle', 'PROD-001', 1, 47000, 35000, 47000),
(8, 9, 2, 'merissa', 'PROD-002', 1, 44000, 32000, 44000),
(10, 10, 2, 'merissa', 'PROD-002', 1, 44000, 32000, 44000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`, `created_at`) VALUES
('rifk3vbdd2rq4k3udb1o77m0da', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '', 1770141146, '2026-02-03 17:50:15'),
('ea67fd5b5248fb686946ea66cc1c3576', NULL, '104.143.84.54', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/139.0.7258.5 Safari/537.36', '', 1770182799, '2026-02-04 05:26:39'),
('43f9450efd0203580218d8a9960a7935', 1, '2404:c0:a301:3a1d:c55b:26e2:70ec:ab9f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'user_id|i:1;user|a:5:{s:2:\"id\";i:1;s:9:\"full_name\";s:13:\"Administrator\";s:5:\"email\";s:18:\"admin@bisnisku.com\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";s:28:\"6980cf9271600_1770049426.png\";}login_time|i:1770184595;last_activity|i:1770184595;flash|a:0:{}', 1770185307, '2026-02-04 05:56:35'),
('3c57e0d8edb505d18a43510ab5653dc5', 2, '182.10.98.188', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'user_id|i:2;user|a:5:{s:2:\"id\";i:2;s:9:\"full_name\";N;s:5:\"email\";s:24:\"agungcandra655@gmail.com\";s:4:\"role\";s:8:\"employee\";s:6:\"avatar\";N;}login_time|i:1770185949;last_activity|i:1770185949;flash|a:0:{}', 1770186043, '2026-02-04 06:19:09'),
('2fe897f9bc37c5508da854b75ef84ba6', 1, '2404:c0:a301:3a1d:65b9:9128:5a7f:5158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'user_id|i:1;user|a:5:{s:2:\"id\";i:1;s:9:\"full_name\";s:13:\"Administrator\";s:5:\"email\";s:18:\"admin@bisnisku.com\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";s:28:\"6982e22743090_1770185255.png\";}login_time|i:1770190097;last_activity|i:1770190097;auto_login|b:1;', 1770190103, '2026-02-04 07:28:17'),
('5a52b959d84dfd4a4502ff65db75bdac', NULL, '16.145.48.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Mobile/15E148 Safari/604.1', '', 1770206998, '2026-02-04 12:09:58'),
('6b2143c27c67ea7978c1b0c2ab65668a', NULL, '16.145.48.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Mobile/15E148 Safari/604.1', '', 1770206998, '2026-02-04 12:09:58'),
('47c3f141bad5f695d5efe6ad5de92e97', NULL, '44.245.148.16', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Mobile/15E148 Safari/604.1', '', 1770207632, '2026-02-04 12:20:32'),
('c69cf36630a8b58c4ff9154affe7dcd9', 1, '2404:c0:a301:3a1d:d59:bb4d:4f9c:2f6b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'user_id|i:1;user|a:5:{s:2:\"id\";i:1;s:9:\"full_name\";s:13:\"Administrator\";s:5:\"email\";s:18:\"admin@bisnisku.com\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";s:28:\"69837aee3f026_1770224366.png\";}login_time|i:1770219932;last_activity|i:1770219932;auto_login|b:1;flash|a:0:{}', 1770230202, '2026-02-04 15:45:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`, `description`, `updated_at`) VALUES
(1, 'company_name', 'Bisnisku', 'Nama perusahaan', '2026-02-02 16:33:49'),
(2, 'company_email', 'info@bisnisku.com', 'Email perusahaan', '2026-02-02 16:33:49'),
(3, 'company_phone', '081234567890', 'Telepon perusahaan', '2026-02-02 16:33:49'),
(4, 'company_address', 'Jl. Bisnis No. 123, Jakarta', 'Alamat lengkap perusahaan', '2026-02-02 16:33:49'),
(5, 'currency', 'IDR', 'Mata uang yang digunakan', '2026-02-02 16:33:49'),
(6, 'tax_rate', '11', 'Persentase pajak PPN', '2026-02-02 16:33:49'),
(7, 'low_stock_threshold', '10', 'Batas minimum stok untuk alert', '2026-02-02 16:33:49'),
(8, 'invoice_prefix', 'INV', 'Prefix untuk nomor invoice', '2026-02-02 16:33:49'),
(9, 'date_format', 'd/m/Y', 'Format tanggal yang digunakan', '2026-02-02 16:33:49'),
(10, 'email_notification', '1', 'Aktifkan notifikasi email', '2026-02-02 16:33:49'),
(11, 'low_stock_alert', '1', 'Aktifkan alert stok rendah', '2026-02-02 16:33:49'),
(12, 'auto_print_invoice', '1', 'Cetak invoice otomatis', '2026-02-02 16:33:49'),
(13, 'maintenance_mode', '0', 'Mode maintenance sistem', '2026-02-02 16:33:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `payment_method` enum('cash','transfer','credit_card','e-wallet') DEFAULT 'cash',
  `attachment` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `category`, `amount`, `description`, `reference_type`, `reference_id`, `transaction_date`, `payment_method`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'income', 'modal awal', 2000000.00, 'modal awal', NULL, NULL, '2026-01-13', 'transfer', NULL, 1, '2026-01-12 18:29:44', '2026-01-12 18:29:44'),
(2, 'expense', 'oprasional', 200000.00, '', NULL, NULL, '2026-01-31', 'transfer', NULL, 1, '2026-01-30 17:06:21', '2026-01-30 17:06:21'),
(3, 'income', 'modal awal', 2000000.00, 'modal awal', NULL, NULL, '2026-02-02', 'transfer', NULL, 1, '2026-02-02 16:15:24', '2026-02-02 16:15:24'),
(4, 'expense', 'oprasional', 200000.00, '', NULL, NULL, '2026-02-04', 'transfer', NULL, 1, '2026-02-03 17:32:18', '2026-02-03 17:32:18'),
(5, 'income', 'modal awal', 1000000.00, '', NULL, NULL, '2026-02-04', 'transfer', NULL, 1, '2026-02-04 06:06:27', '2026-02-04 06:06:27'),
(6, 'expense', 'beli plastik', 200000.00, 'beli plastik', NULL, NULL, '2026-02-05', 'transfer', NULL, 1, '2026-02-04 17:03:32', '2026-02-04 17:03:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','manager','employee') DEFAULT 'employee',
  `status` enum('active','inactive') DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remember_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `avatar`, `role`, `status`, `remember_token`, `created_at`, `updated_at`, `remember_token_expires`) VALUES
(1, 'Administrator', 'admin@bisnisku.com', '$2y$12$zQLuwkonb/ZurHjSHsDHCOArDK0UM.1CGlPqOhGoP6M89BXhZXl1.', '081234567880', '69837aee3f026_1770224366.png', 'admin', 'active', '77235e245ebaabcb3d74ff6ead7783984fb5effcf8b7d7df82068c6590c00305', '2026-01-11 15:53:14', '2026-02-04 16:59:26', '2026-03-06 12:56:35'),
(2, '', 'agungcandra655@gmail.com', '$2y$12$3.lRcxWfXav.M7ndQ0.Sbe6WtUKi14QfeLYERf9wxp2YuXnI5S9xq', NULL, NULL, 'employee', 'active', NULL, '2026-02-03 17:45:45', '2026-02-04 06:20:30', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_records`
--

CREATE TABLE `work_records` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `work_type` enum('rajut','linking') NOT NULL COMMENT 'Jenis pekerjaan: rajut atau linking',
  `date` date NOT NULL,
  `dozens` decimal(10,2) DEFAULT 0.00 COMMENT 'Jumlah dalam lusin (optional)',
  `pcs` int(11) NOT NULL COMMENT 'Jumlah dalam pcs (otomatis: dozens ?? 12)',
  `rate_per_pcs` decimal(10,4) NOT NULL COMMENT 'Snapshot tarif saat input',
  `subtotal` decimal(15,2) NOT NULL COMMENT 'Subtotal = pcs ?? rate_per_pcs',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `work_records`
--

INSERT INTO `work_records` (`id`, `employee_id`, `product_type_id`, `work_type`, `date`, `dozens`, `pcs`, `rate_per_pcs`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 'rajut', '2026-01-31', 1.00, 14, 5583.3333, 78166.67, '', '2026-01-30 18:15:04', '2026-01-30 18:15:04'),
(4, 1, 3, 'rajut', '2026-01-31', 1.00, 14, 3500.0000, 49000.00, '', '2026-01-30 18:16:02', '2026-01-30 18:16:02'),
(5, 1, 6, 'rajut', '2026-02-04', 1.00, 17, 4583.3333, 77916.67, '', '2026-02-03 17:31:51', '2026-02-03 17:31:51'),
(6, 1, 5, 'rajut', '2026-02-04', 1.00, 14, 4000.0000, 56000.00, '', '2026-02-04 06:08:27', '2026-02-04 06:08:27'),
(7, 0, 5, 'rajut', '2026-02-05', 1.00, 14, 4000.0000, 56000.00, '', '2026-02-04 17:01:23', '2026-02-04 17:01:23'),
(8, 0, 5, 'rajut', '2026-02-05', 1.00, 13, 4000.0000, 52000.00, '', '2026-02-04 17:37:50', '2026-02-04 17:37:50'),
(9, 0, 1, 'rajut', '2026-02-05', 1.00, 14, 5583.3333, 78166.67, '', '2026-02-04 17:38:28', '2026-02-04 17:38:28'),
(10, 3, 1, 'rajut', '2026-02-05', 1.00, 14, 5583.3333, 78166.67, '', '2026-02-04 18:09:18', '2026-02-04 18:09:18'),
(13, 2, 5, 'linking', '2026-02-05', 1.00, 14, 2000.0000, 28000.00, '', '2026-02-04 18:23:58', '2026-02-04 18:23:58'),
(14, 1, 1, 'linking', '2026-02-05', 1.00, 14, 2500.0000, 35000.00, '', '2026-02-04 18:24:54', '2026-02-04 18:24:54');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `work_records`
--
ALTER TABLE `work_records`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `payroll_details`
--
ALTER TABLE `payroll_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `work_records`
--
ALTER TABLE `work_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
