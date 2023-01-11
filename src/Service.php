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

    public function delete($idLayananBisnis)
    {
        $query = "DELETE FROM {$this->tableName} WHERE id_layanan_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idLayananBisnis);

        return $stmt->execute();
    }

    public function deleteServicesByBusinessId($idBisnis)
    {
        $query = "DELETE FROM {$this->tableName} WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idBisnis);

        return $stmt->execute();
    }
}
