<?php

ini_set('display_errors', '0');
$autoload = __DIR__ . '/../../vendor/autoload.php';
if (!is_file($autoload)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Run composer install to install FlightPHP.']);
    return;
}

require $autoload;
require __DIR__ . '/../routes/crud.php';
require __DIR__ . '/../routes/locations.php';
require __DIR__ . '/../routes/docs.php';
Flight::start();
