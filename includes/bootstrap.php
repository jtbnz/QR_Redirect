<?php
// Common bootstrap file for all pages

// Start session
session_start();

// Load configuration
$configFile = __DIR__ . '/../config.php';
if (!file_exists($configFile)) {
    die('Error: config.php not found. Please copy config.example.php to config.php and update the values.');
}
$config = require $configFile;

// Include required classes
require_once __DIR__ . '/IncidentStorage.php';

// Initialize incident storage
$storage = new IncidentStorage($config['data_path']);

// Helper function to check admin authentication
function isAdminAuthenticated() {
    return isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;
}

// Helper function to validate QR secret
function isValidQRSecret($key, $config) {
    return !empty($key) && $key === $config['qr_secret'];
}

// Helper function to sanitize output
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Helper function to get base URL
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    $scriptName = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    return $protocol . $domainName . $scriptName;
}