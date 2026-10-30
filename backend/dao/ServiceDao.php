<?php

require_once __DIR__ . '/BaseDao.php';

class ServiceDao extends BaseDao {

    protected $allowedColumns = [
        'name',
        'description',
        'base_price'
    ];

    public function __construct() {
        parent::__construct("services");
    }
}