<?php
/**
 * THE LOVE SHACK — Initial Administrator Setup & Password Reset Tool
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db_connection();
$message = '';
$error = '';

$hasExistingAdmin = false;
if ($pdo) {
    try {
        $count = (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
        $hasExistingAdmin = $count > 0;
    } catch (Exception $e) {
        $error = "Database tables not yet imported. Please import /database/love_shack.sql via phpMyAdmin.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $fullName = trim((string)($_POST['full_name'] ?? 'Villa Administrator'));
    $password = (string)($_POST['password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    if (empty($username) || strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (!$email) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long for security.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (!$pdo) {
        $error = "Cannot connect to MySQL database. Please verify config/database.php settings.";
    } else {
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            // Check if admin user exists
            $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            $existing = $stmt->fetch();

            if ($existing) {
                $upd = $pdo->prepare("UPDATE admin_users SET email = ?, full_name = ?, password_hash = ?, is_active = 1 WHERE id = ?");
                $upd->execute([$email, $fullName, $hash, $existing['id']]);
                $message = "Administrator account '{$username}' successfully updated! You can now log in.";
            } else {
                $ins = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash, full_name, role, is_active) VALUES (?, ?, ?, ?, 'admin', 1)");
                $ins->execute([$username, $email, $hash, $fullName]);
                $message = "Administrator account '{$username}' successfully created! You can now log in.";
            }
        } catch (Exception $e) {
            $error = "Error updating admin user: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Setup | The Love Shack</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@700&family=Montserrat:wght@500;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../assets/css/style.css?v=2.2">
  <style>
    body {
      background: #f4f8f8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      font-family: var(--font-body);
    }
    .setup-card {
      background: var(--color-white);
      max-width: 520px;
      width: 100%;
      border-radius: var(--radius-lg);
      padding: 40px;
      box-shadow: 0 16px 40px rgba(15, 55, 71, 0.1);
      border: 1px solid var(--color-border);
    }
    .setup-card h1 {
      font-size: 1.8rem;
      color: var(--color-primary);
      margin-bottom: 6px;
    }
    .setup-card p {
      color: var(--color-text-muted);
      font-size: 0.92rem;
      margin-bottom: 24px;
    }
    .setup-field {
      margin-bottom: 18px;
    }
    .setup-field label {
      display: block;
      font-family: var(--font-ui);
      font-size: 0.76rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--color-primary);
      margin-bottom: 6px;
    }
    .setup-field input {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-sm);
      font-size: 0.95rem;
    }
  </style>
</head>
<body>

  <div class="setup-card">
    <a class="logo" href="../index.php" style="margin-bottom: 12px; font-size: 1.6rem; color: var(--color-primary);">The Love Shack</a>
    <h1>Administrator Setup</h1>
    <p>Configure custom administrative credentials for the host console. Passwords are encrypted using PHP BCRYPT hashing.</p>

    <?php if (!empty($message)): ?>
      <div style="background: #ecfdf5; border-left: 4px solid var(--color-secondary); color: #065f46; padding: 14px; border-radius: var(--radius-sm); margin-bottom: 20px;">
        <?= htmlspecialchars($message) ?><br>
        <a href="login.php" style="font-weight: 700; color: var(--color-secondary); text-decoration: underline; margin-top: 6px; display: inline-block;">Go to Login &rarr;</a>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 14px; border-radius: var(--radius-sm); margin-bottom: 20px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="setup.php">
      <div class="setup-field">
        <label for="username">Admin Username</label>
        <input type="text" id="username" name="username" value="admin" required>
      </div>

      <div class="setup-field">
        <label for="full_name">Host / Administrator Name</label>
        <input type="text" id="full_name" name="full_name" value="Love Shack Host Concierge" required>
      </div>

      <div class="setup-field">
        <label for="email">Admin Contact Email</label>
        <input type="email" id="email" name="email" value="admin@loveshackaruba.com" required>
      </div>

      <div class="setup-field">
        <label for="password">New Password (Min 8 chars)</label>
        <input type="password" id="password" name="password" placeholder="Enter secure password" required minlength="8">
      </div>

      <div class="setup-field">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-type password" required minlength="8">
      </div>

      <button type="submit" class="btn btn-teal" style="width: 100%; padding: 14px; margin-top: 10px;">
        Save Administrator Account
      </button>
    </form>

    <div style="margin-top: 20px; text-align: center;">
      <a href="login.php" class="btn-text-link">Return to Login Portal</a>
    </div>
  </div>

</body>
</html>
