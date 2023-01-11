<?
class Report
{
    private $conn;
    private $table_name = 'report';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($idPengguna, $idUlasan, $report, $description)
    {
        $query = "INSERT INTO $this->table_name (id_pengguna, id_ulasan, report, description) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idPengguna);
        $stmt->bindParam(2, $idUlasan);
        $stmt->bindParam(3, $report);
        $stmt->bindParam(4, $description);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function read($idReport)
    {
        $query = "SELECT * FROM $this->table_name";

        if (isset($idReport) && !is_null($idReport)) {
            $query .= " WHERE id_report = $idReport";
        }

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function readByIdUlasan($idUlasan)
    {
        $query = "SELECT * FROM $this->table_name WHERE id_ulasan = $idUlasan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readDistinct()
    {
        $query = "SELECT DISTINCT * FROM $this->table_name GROUP BY id_ulasan";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function getReportByIdUlasan($idUlasan)
    {
        $query = "SELECT * FROM $this->table_name WHERE id_ulasan = $idUlasan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($idReport)
    {
        $query = "DELETE FROM $this->table_name WHERE id_report = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idReport);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function deleteReportByIdUlasan($idUlasan)
    {
        $query = "DELETE FROM $this->table_name WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idUlasan);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
