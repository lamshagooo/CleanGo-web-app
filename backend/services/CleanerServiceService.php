<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CleanerServiceDao.php';
require_once __DIR__ . '/../dao/CleanerProfileDao.php';
require_once __DIR__ . '/../dao/ServiceDao.php';

class CleanerServiceService extends BaseService {
    public function __construct() {
        $this->dao = new CleanerServiceDao();
    }

    public function getByIds($cleanerId, $serviceId) {
        $cleanerId = $this->integer($cleanerId, 'cleaner_id');
        $serviceId = $this->integer($serviceId, 'service_id');
        $record = $this->dao->getByIds($cleanerId, $serviceId);
        if (!$record) {
            throw new OutOfBoundsException('Cleaner service not found.');
        }
        return $record;
    }

    public function create($data) {
        $this->fields($data, ['cleaner_id', 'service_id'], [], []);
        $cleanerId = $this->integer($data['cleaner_id'], 'cleaner_id');
        $serviceId = $this->checkService($data['service_id']);
        if (!(new CleanerProfileDao())->getById($cleanerId)) {
            throw new InvalidArgumentException('Cleaner profile does not exist.');
        }
        $this->dao->insert(['cleaner_id' => $cleanerId, 'service_id' => $serviceId]);
        return $this->getByIds($cleanerId, $serviceId);
    }

    public function updatePair($cleanerId, $serviceId, $data) {
        $this->getByIds($cleanerId, $serviceId);
        $this->fields($data, ['service_id'], [], []);
        $newServiceId = $this->checkService($data['service_id']);
        $this->dao->updateByIds($cleanerId, $serviceId, $newServiceId);
        return $this->getByIds($cleanerId, $newServiceId);
    }

    public function deletePair($cleanerId, $serviceId) {
        $this->getByIds($cleanerId, $serviceId);
        $this->dao->deleteByIds($cleanerId, $serviceId);
    }

    private function checkService($value) {
        $serviceId = $this->integer($value, 'service_id');
        if (!(new ServiceDao())->getById($serviceId)) {
            throw new InvalidArgumentException('Cleaning service does not exist.');
        }
        return $serviceId;
    }
}
