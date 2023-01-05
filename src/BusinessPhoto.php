<?
class BusinessPhoto
{
    private $conn;
    private $table_name = 'foto_bisnis';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($idBisnis, $fileName)
    {
        $query = "INSERT INTO $this->table_name (id_bisnis, filename) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idBisnis);
        $stmt->bindParam(2, $fileName);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function read($idBisnis)
    {
        $query = "SELECT * FROM $this->table_name WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idBisnis);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function delete($idFotoBisnis)
    {
        // Prepare the delete statement
        $query = "DELETE FROM $this->table_name WHERE id_foto_bisnis = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idFotoBisnis);
        
        return $stmt->rowCount() > 0;
    }
}
