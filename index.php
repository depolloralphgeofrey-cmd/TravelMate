<?php
// Entry point: login first, then the matching flow
require_once __DIR__ . '/includes/config.php';
header('Location: ' . (is_logged_in() ? landing_url() : 'login.php'));
exit;
