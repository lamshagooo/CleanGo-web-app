<?php

header('Content-Type: application/json');
$address = $_GET['q'] ?? '';
if ($address === 'upstream-error') {
    http_response_code(503);
    echo '{}';
} elseif ($address === 'invalid') {
    echo '[{"display_name": "Missing coordinates"}]';
} elseif ($address === 'empty') {
    echo '[]';
} elseif ($address === 'timeout') {
    sleep(11);
    echo '[]';
} elseif ($address === 'Vijecnica, Sarajevo' && ($_GET['format'] ?? '') === 'jsonv2' &&
    ($_GET['limit'] ?? '') === '5' && str_starts_with($_SERVER['HTTP_USER_AGENT'] ?? '', 'CleanGo-')) {
    echo '[{"display_name": "Sarajevo City Hall", "lat": "43.859", "lon": "18.434"}]';
} else {
    http_response_code(400);
    echo '{}';
}
