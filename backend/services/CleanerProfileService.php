<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CleanerProfileDao.php';
require_once __DIR__ . '/../dao/UserDao.php';

class CleanerProfileService extends BaseService {
    public function __construct() {
        $this->dao = new CleanerProfileDao();
    }

    protected function validate($data, $current = []) {
        $this->fields($data, ['user_id', 'hourly_rate'], ['bio', 'location', 'experience_years'], $current);

        if (array_key_exists('user_id', $data)) {
            $data['user_id'] = $this->integer($data['user_id'], 'user_id');
            $user = (new UserDao())->getById($data['user_id']);
            if (!$user || $user['role'] !== 'cleaner') {
                throw new InvalidArgumentException('user_id must belong to a cleaner.');
            }
        }
        if (array_key_exists('hourly_rate', $data)) {
            $data['hourly_rate'] = $this->price($data['hourly_rate'], 'hourly_rate');
        }
        if (array_key_exists('experience_years', $data)) {
            $data['experience_years'] = $this->integer($data['experience_years'], 'experience_years', 0);
        }
        foreach (['bio' => 16000, 'location' => 100] as $field => $max) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $this->text($data[$field], $field, $max, true);
            }
        }
        return $data;
    }
}
