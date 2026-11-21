<?php

require_once __DIR__ . '/../services/LocationService.php';

Flight::route('GET /locations/search', function () {
    try {
        Flight::json((new LocationService())->search(Flight::request()->query->address));
    } catch (InvalidArgumentException $error) {
        Flight::json(['error' => $error->getMessage()], 400);
    } catch (RuntimeException $error) {
        if (!in_array($error->getCode(), [429, 502, 504], true)) {
            throw $error;
        }
        if ($error->getCode() === 429) {
            Flight::response()->header('Retry-After', '1');
        }
        Flight::json(['error' => $error->getMessage()], $error->getCode());
    }
});
