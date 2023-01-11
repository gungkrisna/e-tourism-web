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

    public function getPhotoById($idFotoBisnis, $idBisnis)
    {
        $query = "SELECT filename FROM $this->table_name WHERE id_foto_bisnis = ? AND id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idFotoBisnis);
        $stmt->bindParam(2, $idBisnis);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($idFotoBisnis = null, $idBisnis = null)
    {
        $query = "DELETE FROM $this->table_name WHERE ";
        $params = array();

        if ($idFotoBisnis !== null) {
            $query .= "id_foto_bisnis = ?";
            $params[] = $idFotoBisnis;
        }

        if ($idBisnis !== null) {
            if (!empty($params)) {
                $query .= " AND ";
            }
            $query .= "id_bisnis = ?";
            $params[] = $idBisnis;
        }

        $stmt = $this->conn->prepare($query);

        for ($i = 0; $i < count($params); $i++) {
            $stmt->bindParam($i + 1, $params[$i]);
        }

        return $stmt->execute();
    }
}
