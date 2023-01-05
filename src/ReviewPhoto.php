<?
class ReviewPhoto
{
    private $conn;
    private $table_name = 'foto_ulasan';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($idUlasan, $fileName)
    {
        $query = "INSERT INTO $this->table_name (id_ulasan, filename) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idUlasan);
        $stmt->bindParam(2, $fileName);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function read($idUlasan)
    {
        $query = "SELECT * FROM $this->table_name WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idUlasan);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function delete($idFotoUlasan)
    {
        $query = "DELETE FROM $this->table_name WHERE id_foto_ulasan = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $idFotoUlasan);
        
        return $stmt->rowCount() > 0;
    }
}
