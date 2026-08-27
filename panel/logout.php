<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
oir_logout();
header('Location: index.php');
exit;
