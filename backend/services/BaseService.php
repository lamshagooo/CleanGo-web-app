<?php

class BaseService {
    protected $dao;

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        $record = $this->dao->getById($this->integer($id, 'ID'));
        if (!$record) {
            throw new OutOfBoundsException('Record not found.');
        }
        return $record;
    }

    public function create($data) {
        $data = $this->validate($data);
        return $this->getById($this->dao->insert($data));
    }

    public function update($id, $data) {
        $current = $this->getById($id);
        $data = $this->validate($data, $current);
        $this->dao->update($id, $data);
        return $this->getById($id);
    }

    public function delete($id) {
        $this->getById($id);
        $this->dao->delete($id);
    }

    protected function fields($data, $required, $optional, $current) {
        if (!$data || array_diff(array_keys($data), array_merge($required, $optional))) {
            throw new InvalidArgumentException('Supply at least one supported field and remove unknown fields.');
        }
        if (!$current) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException("$field is required.");
                }
            }
        }
    }

    protected function integer($value, $field, $min = 1, $max = 2147483647) {
        $number = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => $min, 'max_range' => $max]
        ]);
        if ($number === false) {
            throw new InvalidArgumentException("$field must be an integer between $min and $max.");
        }
        return $number;
    }

    protected function text($value, $field, $max, $optional = false) {
        if ($optional && $value === null) {
            return null;
        }
        if (!is_string($value) || (!$optional && trim($value) === '') || mb_strlen($value) > $max) {
            throw new InvalidArgumentException("$field must be text up to $max characters.");
        }
        return trim($value);
    }

    protected function price($value, $field) {
        if (!is_numeric($value) || $value < 0) {
            throw new InvalidArgumentException("$field must be a number that is 0 or more.");
        }
        return round((float) $value, 2);
    }
}
