<?php

require_once __DIR__ . '/BaseDao.php';

class BookingDao extends BaseDao {

    protected $allowedColumns = [
        'user_id',
        'cleaner_id',
        'service_id',
        'booking_date',
        'booking_time',
        'duration',
        'status'
    ];

    public function __construct() {
        parent::__construct("bookings");
    }

    public function getByUserId($userId) {

        $stmt = $this->connection->prepare(
            "SELECT * FROM bookings WHERE user_id = :user_id"
        );

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getByCleanerId($cleanerId) {

        $stmt = $this->connection->prepare(
            "SELECT * FROM bookings WHERE cleaner_id = :cleaner_id"
        );

        $stmt->execute(['cleaner_id' => $cleanerId]);

        return $stmt->fetchAll();
    }
}