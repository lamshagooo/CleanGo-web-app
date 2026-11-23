<?php

class LocationService {
    public function search($address) {
        if (!is_string($address) || mb_strlen(trim($address)) < 3 || mb_strlen($address) > 200) {
            throw new InvalidArgumentException('Enter an address between 3 and 200 characters.');
        }

        $this->checkRateLimit();

        $endpoint = getenv('NOMINATIM_SEARCH_URL') ?: 'https://nominatim.openstreetmap.org/search';
        $url = $endpoint . '?' . http_build_query([
            'q' => trim($address),
            'format' => 'jsonv2',
            'limit' => 5
        ]);

        $request = curl_init($url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($request, CURLOPT_TIMEOUT, 10);
        curl_setopt($request, CURLOPT_USERAGENT, 'CleanGo-StudentProject/1.0');
        $certificates = 'C:/xampp/apache/bin/curl-ca-bundle.crt';
        if (is_file($certificates)) {
            curl_setopt($request, CURLOPT_CAINFO, $certificates);
        }

        $body = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        $errorNumber = curl_errno($request);
        curl_close($request);

        if ($errorNumber === CURLE_OPERATION_TIMEDOUT) {
            $this->logError('Timeout for address: ' . $address);
            throw new RuntimeException('The address provider timed out.', 504);
        }
        $places = json_decode((string) $body, true);
        if ($status !== 200 || !is_array($places)) {
            $this->logError('HTTP status ' . $status . ', cURL error ' . $errorNumber . ' for address: ' . $address);
            throw new RuntimeException('The address provider is unavailable.', 502);
        }

        $matches = [];
        foreach ($places as $place) {
            if (!isset($place['display_name'], $place['lat'], $place['lon'])) {
                $this->logError('Invalid result for address: ' . $address);
                throw new RuntimeException('The address provider returned invalid results.', 502);
            }
            $matches[] = [
                'address' => $place['display_name'],
                'latitude' => (float) $place['lat'],
                'longitude' => (float) $place['lon']
            ];
        }

        return [
            'matches' => $matches,
            'attribution' => '© OpenStreetMap contributors',
            'attribution_url' => 'https://www.openstreetmap.org/copyright'
        ];
    }

    private function checkRateLimit() {
        $file = __DIR__ . '/../logs/nominatim-rate.log';
        $lastRequest = is_file($file) ? (float) file_get_contents($file) : 0;

        if (microtime(true) - $lastRequest < 1) {
            throw new RuntimeException('Wait one second before searching again.', 429);
        }
        file_put_contents($file, (string) microtime(true), LOCK_EX);
    }

    private function logError($message) {
        $line = date('Y-m-d H:i:s') . ' Nominatim error: ' . $message . PHP_EOL;
        file_put_contents(__DIR__ . '/../logs/external-api.log', $line, FILE_APPEND);
    }
}
