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
        $query = "UPDATE bisnis SET nama = ?, deskripsi = ?, telepon = ?, email = ?, website = ?, alamat = ?, id_desa = ?, lat = ?, lng = ?, status = ? WHERE id_bisnis = ?";
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
            $business->getStatus(),
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

    public function rejectBusinessListing($idBisnis, $alasan)
    {
        $query = "INSERT INTO penolakan_bisnis (id_bisnis, alasan) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis, $alasan]);
        return $stmt->rowCount() > 0;
    }
    
    public function removeRejectedBusinessListing($idBisnis)
    {
        $query = "DELETE FROM penolakan_bisnis WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        return $stmt->rowCount() > 0;
    }

    public function readRejectedBusinessListing($idBisnis)
    {
        $query = "SELECT * FROM penolakan_bisnis WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        return $stmt->fetchAll();
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

    public function getBusinessByUserId($idPengguna)
    {
        $query = "SELECT * FROM bisnis WHERE id_pengguna = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idPengguna]);
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

    public function getAllBusinessCategory()
    {
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

    public function updateCategoryByBusinessId($idKategori, $idBisnis)
    {
        $query = "UPDATE kategori_bisnis SET id_kategori = ? WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idKategori);
        $stmt->bindParam(2, $idBisnis);
        $stmt->execute();
    }

    public function deleteBusinessFromCategoryId($idBisnis)
    {
        $query = "DELETE FROM kategori_bisnis WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idBisnis);
        $stmt->execute();
    }

    public function getMostPopularBusinesses($id_provinsi = null)
    {
        $query = "SELECT b.id_bisnis, b.nama, b.status, AVG(r.rating) as avg_rating, COUNT(r.id_ulasan) as total_reviews, wd.id_kecamatan, wk.id_kabupaten, wp.id_provinsi, b.alamat
                    FROM bisnis b
                    LEFT JOIN ulasan r ON r.id_bisnis = b.id_bisnis
                    JOIN wilayah_desa wd ON wd.id_desa = b.id_desa
                    JOIN wilayah_kecamatan wk ON wk.id_kecamatan = wd.id_kecamatan
                    JOIN wilayah_kabupaten wb ON wb.id_kabupaten = wk.id_kabupaten
                    JOIN wilayah_provinsi wp ON wp.id_provinsi = wb.id_provinsi";
        if ($id_provinsi !== null) {
            $query .= " WHERE wp.id_provinsi = $id_provinsi";
        }
        $query .= " GROUP BY b.id_bisnis
                    HAVING avg_rating >= 3
                    ORDER BY total_reviews DESC";
    
        $result = $this->conn->query($query);
    
        return $result->fetchAll();
    }    

    public function countBusinessesByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM bisnis GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[$row['status']] = $row['count'];
        }
        return $results;
    }
    
    
}
