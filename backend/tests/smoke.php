<?php

$base = rtrim($argv[1] ?? 'http://127.0.0.1:8083', '/');
$mode = $argv[2] ?? 'crud';
$cleanup = [];

function request($method, $path, $expected, $data = null) {
    global $base;
    $curl = curl_init($base . $path);
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20
    ]);
    if ($data !== null) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
    }
    $body = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    check($status === $expected, "$method $path expected $expected, got $status. $body");
    if ($status === 204) {
        check($body === '', '204 must have an empty response.');
        return;
    }
    return $path === '/docs' ? $body : json_decode($body, true, 512, JSON_THROW_ON_ERROR);
}

function check($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function createRecord($path, $data) {
    global $cleanup;
    request('GET', $path, 200);
    $record = request('POST', $path, 201, $data);
    $item = isset($record['id'])
        ? "$path/" . $record['id']
        : "$path/" . $record['cleaner_id'] . '/' . $record['service_id'];
    $cleanup[$path] = $item;
    check(request('GET', $item, 200) === $record, "Read after create failed for $item.");
    return $record;
}

function updateRecord($path, $data, $newPath = null) {
    global $cleanup;
    $record = request('PATCH', $cleanup[$path], 200, $data);
    if ($newPath !== null) {
        $cleanup[$path] = $newPath;
    }
    foreach ($data as $field => $value) {
        check(($record[$field] ?? null) == $value, "$field did not update.");
    }
}

function testCrud() {
    global $cleanup;
    $suffix = uniqid();
    $cleaner = createRecord('/users', [
        'name' => 'Smoke Cleaner', 'email' => "cleaner-$suffix@example.com",
        'password' => 'fixture-password', 'role' => 'cleaner'
    ]);
    check(!array_key_exists('password', $cleaner), 'Create exposed a password.');
    updateRecord('/users', ['name' => 'Updated Cleaner']);
    request('POST', '/users', 409, [
        'name' => 'Duplicate', 'email' => $cleaner['email'],
        'password' => 'fixture-password', 'role' => 'cleaner'
    ]);
    $cleanup['cleaner-user'] = $cleanup['/users'];

    $customer = createRecord('/users', [
        'name' => 'Smoke Customer', 'email' => "customer-$suffix@example.com",
        'password' => 'fixture-password', 'role' => 'customer'
    ]);
    foreach (request('GET', '/users', 200) as $user) {
        check(!array_key_exists('password', $user), 'List exposed a password.');
    }

    request('POST', '/cleaner-profiles', 400, ['user_id' => $customer['id'], 'hourly_rate' => 20]);
    $cleanerProfile = createRecord('/cleaner-profiles', ['user_id' => $cleaner['id'], 'hourly_rate' => 20]);
    updateRecord('/cleaner-profiles', ['experience_years' => 2]);
    request('POST', '/services', 400, ['name' => 'Invalid', 'base_price' => -1]);
    $service = createRecord('/services', ['name' => 'Smoke Service', 'base_price' => 30]);
    updateRecord('/services', ['name' => 'Updated Service']);
    $cleanup['first-service'] = $cleanup['/services'];
    $secondService = createRecord('/services', ['name' => 'Second Smoke Service', 'base_price' => 35]);

    $pair = ['cleaner_id' => $cleanerProfile['id'], 'service_id' => $service['id']];
    createRecord('/cleaner-services', $pair);
    request('POST', '/cleaner-services', 409, $pair);
    updateRecord('/cleaner-services', ['service_id' => $secondService['id']],
        '/cleaner-services/' . $cleanerProfile['id'] . '/' . $secondService['id']);
    request('GET', '/cleaner-services/' . $cleanerProfile['id'] . '/' . $service['id'], 404);

    $data = [
        'user_id' => $customer['id'], 'cleaner_id' => $cleanerProfile['id'],
        'service_id' => $secondService['id'], 'booking_date' => '2030-06-01',
        'booking_time' => '09:00', 'duration' => 2
    ];
    request('POST', '/bookings', 400, array_replace($data, ['booking_date' => '2030-02-30']));
    request('POST', '/bookings', 400, array_replace($data, ['service_id' => $service['id']]));
    $booking = createRecord('/bookings', $data);
    check($booking['status'] === 'pending', 'New booking should default to pending.');
    request('POST', '/reviews', 400, ['booking_id' => $booking['id'], 'rating' => 5]);
    updateRecord('/bookings', ['status' => 'completed']);

    request('POST', '/reviews', 400, ['booking_id' => $booking['id'], 'rating' => 6]);
    createRecord('/reviews', ['booking_id' => $booking['id'], 'rating' => 5]);
    updateRecord('/reviews', ['comment' => 'Great work!']);
    request('POST', '/reviews', 409, ['booking_id' => $booking['id'], 'rating' => 4]);
    request('DELETE', $cleanup['/bookings'], 409);
    request('DELETE', $cleanup['/cleaner-services'], 409);
    request('POST', '/reviews', 400, '{');
    request('GET', '/users/invalid', 400);
}

function testDocumentation() {
    check(str_contains(request('GET', '/docs', 200), 'SwaggerUIBundle'), 'Swagger page missing.');
    $spec = request('GET', '/openapi.json', 200);
    foreach (['users', 'cleaner-profiles', 'services', 'cleaner-services', 'bookings', 'reviews'] as $entity) {
        $list = $spec['paths']["/$entity"];
        $itemPath = $entity === 'cleaner-services' ? "/$entity/{cleanerId}/{serviceId}" : "/$entity/{id}";
        $item = $spec['paths'][$itemPath];
        check(isset($list['get'], $list['post'], $item['get'], $item['patch'], $item['delete']),
            "OpenAPI operations missing for $entity.");
    }
    check(isset($spec['paths']['/locations/search']['get']), 'OpenAPI location search missing.');
    request('GET', '/missing-route', 404);
    request('GET', '/locations/search', 400);
}

function testLocations() {
    $url = '/locations/search?address=';
    $response = request('GET', $url . urlencode('Vijecnica, Sarajevo'), 200);
    check($response['matches'][0]['address'] === 'Sarajevo City Hall', 'Expected the fixture location.');
    check($response['matches'][0]['latitude'] === 43.859, 'Expected a numeric latitude.');
    request('GET', $url . 'empty', 429);
    sleep(1);
    check(request('GET', $url . 'empty', 200)['matches'] === [], 'Expected an empty match list.');
    sleep(1);
    request('GET', $url . 'invalid', 502);
    sleep(1);
    request('GET', $url . 'upstream-error', 502);
    sleep(1);
    request('GET', $url . 'timeout', 504);
}

$failed = false;
try {
    testDocumentation();
    if ($mode === 'crud') {
        testCrud();
    } elseif ($mode === 'locations') {
        testLocations();
    } else {
        throw new InvalidArgumentException('Choose crud or locations.');
    }
} catch (Throwable $error) {
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    $failed = true;
} finally {
    foreach (array_reverse($cleanup) as $item) {
        try {
            request('DELETE', $item, 204);
            request('GET', $item, 404);
        } catch (Throwable $error) {
            fwrite(STDERR, "Cleanup: " . $error->getMessage() . PHP_EOL);
            $failed = true;
        }
    }
}
if ($failed) {
    exit(1);
}
echo "Passed: $mode and documentation checks." . PHP_EOL;
