<?php
require_once '../includes/bootstrap.php';

// Check authentication
if (!isAdminAuthenticated()) {
    header('Location: login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $title = trim($_POST['title'] ?? '');
            $url = trim($_POST['url'] ?? '');
            $status = $_POST['status'] ?? 'active';
            
            if ($title && $url) {
                $storage->createIncident($title, $url, $status);
                $message = 'Incident created successfully';
                $messageType = 'success';
            } else {
                $message = 'Title and URL are required';
                $messageType = 'error';
            }
            break;
            
        case 'toggle_status':
            $id = $_POST['id'] ?? '';
            $incident = $storage->getIncident($id);
            if ($incident) {
                $newStatus = $incident['status'] === 'active' ? 'inactive' : 'active';
                $storage->updateIncident($id, ['status' => $newStatus]);
                $message = 'Status updated successfully';
                $messageType = 'success';
            }
            break;
            
        case 'delete':
            $id = $_POST['id'] ?? '';
            if ($id) {
                $storage->deleteIncident($id);
                $message = 'Incident deleted successfully';
                $messageType = 'success';
            }
            break;
    }
}

// Get all incidents
$incidents = $storage->getAllIncidents();

// Generate QR URL
$qrUrl = getBaseUrl() . 'index.php?key=' . $config['qr_secret'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow">
    <title>Admin Dashboard</title>
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
            padding: 2rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333;
        }
        
        h1 {
            font-size: 2rem;
            font-weight: 300;
            letter-spacing: 1px;
        }
        
        .logout-btn {
            padding: 0.5rem 1rem;
            background-color: transparent;
            color: #fff;
            border: 1px solid #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: #111;
            border-color: #555;
        }
        
        .qr-info {
            background-color: #111;
            border: 1px solid #333;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .qr-info h2 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            font-weight: 400;
        }
        
        .qr-url-container {
            display: flex;
            margin-top: 0.5rem;
            gap: 0.5rem;
            align-items: stretch;
        }
        
        .qr-url {
            font-family: monospace;
            font-size: 0.9rem;
            color: #ccc;
            word-break: break-all;
            background-color: #000;
            padding: 0.5rem;
            border: 1px solid #222;
            flex: 1;
            display: flex;
            align-items: center;
        }
        
        .copy-btn {
            padding: 0.5rem 1rem;
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        
        .copy-btn:hover {
            background-color: #555;
            border-color: #777;
        }
        
        .copy-btn:active {
            background-color: #666;
        }
        
        .copy-btn.copied {
            background-color: #1a3a1a;
            border-color: #22c55e;
            color: #4ade80;
        }
        
        .section {
            margin-bottom: 3rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 300;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr auto auto;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        input[type="text"],
        input[type="url"],
        select {
            padding: 0.75rem;
            background-color: #111;
            border: 1px solid #333;
            color: #fff;
            font-size: 1rem;
        }
        
        input[type="text"]:focus,
        input[type="url"]:focus,
        select:focus {
            outline: none;
            border-color: #666;
        }
        
        button {
            padding: 0.75rem 1.5rem;
            background-color: #fff;
            color: #000;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        button:hover {
            background-color: #ccc;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #222;
        }
        
        .table th {
            font-weight: 500;
            color: #ccc;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 3px;
        }
        
        .status-active {
            background-color: #1a3a1a;
            color: #4ade80;
            border: 1px solid #22c55e;
        }
        
        .status-inactive {
            background-color: #3a1a1a;
            color: #f87171;
            border: 1px solid #ef4444;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .btn-toggle {
            background-color: #333;
            color: #fff;
        }
        
        .btn-toggle:hover {
            background-color: #555;
        }
        
        .btn-delete {
            background-color: #500;
            color: #fff;
        }
        
        .btn-delete:hover {
            background-color: #700;
        }
        
        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 3px;
        }
        
        .message-success {
            background-color: #1a3a1a;
            color: #4ade80;
            border: 1px solid #22c55e;
        }
        
        .message-error {
            background-color: #3a1a1a;
            color: #f87171;
            border: 1px solid #ef4444;
        }
        
        .no-incidents {
            text-align: center;
            color: #666;
            padding: 3rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <a href="?logout" class="logout-btn">Logout</a>
        </div>
        
        <?php if ($message): ?>
            <div class="message message-<?php echo e($messageType); ?>">
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="qr-info">
            <h2>QR Code URL</h2>
            <p>Use this URL for your QR codes:</p>
            <div class="qr-url-container">
                <div class="qr-url" id="qr-url"><?php echo e($qrUrl); ?></div>
                <button type="button" class="copy-btn" onclick="copyToClipboard()">Copy</button>
            </div>
        </div>
        
        <div class="section">
            <h2 class="section-title">Create New Incident</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-grid">
                    <input type="text" name="title" placeholder="Incident Title" required>
                    <input type="url" name="url" placeholder="Redirect URL" required>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <button type="submit">Create</button>
                </div>
            </form>
        </div>
        
        <div class="section">
            <h2 class="section-title">Manage Incidents</h2>
            <?php if (empty($incidents)): ?>
                <p class="no-incidents">No incidents created yet</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incidents as $incident): ?>
                            <tr>
                                <td><?php echo e($incident['title']); ?></td>
                                <td><?php echo e($incident['url']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo e($incident['status']); ?>">
                                        <?php echo e(ucfirst($incident['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo e($incident['created_at']); ?></td>
                                <td>
                                    <div class="actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?php echo e($incident['id']); ?>">
                                            <button type="submit" class="btn-small btn-toggle">
                                                Toggle Status
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo e($incident['id']); ?>">
                                            <button type="submit" class="btn-small btn-delete">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function copyToClipboard() {
            const qrUrlElement = document.getElementById('qr-url');
            const copyBtn = document.querySelector('.copy-btn');
            const text = qrUrlElement.textContent;
            
            // Use the modern Clipboard API if available
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopySuccess(copyBtn);
                }).catch(function(err) {
                    // Fallback to older method
                    fallbackCopyTextToClipboard(text, copyBtn);
                });
            } else {
                // Fallback for older browsers or non-secure contexts
                fallbackCopyTextToClipboard(text, copyBtn);
            }
        }
        
        function fallbackCopyTextToClipboard(text, copyBtn) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess(copyBtn);
                } else {
                    showCopyError(copyBtn);
                }
            } catch (err) {
                showCopyError(copyBtn);
            }
            
            document.body.removeChild(textArea);
        }
        
        function showCopySuccess(copyBtn) {
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Copied!';
            copyBtn.classList.add('copied');
            
            setTimeout(function() {
                copyBtn.textContent = originalText;
                copyBtn.classList.remove('copied');
            }, 2000);
        }
        
        function showCopyError(copyBtn) {
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Failed';
            
            setTimeout(function() {
                copyBtn.textContent = originalText;
            }, 2000);
        }
    </script>
</body>
</html>