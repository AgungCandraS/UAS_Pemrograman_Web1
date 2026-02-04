<?php
/**
 * Profile Controller
 */

class ProfileController {
    private $userModel;
    
    public function __construct() {
        require_auth();
        $this->userModel = new User();
    }
    
    public function index() {
        $user = $this->userModel->findById(auth_user()['id']);
        
        ob_start();
        include APP_PATH . '/views/profile/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Profile Saya';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('profile'));
        }
        
        $data = [
            'full_name' => post('full_name'),
            'phone' => post('phone')
        ];
        
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $uploadedFile = upload_file($_FILES['avatar']);
            if ($uploadedFile) {
                $data['avatar'] = $uploadedFile;
            }
        }
        
        if ($this->userModel->update(auth_user()['id'], $data)) {
            // Update session data
            $_SESSION['user']['full_name'] = $data['full_name'];
            if (isset($data['avatar'])) {
                $_SESSION['user']['avatar'] = $data['avatar'];
            }
            flash('success', 'Profile berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate profile');
        }
        
        redirect(base_url('profile'));
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('profile'));
        }
        
        $oldPassword = post('old_password');
        $newPassword = post('new_password');
        $confirmPassword = post('confirm_password');
        
        if ($newPassword !== $confirmPassword) {
            flash('error', 'Password baru tidak cocok');
            redirect(base_url('profile'));
        }
        
        $user = $this->userModel->findById(auth_user()['id']);
        
        if (!$this->userModel->verifyPassword($oldPassword, $user['password'])) {
            flash('error', 'Password lama salah');
            redirect(base_url('profile'));
        }
        
        $hashedPassword = password_hash($newPassword, HASH_ALGO, ['cost' => HASH_COST]);
        
        if ($this->userModel->update(auth_user()['id'], ['password' => $hashedPassword])) {
            flash('success', 'Password berhasil diubah');
        } else {
            flash('error', 'Gagal mengubah password');
        }
        
        redirect(base_url('profile'));
    }
}
