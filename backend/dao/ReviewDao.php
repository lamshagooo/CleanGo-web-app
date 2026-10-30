<?php

require_once __DIR__ . '/BaseDao.php';

class ReviewDao extends BaseDao {

    protected $allowedColumns = [
        'booking_id',
        'rating',
        'comment'
    ];

    public function __construct() {
        parent::__construct("reviews");
    }

    public function getByBookingId($bookingId) {

        $stmt = $this->connection->prepare(
            "SELECT * FROM reviews WHERE booking_id = :booking_id"
        );

        $stmt->execute(['booking_id' => $bookingId]);

        return $stmt->fetch();
    }
}