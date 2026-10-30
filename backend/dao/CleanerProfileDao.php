<?php

require_once __DIR__ . '/BaseDao.php';

class CleanerProfileDao extends BaseDao {

    protected $allowedColumns = [
        'user_id',
        'bio',
        'hourly_rate',
        'location',
        'experience_years'
    ];

    public function __construct() {
        parent::__construct("cleaner_profiles");
    }

    public function getByUserId($userId) {

        $stmt = $this->connection->prepare(
            "SELECT * FROM cleaner_profiles WHERE user_id = :user_id"
        );

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch();
    }
}