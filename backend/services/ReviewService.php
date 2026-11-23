<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/../dao/BookingDao.php';

class ReviewService extends BaseService {
    public function __construct() {
        $this->dao = new ReviewDao();
    }

    protected function validate($data, $current = []) {
        $this->fields($data, ['booking_id', 'rating'], ['comment'], $current);

        if (array_key_exists('booking_id', $data)) {
            $data['booking_id'] = $this->integer($data['booking_id'], 'booking_id');
            $booking = (new BookingDao())->getById($data['booking_id']);
            if (!$booking || $booking['status'] !== 'completed') {
                throw new InvalidArgumentException('Reviews require a completed booking.');
            }
        }
        if (array_key_exists('rating', $data)) {
            $data['rating'] = $this->integer($data['rating'], 'rating', 1, 5);
        }
        if (array_key_exists('comment', $data)) {
            $data['comment'] = $this->text($data['comment'], 'comment', 16000, true);
        }
        return $data;
    }
}
