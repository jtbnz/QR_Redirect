<?php
require_once 'includes/bootstrap.php';

// Check if valid QR secret is provided
$qrKey = $_GET['key'] ?? '';
if (!isValidQRSecret($qrKey, $config)) {
    die('Access denied. Invalid or missing access key.');
}

// Get active incidents
$activeIncidents = $storage->getActiveIncidents();

// Handle auto-redirect if only one incident
if (count($activeIncidents) === 1) {
    $incident = reset($activeIncidents);
    header('Location: ' . $incident['url']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow">
    <title>Incident Portal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            width: 90%;
            padding: 2rem;
            text-align: center;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 2rem;
            font-weight: 300;
            letter-spacing: 1px;
        }
        .no-incidents {
            color: #666;
            font-size: 1.2rem;
            padding: 3rem 0;
        }
        .incident-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .incident-item {
            margin-bottom: 1rem;
        }
        .incident-link {
            display: block;
            padding: 1.5rem;
            background-color: #111;
            color: #fff;
            text-decoration: none;
            border: 1px solid #333;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        .incident-link:hover {
            background-color: #222;
            border-color: #555;
            transform: translateY(-2px);
        }
        .incident-link:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Incident Portal</h1>
        
        <?php if (empty($activeIncidents)): ?>
            <p class="no-incidents">No Current Incidents</p>
        <?php else: ?>
            <ul class="incident-list">
                <?php foreach ($activeIncidents as $incident): ?>
                    <li class="incident-item">
                        <a href="<?php echo e($incident['url']); ?>" class="incident-link">
                            <?php echo e($incident['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    
    <script>
        // Auto-redirect if single incident (JavaScript fallback)
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.incident-link');
            if (links.length === 1) {
                window.location.href = links[0].href;
            }
        });
    </script>
</body>
</html>