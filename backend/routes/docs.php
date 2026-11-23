<?php

Flight::route('GET /docs', function () {
    Flight::response()->header('Content-Type', 'text/html; charset=utf-8');
    readfile(__DIR__ . '/../docs/swagger.html');
});

Flight::route('GET /openapi.json', function () {
    Flight::response()->header('Content-Type', 'application/json');
    readfile(__DIR__ . '/../docs/openapi.json');
});
