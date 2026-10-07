<?php
// config/db.php
// AI Creator Marketplace - Database Configuration & Helpers
// Configured for XAMPP (Apache + MySQL / MariaDB)

// Configure persistent sessions (1 year lifetime so user is never asked for login credentials repeatedly)
ini_set('session.gc_maxlifetime', 31536000);
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 31536000,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

$db_host = getenv('DB_HOST') ?: '127.0.0.1';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db_name = getenv('DB_NAME') ?: 'ai_creator_marketplace';
$db_port = getenv('DB_PORT') ?: '3306';

define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);
define('DB_PORT', (int)$db_port);

/**
 * Returns a PDO database connection.
 * Automatically creates the database and seeds tables if they don't exist.
 */
function get_db(): PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        // Try connecting directly to the target database
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => true,
        ]);
        
        // Auto-migrate creator_profiles if new brand filter columns are missing
        try {
            $check_stmt = $pdo->query("SHOW COLUMNS FROM `creator_profiles` LIKE 'content_types'");
            if ($check_stmt && $check_stmt->rowCount() === 0) {
                $pdo->exec("ALTER TABLE `creator_profiles` 
                    ADD COLUMN `content_types` VARCHAR(255) DEFAULT 'Video, Advertisement',
                    ADD COLUMN `aspect_ratios` VARCHAR(100) DEFAULT '16:9, 9:16',
                    ADD COLUMN `budget_tier` VARCHAR(100) DEFAULT '₹25,000–₹50,000',
                    ADD COLUMN `price_num` INT NOT NULL DEFAULT 25000,
                    ADD COLUMN `experience_level` VARCHAR(50) DEFAULT 'Advanced',
                    ADD COLUMN `availability` VARCHAR(50) DEFAULT 'Available Now',
                    ADD COLUMN `commercial_rights` VARCHAR(255) DEFAULT 'Commercial Use, Paid Advertising',
                    ADD COLUMN `verification_tags` VARCHAR(255) DEFAULT 'Tools Verified, Portfolio Verified, Commercial Ready'");
            }
        } catch (Exception $mig_err) {
            // Non-fatal if table doesn't exist yet
        }
        
        return $pdo;
    } catch (PDOException $e) {
        // Database might not exist yet -> connect to server and initialize
        try {
            $server_dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
            $server_pdo = new PDO($server_dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $server_pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            // Now connect to the newly created database
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
            ]);

            // Execute SQL schema file if present
            $sql_file = __DIR__ . '/../database.sql';
            if (file_exists($sql_file)) {
                $sql_content = file_get_contents($sql_file);
                // Remove CREATE DATABASE and USE statements from execution block
                $pdo->exec($sql_content);
            }
            return $pdo;
        } catch (PDOException $init_err) {
            die("<div style='background:#0A0B12;color:#f87171;padding:32px;font-family:system-ui,-apple-system,sans-serif;border:1px solid #26243E;border-radius:16px;max-width:600px;margin:40px auto;box-shadow:0 10px 25px rgba(0,0,0,0.5);'>
                <h2 style='color:#F1EEFA;margin-top:0;'>Database Connection Error</h2>
                <p style='color:#C4BCE3;'>Could not connect to MySQL server at <code>" . htmlspecialchars(DB_HOST) . ":" . htmlspecialchars((string)DB_PORT) . "</code>.</p>
                <p style='color:#00F5FF;'><b>If running locally:</b> Please ensure MySQL is running in your XAMPP Control Panel.</p>
                <p style='color:#00F5FF;'><b>If running on Render:</b> Ensure your database environment variables (<code>DB_HOST</code>, <code>DB_USER</code>, <code>DB_PASS</code>, <code>DB_NAME</code>, <code>DB_PORT</code>) are configured in Render service settings.</p>
                <p style='color:#A79DCB;font-size:13px;'>Details: " . htmlspecialchars($init_err->getMessage()) . "</p>
            </div>");
        }
    }
}

/**
 * Sets a persistent 1-year remember token cookie so user is never asked for credentials on repeat visits.
 */
function set_remember_user(int $user_id): void {
    $token = hash_hmac('sha256', (string)$user_id, 'ai_creator_marketplace_auth_key_2026');
    $val = $user_id . ':' . $token;
    setcookie('remember_user', $val, [
        'expires' => time() + 31536000,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

/**
 * Clears the persistent remember token on explicit logout.
 */
function clear_remember_user(): void {
    setcookie('remember_user', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

/**
 * Checks if a user is logged in. Automatically restores session from persistent cookie.
 */
function is_logged_in(): bool {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return true;
    }

    // Auto-restore login from persistent cookie if available
    if (isset($_COOKIE['remember_user']) && !empty($_COOKIE['remember_user'])) {
        $parts = explode(':', $_COOKIE['remember_user'], 2);
        if (count($parts) === 2) {
            $uid = (int)$parts[0];
            $token = $parts[1];
            $expected = hash_hmac('sha256', (string)$uid, 'ai_creator_marketplace_auth_key_2026');
            if (hash_equals($expected, $token)) {
                $db = get_db();
                $stmt = $db->prepare("SELECT id, role, full_name FROM users WHERE id = :id LIMIT 1");
                $stmt->execute(['id' => $uid]);
                $user = $stmt->fetch();
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_name'] = $user['full_name'];
                    return true;
                }
            }
        }
    }

    return false;
}

/**
 * Retrieves the currently logged in user from the database.
 */
function get_logged_in_user(): ?array {
    if (!is_logged_in()) {
        return null;
    }
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

/**
 * Requires authentication and optionally a specific role.
 */
function require_login(?string $role = null): array {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
    $user = get_logged_in_user();
    if (!$user) {
        unset($_SESSION['user_id'], $_SESSION['user_role']);
        header("Location: login.php");
        exit;
    }
    if ($role !== null && $user['role'] !== $role) {
        if ($user['role'] === 'brand') {
            header("Location: brand_dashboard.php");
        } else {
            header("Location: creator_dashboard.php");
        }
        exit;
    }
    return $user;
}

/**
 * Sets a flash message to be shown on next page load.
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Gets and clears flash message.
 */
function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Upload helper for avatars and videos.
 */
function handle_file_upload(array $file, string $target_folder, array $allowed_extensions, int $max_bytes): array {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid file parameter.'];
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'error' => 'No file uploaded.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error code: ' . $file['error']];
    }

    if ($file['size'] > $max_bytes) {
        $mb = round($max_bytes / (1024 * 1024));
        return ['success' => false, 'error' => "File exceeds maximum size of {$mb}MB."];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_extensions, true)) {
        return ['success' => false, 'error' => 'Unsupported file type. Allowed: ' . implode(', ', $allowed_extensions)];
    }

    $upload_dir = __DIR__ . '/../' . trim($target_folder, '/') . '/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = uniqid('upload_', true) . '.' . $ext;
    $destination = $upload_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'error' => 'Failed to move uploaded file.'];
    }

    $relative_path = trim($target_folder, '/') . '/' . $filename;
    return ['success' => true, 'path' => $relative_path, 'filename' => $filename];
}
