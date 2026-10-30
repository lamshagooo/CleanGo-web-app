<?php

class BaseDao {

    protected $connection;
    protected $table;
    protected $allowedColumns = [];

    public function __construct($table) {

        $allowedTables = [
            'users',
            'cleaner_profiles',
            'services',
            'cleaner_services',
            'bookings',
            'reviews'
        ];

        if (!in_array($table, $allowedTables, true)) {
            throw new InvalidArgumentException("Table is not allowed.");
        }

        $config = require __DIR__ . '/../config/config.php';
        $db = $config['db'];

        $host = $db['host'];
        $port = $db['port'];
        $dbname = $db['dbname'];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        $this->connection = new PDO(
            $dsn,
            $db['username'],
            $db['password']
        );

        $this->connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $this->connection->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

        $this->table = $table;
    }

    protected function validateColumns($data) {

        foreach (array_keys($data) as $column) {

            if (!in_array($column, $this->allowedColumns, true)) {
                throw new InvalidArgumentException(
                    "Column '$column' is not allowed."
                );
            }
        }
    }

    // CREATE
    public function insert($data) {

        if (empty($data)) {
            throw new InvalidArgumentException("Insert data cannot be empty.");
        }

        $this->validateColumns($data);

        $columns = array_keys($data);

        $columnNames = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $sql = "INSERT INTO {$this->table} ($columnNames)
                VALUES ($placeholders)";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);

        return $this->connection->lastInsertId();
    }

    // READ ALL
    public function getAll() {

        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table}"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // READ ONE
    public function getById($id) {

        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    // UPDATE
    public function update($id, $data) {

        if (empty($data)) {
            throw new InvalidArgumentException("Update data cannot be empty.");
        }

        $this->validateColumns($data);

        $fields = [];

        foreach ($data as $column => $value) {
            $fields[] = "$column = :$column";
        }

        $setClause = implode(', ', $fields);

        $sql = "UPDATE {$this->table}
                SET $setClause
                WHERE id = :id";

        $data['id'] = $id;

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute($data);
    }

    // DELETE
    public function delete($id) {

        $stmt = $this->connection->prepare(
            "DELETE FROM {$this->table} WHERE id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }
}