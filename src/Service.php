<?php

class Service
{
    private $conn;
    private $tableName = "layanan_bisnis";

    public $id_layanan_bisnis;
    public $id_bisnis;
    public $layanan;
    public $disediakan;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(Service $service)
    {
        $query = "INSERT INTO {$this->tableName} (id_bisnis, layanan, disediakan) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $service->id_bisnis);
        $stmt->bindParam(2, $service->layanan);
        $stmt->bindParam(3, $service->disediakan);

        return $stmt->execute();
    }

    public function readAvailable($idBisnis)
    {
        $query = "SELECT * FROM {$this->tableName} WHERE id_bisnis = ? AND disediakan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis, true]);
        return $stmt->fetchAll();
    }

    public function readUnavailable($idBisnis)
    {
        $query = "SELECT * FROM {$this->tableName} WHERE id_bisnis = ? AND disediakan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis, false]);
        return $stmt->fetchAll();
    }

    public function update(Service $service)
    {
        $query = "UPDATE {$this->tableName} SET id_bisnis = :id_bisnis, layanan = :layanan, disediakan = :disediakan WHERE id_layanan_bisnis = :id_layanan_bisnis";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_bisnis", $service->id_bisnis);
        $stmt->bindParam(":layanan", $service->layanan);
        $stmt->bindParam(":disediakan", $service->disediakan);
        $stmt->bindParam(":id_layanan_bisnis", $service->id_layanan_bisnis);
        return $stmt->rowCount() > 0;
    }

    public function delete(Service $service)
    {
        $query = "DELETE FROM {$this->tableName} WHERE id_layanan_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $service->id_layanan_bisnis);
        return $stmt->rowCount() > 0;
    }
}
