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

    public function isWishlist($idPengguna, $idBisnis){
        $query = "SELECT * FROM $this->tableName WHERE id_pengguna = ? AND id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idPengguna, $idBisnis]);
        return $stmt->fetchAll();
    }

    public function delete($idPengguna, $idBisnis)
    {
        $query = "DELETE FROM $this->tableName WHERE id_pengguna = ? AND id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idPengguna, $idBisnis]);
        return $stmt->rowCount() > 0;
    }

    public function deleteWishlistByBusinessId(int $idBisnis)
    {
        $query = "DELETE FROM $this->tableName WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        return $stmt->rowCount() > 0;
    }

    public function countByBusinessId(int $idBisnis)
    {
        $query = "SELECT COUNT(*) FROM $this->tableName WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        return $stmt->fetchColumn();
    }

    public function getTotalWishlistsPerMonth(int $idBisnis)
    {
        $query = "SELECT
        MONTH(added_at) AS Month,
        YEAR(added_at) AS Year,
        COUNT(added_at) AS TotalWishlists
      FROM $this->tableName
      WHERE id_bisnis = :id_bisnis AND added_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
      GROUP BY Month
      ORDER BY Month(added_at) DESC
      ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_bisnis', $idBisnis);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
