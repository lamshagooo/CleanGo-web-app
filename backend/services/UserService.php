<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/CleanerProfileDao.php';
require_once __DIR__ . '/../dao/BookingDao.php';

class UserService extends BaseService {
    public function __construct() {
        $this->dao = new UserDao();
    }

    public function getAll() {
        $users = parent::getAll();
        foreach ($users as &$user) {
            unset($user['password']);
        }
        return $users;
    }

    public function getById($id) {
        $user = parent::getById($id);
        unset($user['password']);
        return $user;
    }

    protected function validate($data, $current = []) {
        $this->fields($data, ['name', 'email', 'password', 'role'], [], $current);

        if (array_key_exists('name', $data)) {
            $data['name'] = $this->text($data['name'], 'name', 100);
        }
        if (array_key_exists('email', $data)) {
            $data['email'] = $this->text($data['email'], 'email', 150);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Enter a valid email address.');
            }
        }
        if (array_key_exists('password', $data)) {
            if (!is_string($data['password']) || strlen(trim($data['password'])) < 8) {
                throw new InvalidArgumentException('Password must have at least 8 characters.');
            }
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (array_key_exists('role', $data)) {
            if (!in_array($data['role'], ['customer', 'cleaner', 'admin'], true)) {
                throw new InvalidArgumentException('Role must be customer, cleaner or admin.');
            }
            if ($current && $data['role'] !== $current['role']) {
                if ((new CleanerProfileDao())->getByUserId($current['id']) ||
                    (new BookingDao())->getByUserId($current['id'])) {
                    throw new DomainException('Cannot change the role of a user with a profile or bookings.');
                }
            }
        }
        return $data;
    }
}
