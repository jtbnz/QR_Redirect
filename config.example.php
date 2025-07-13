<?php
// Copy this file to config.php and update the values

return [
    // Secret key for QR code access - used in URL like: index.php?key=YOUR_QR_SECRET
    'qr_secret' => 'CHANGE_THIS_TO_A_SECURE_RANDOM_STRING',
    
    // Admin secret key for admin panel access
    'admin_secret' => 'CHANGE_THIS_TO_ANOTHER_SECURE_RANDOM_STRING',
    
    // Data storage path
    'data_path' => __DIR__ . '/data/incidents.json',
    
    // Session configuration
    'session_name' => 'qr_incident_session',
    'session_lifetime' => 3600, // 1 hour
];