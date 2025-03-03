<?php

class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = new Database();
        if (!$this->table) {
            $this->table = strtolower((new ReflectionClass($this))->getShortName()) . 's';
        }
    }

    // Create new record
    public function create($data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";

        $this->db->query($sql);
        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $this->db->execute();
    }

    // Read all records
    public function getAll() {
        $this->db->query("SELECT * FROM {$this->table}");
        return $this->db->resultSet();
    }

    // Find record by ID
    public function findById($id) {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update record by ID
    public function update($id, $data) {
        $setPart = [];
        foreach ($data as $key => $value) {
            $setPart[] = "$key = :$key";
        }
        $setPart = implode(', ', $setPart);
        $sql = "UPDATE {$this->table} SET $setPart WHERE id = :id";

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $this->db->execute();
    }

    // Delete record by ID
    public function delete($id) {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
