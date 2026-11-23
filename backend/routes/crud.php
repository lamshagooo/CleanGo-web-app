<?php

foreach (['User', 'CleanerProfile', 'Service', 'CleanerService', 'Booking', 'Review'] as $entity) {
    require_once __DIR__ . '/../services/' . $entity . 'Service.php';
}

function jsonBody() {
    $body = json_decode(Flight::request()->getBody());
    if (!is_object($body)) {
        throw new InvalidArgumentException('Send a valid JSON object.');
    }
    return (array) $body;
}

function respond($action, $status = 200) {
    try {
        $result = $action();
        if ($status === 204) {
            Flight::response()->status(204)->send();
            return;
        }
        Flight::json($result, $status);
    } catch (InvalidArgumentException $error) {
        Flight::json(['error' => $error->getMessage()], 400);
    } catch (OutOfBoundsException $error) {
        Flight::json(['error' => $error->getMessage()], 404);
    } catch (DomainException $error) {
        Flight::json(['error' => $error->getMessage()], 409);
    } catch (PDOException $error) {
        $code = $error->errorInfo[1] ?? null;
        if ($code === 1062) {
            Flight::json(['error' => 'A record with these unique values already exists.'], 409);
        } elseif ($code === 1451 || $code === 1452) {
            Flight::json(['error' => 'This change conflicts with a related record.'], 409);
        } else {
            Flight::json(['error' => 'Database operation failed.'], 500);
        }
    }
}

Flight::map('notFound', function () {
    Flight::json(['error' => 'Route not found.'], 404);
});
Flight::map('error', function ($error) {
    Flight::json(['error' => 'Unexpected server error.'], 500);
});

function crudRoutes($path, $service) {
    Flight::route("GET $path", function () use ($service) {
        respond(fn () => (new $service())->getAll());
    });
    Flight::route("POST $path", function () use ($service) {
        respond(fn () => (new $service())->create(jsonBody()), 201);
    });
    Flight::route("GET $path/@id", function ($id) use ($service) {
        respond(fn () => (new $service())->getById($id));
    });
    Flight::route("PATCH $path/@id", function ($id) use ($service) {
        respond(fn () => (new $service())->update($id, jsonBody()));
    });
    Flight::route("DELETE $path/@id", function ($id) use ($service) {
        respond(fn () => (new $service())->delete($id), 204);
    });
}

crudRoutes('/users', UserService::class);
crudRoutes('/cleaner-profiles', CleanerProfileService::class);
crudRoutes('/services', ServiceService::class);
crudRoutes('/bookings', BookingService::class);
crudRoutes('/reviews', ReviewService::class);

Flight::route('GET /cleaner-services', function () {
    respond(fn () => (new CleanerServiceService())->getAll());
});
Flight::route('POST /cleaner-services', function () {
    respond(fn () => (new CleanerServiceService())->create(jsonBody()), 201);
});
Flight::route('GET /cleaner-services/@cleanerId/@serviceId', function ($cleanerId, $serviceId) {
    respond(fn () => (new CleanerServiceService())->getByIds($cleanerId, $serviceId));
});
Flight::route('PATCH /cleaner-services/@cleanerId/@serviceId', function ($cleanerId, $serviceId) {
    respond(fn () => (new CleanerServiceService())->updatePair($cleanerId, $serviceId, jsonBody()));
});
Flight::route('DELETE /cleaner-services/@cleanerId/@serviceId', function ($cleanerId, $serviceId) {
    respond(fn () => (new CleanerServiceService())->deletePair($cleanerId, $serviceId), 204);
});
