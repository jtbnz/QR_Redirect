<?php
require_once '../includes/bootstrap.php';

// If already logged in, redirect to dashboard
if (isAdminAuthenticated()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedKey = $_POST['admin_key'] ?? '';
    
    if ($submittedKey === $config['admin_secret']) {
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_login_time'] = time();
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid admin key';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow">
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #000;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background-color: #111;
            padding: 3rem;
            border: 1px solid #333;
            max-width: 400px;
            width: 90%;
        }
        
        h1 {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ccc;
            font-size: 0.9rem;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            background-color: #000;
            border: 1px solid #333;
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        input[type="password"]:focus {
            outline: none;
            border-color: #666;
        }
        
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #fff;
            color: #000;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        button:hover {
            background-color: #ccc;
        }
        
        button:active {
            transform: translateY(1px);
        }
        
        .error {
            background-color: #200;
            border: 1px solid #500;
            color: #faa;
            padding: 0.75rem;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo e($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="admin_key">Admin Secret Key</label>
                <input type="password" id="admin_key" name="admin_key" required autofocus>
            </div>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>