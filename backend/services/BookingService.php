<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/../dao/CleanerServiceDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/ReviewDao.php';

class BookingService extends BaseService {
    public function __construct() {
        $this->dao = new BookingDao();
    }

    protected function validate($data, $current = []) {
        $required = ['user_id', 'cleaner_id', 'service_id', 'booking_date', 'booking_time', 'duration'];
        $this->fields($data, $required, ['status'], $current);

        foreach (['user_id', 'cleaner_id', 'service_id', 'duration'] as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $this->integer($data[$field], $field);
            }
        }
        if (isset($data['user_id'])) {
            $user = (new UserDao())->getById($data['user_id']);
            if (!$user || $user['role'] !== 'customer') {
                throw new InvalidArgumentException('user_id must belong to a customer.');
            }
        }
        if (isset($data['cleaner_id']) || isset($data['service_id'])) {
            $booking = array_replace($current, $data);
            if (!(new CleanerServiceDao())->getByIds($booking['cleaner_id'], $booking['service_id'])) {
                throw new InvalidArgumentException('This cleaner does not offer the selected service.');
            }
        }
        if (array_key_exists('booking_date', $data) && !$this->matchesFormat($data['booking_date'], 'Y-m-d')) {
            throw new InvalidArgumentException('booking_date must be a real date in YYYY-MM-DD format.');
        }
        if (array_key_exists('booking_time', $data) &&
            !$this->matchesFormat($data['booking_time'], 'H:i') &&
            !$this->matchesFormat($data['booking_time'], 'H:i:s')) {
            throw new InvalidArgumentException('booking_time must use HH:MM or HH:MM:SS format.');
        }
        if (array_key_exists('status', $data)) {
            if (!in_array($data['status'], ['pending', 'confirmed', 'completed', 'cancelled'], true)) {
                throw new InvalidArgumentException('Invalid booking status.');
            }
            if (($current['status'] ?? '') === 'completed' && $data['status'] !== 'completed' &&
                (new ReviewDao())->getByBookingId($current['id'])) {
                throw new DomainException('A reviewed booking must stay completed.');
            }
        }
        return $data;
    }

    private function matchesFormat($value, $format) {
        if (!is_string($value)) {
            return false;
        }
        $parsed = DateTime::createFromFormat($format, $value);
        return $parsed && $parsed->format($format) === $value;
    }
}
