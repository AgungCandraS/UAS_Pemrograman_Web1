<?php
require_once 'config/env.php';
require_once 'core/Database.php';

$db = Database::getInstance();

// Generate new password hash
$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "New password hash: $hash\n\n";

// Update to database
$db->update('users', ['password' => $hash], 'email = ?', ['admin@bisnisku.com']);

echo "Password updated successfully!\n\n";

// Verify
$user = $db->fetchOne("SELECT * FROM users WHERE email = 'admin@bisnisku.com'");
if (password_verify($password, $user['password'])) {
    echo "✓ Password verification SUCCESS!\n";
} else {
    echo "✗ Password verification FAILED!\n";
}
