<?php
/**
 * Settings Controller
 */

class SettingsController {
    private $db;
    
    public function __construct() {
        require_auth();
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $settings = $this->db->fetchAll("SELECT * FROM settings");
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['key_name']] = $setting['value'];
        }
        
        ob_start();
        include APP_PATH . '/views/settings/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Pengaturan';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('settings'));
        }
        
        $settings = $_POST;
        
        foreach ($settings as $key => $value) {
            $this->db->query(
                "UPDATE settings SET value = ? WHERE key_name = ?",
                [$value, $key]
            );
        }
        
        flash('success', 'Pengaturan berhasil disimpan');
        redirect(base_url('settings'));
    }
}
