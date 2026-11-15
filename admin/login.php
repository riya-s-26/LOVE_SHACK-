<?php
/**
 * THE LOVE SHACK — Admin Login Portal
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

if (is_admin_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    $result = admin_login($username, $password);
    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $result['error'] ?? 'Invalid login credentials.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | The Love Shack</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@700&family=Montserrat:wght@500;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../assets/css/style.css?v=2.2">
  <style>
    body {
      background: linear-gradient(135deg, #164E63 0%, #0f3747 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      font-family: var(--font-body);
    }
    .login-card {
      background: var(--color-white);
      max-width: 440px;
      width: 100%;
      border-radius: var(--radius-lg);
      padding: 44px 36px;
      box-shadow: 0 20px 48px rgba(0, 0, 0, 0.28);
      text-align: center;
    }
    .login-card .logo {
      font-size: 2.1rem;
      color: var(--color-primary);
      justify-content: center;
      margin-bottom: 8px;
    }
    .login-badge {
      font-family: var(--font-ui);
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--color-secondary);
      display: block;
      margin-bottom: 24px;
    }
    .login-alert {
      background: #fee2e2;
      color: #991b1b;
      padding: 12px 16px;
      border-radius: var(--radius-sm);
      font-size: 0.85rem;
      margin-bottom: 20px;
      text-align: left;
    }
    .login-field {
      text-align: left;
      margin-bottom: 20px;
    }
    .login-field label {
      display: block;
      font-family: var(--font-ui);
      font-size: 0.76rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--color-primary);
      margin-bottom: 8px;
    }
    .login-field input {
      width: 100%;
      padding: 14px 16px;
      border: 1.5px solid var(--color-border);
      border-radius: var(--radius-sm);
      font-size: 0.95rem;
    }
    .login-field input:focus {
      outline: none;
      border-color: var(--color-secondary);
    }
  </style>
</head>
<body>

  <div class="login-card">
    <a class="logo" href="../index.php">The Love Shack</a>
    <span class="login-badge">CONCIERGE MANAGEMENT PORTAL</span>

    <?php if (!empty($error)): ?>
      <div class="login-alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="login-field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="e.g. admin" required autofocus autocomplete="username">
      </div>

      <div class="login-field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter administrative password" required autocomplete="current-password">
      </div>

      <button type="submit" class="btn btn-teal" style="width: 100%; padding: 14px; margin-top: 8px;">
        Sign In to Dashboard
      </button>
    </form>

    <div style="margin-top: 24px; font-size: 0.75rem; color: var(--color-text-muted); line-height: 1.5;">
      Default demo credentials: <code>admin</code> / <code>LoveShack2026!</code><br>
      <a href="setup.php" style="color: var(--color-secondary); font-weight: 600; text-decoration: underline;">Initial Setup / Reset Tool</a>
    </div>
  </div>

</body>
</html>
