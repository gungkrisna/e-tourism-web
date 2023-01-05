<?
class Wishlist
{
    private $conn;
    private $tableName = "wishlist";

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function add(int $idPengguna, int $idBisnis)
    {
        $query = "INSERT INTO $this->tableName (id_pengguna, id_bisnis) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idPengguna, $idBisnis]);
        return $stmt->rowCount() > 0;
    }

    public function read(int $idPengguna)
    {
        $query = "SELECT * FROM $this->tableName WHERE id_pengguna = ? ORDER BY id_wishlist DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idPengguna]);
        return $stmt->fetchAll();
    }

    public function delete(int $idWishlist)
    {
        $query = "DELETE FROM $this->tableName WHERE id_wishlist = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idWishlist]);
        return $stmt->rowCount() > 0;
    }

    public function countByBusinessId(int $idBisnis)
    {
        $query = "SELECT COUNT(*) FROM $this->tableName WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        return $stmt->fetchColumn();
    }
}
