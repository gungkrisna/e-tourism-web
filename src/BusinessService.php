<?
class BusinessService
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }


    public function createBusiness(Business $business)
    {
        $query = "INSERT INTO bisnis (id_pengguna, nama, deskripsi, telepon, email, website, alamat, id_desa, lat, lng, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $business->getIdPengguna(),
            $business->getNama(),
            $business->getDeskripsi(),
            $business->getTelepon(),
            $business->getEmail(),
            $business->getWebsite(),
            $business->getAlamat(),
            $business->getIdDesa(),
            $business->getLat(),
            $business->getLng(),
            $business->getStatus(),
        ]);
        return $this->conn->lastInsertId();
    }

    public function updateBusiness(Business $business)
    {
        $query = "UPDATE bisnis SET nama = ?, deskripsi = ?, telepon = ?, email = ?, website = ?, alamat = ?, id_desa = ?, lat = ?, lng = ? WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $business->getNama(),
            $business->getDeskripsi(),
            $business->getTelepon(),
            $business->getEmail(),
            $business->getWebsite(),
            $business->getAlamat(),
            $business->getIdDesa(),
            $business->getLat(),
            $business->getLng(),
            $business->getIdBisnis()
        ]);
        return $stmt->rowCount() > 0;
    }

    public function deleteBusiness($idBisnis)
    {
        $query = "DELETE FROM bisnis WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        return $stmt->rowCount() > 0;
    }

    public function getBusinessById($idBisnis)
    {
        $query = "SELECT * FROM bisnis WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return new Business(
            $row['id_bisnis'],
            $row['id_pengguna'],
            $row['nama'],
            $row['deskripsi'],
            $row['telepon'],
            $row['email'],
            $row['website'],
            $row['alamat'],
            $row['id_desa'],
            $row['lat'],
            $row['lng'],
            $row['status']
        );
    }

    public function getAllBusinessCategory() {
        $query = "SELECT * FROM kategori";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCategoryByBusinessId($idBisnis)
    {
        $query = "SELECT k.* FROM kategori k
        INNER JOIN kategori_bisnis kb ON k.id_kategori = kb.id_kategori
        WHERE kb.id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        return $stmt->fetch();
    }

    public function setCategoryByBusinessId($idKategori, $idBisnis)
    {
        $query = "INSERT INTO kategori_bisnis (id_kategori, id_bisnis) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idKategori);
        $stmt->bindParam(2, $idBisnis);
        $stmt->execute();
    }

    public function getMostPopularBusinesses()
    {
        $query = "SELECT b.id_bisnis, b.nama, AVG(r.rating) as avg_rating, COUNT(r.id_ulasan) as total_reviews, wd.id_kecamatan, wk.id_kabupaten, b.alamat
                    FROM bisnis b
                    LEFT JOIN ulasan r ON r.id_bisnis = b.id_bisnis
                    JOIN wilayah_desa wd ON wd.id_desa = b.id_desa
                    JOIN wilayah_kecamatan wk ON wk.id_kecamatan = wd.id_kecamatan
                    GROUP BY b.id_bisnis
                    HAVING avg_rating >= 3
                    ORDER BY total_reviews DESC
        ";
        $result = $this->conn->query($query);

        return $result->fetchAll();
    }
}
