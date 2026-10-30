<?php

require_once __DIR__ . '/BaseDao.php';

class CleanerServiceDao extends BaseDao {

    protected $allowedColumns = [
        'cleaner_id',
        'service_id'
    ];

    public function __construct() {
        parent::__construct("cleaner_services");
    }

    // CREATE
    public function insert($data) {

        if (
            !isset($data['cleaner_id']) ||
            !isset($data['service_id']) ||
            count($data) !== 2
        ) {
            throw new InvalidArgumentException(
                "Cleaner ID and Service ID are required."
            );
        }

        $this->validateColumns($data);

        $stmt = $this->connection->prepare(
            "INSERT INTO cleaner_services (cleaner_id, service_id)
             VALUES (:cleaner_id, :service_id)"
        );

        return $stmt->execute([
            'cleaner_id' => $data['cleaner_id'],
            'service_id' => $data['service_id']
        ]);
    }

    // READ ALL
    public function getAll() {
        return parent::getAll();
    }

    // READ ONE
    public function getByIds($cleanerId, $serviceId) {

        $stmt = $this->connection->prepare(
            "SELECT * FROM cleaner_services
             WHERE cleaner_id = :cleaner_id
             AND service_id = :service_id"
        );

        $stmt->execute([
            'cleaner_id' => $cleanerId,
            'service_id' => $serviceId
        ]);

        return $stmt->fetch();
    }

    // READ BY CLEANER
    public function getByCleanerId($cleanerId) {

        $stmt = $this->connection->prepare(
            "SELECT * FROM cleaner_services
             WHERE cleaner_id = :cleaner_id"
        );

        $stmt->execute(['cleaner_id' => $cleanerId]);

        return $stmt->fetchAll();
    }

    // UPDATE
    public function updateByIds($cleanerId, $serviceId, $newServiceId) {

        $stmt = $this->connection->prepare(
            "UPDATE cleaner_services
             SET service_id = :new_service_id
             WHERE cleaner_id = :cleaner_id
             AND service_id = :service_id"
        );

        return $stmt->execute([
            'new_service_id' => $newServiceId,
            'cleaner_id' => $cleanerId,
            'service_id' => $serviceId
        ]);
    }

    // DELETE
    public function deleteByIds($cleanerId, $serviceId) {

        $stmt = $this->connection->prepare(
            "DELETE FROM cleaner_services
             WHERE cleaner_id = :cleaner_id
             AND service_id = :service_id"
        );

        return $stmt->execute([
            'cleaner_id' => $cleanerId,
            'service_id' => $serviceId
        ]);
    }
}