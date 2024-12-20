<?php
require_once __DIR__ . '/bootstrap.php';

// Use a custom function for headers, defaulting to PHP's built-in header()
$headerFunction = $headerFunction ?? 'header';

// Destroy the session
session_unset();
session_destroy();

// Redirect to login page
call_user_func($headerFunction, 'Location: /login.php');
