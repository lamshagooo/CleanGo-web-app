<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ServiceDao.php';

class ServiceService extends BaseService {
    public function __construct() {
        $this->dao = new ServiceDao();
    }

    protected function validate($data, $current = []) {
        $this->fields($data, ['name', 'base_price'], ['description'], $current);

        if (array_key_exists('name', $data)) {
            $data['name'] = $this->text($data['name'], 'name', 100);
        }
        if (array_key_exists('base_price', $data)) {
            $data['base_price'] = $this->price($data['base_price'], 'base_price');
        }
        if (array_key_exists('description', $data)) {
            $data['description'] = $this->text($data['description'], 'description', 16000, true);
        }
        return $data;
    }
}
