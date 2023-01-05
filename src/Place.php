<?
class Place
{
    private $conn;
    private $tbDesa = "wilayah_desa";
    private $tbKecamatan = "wilayah_kecamatan";
    private $tbKabupaten = "wilayah_kabupaten";
    private $tbProvinsi = "wilayah_provinsi";

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getDesa()
    {
        $query = "SELECT * FROM $this->tbDesa";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getKecamatan()
    {
        $query = "SELECT * FROM $this->tbKecamatan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getKabupaten()
    {
        $query = "SELECT * FROM $this->tbKabupaten";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProvinsi()
    {
        $query = "SELECT * FROM $this->tbProvinsi";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getAllPlaces()
    {
        $query = "SELECT kabupaten.id_kabupaten AS id, 'kabupaten' AS type_of_place, kabupaten.nama AS nama
        FROM wilayah_kabupaten AS kabupaten
        
        UNION ALL
        
        SELECT provinsi.id_provinsi AS id, 'provinsi' AS type_of_place, provinsi.nama AS nama
        FROM wilayah_provinsi AS provinsi
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPlaceById($idDesa)
    {
        $query = "SELECT d.id_kecamatan, k.id_kabupaten, p.id_provinsi FROM $this->tbDesa d
                  LEFT JOIN $this->tbKecamatan k ON d.id_kecamatan = k.id_kecamatan
                  LEFT JOIN $this->tbKabupaten b ON k.id_kabupaten = b.id_kabupaten
                  LEFT JOIN $this->tbProvinsi p ON b.id_provinsi = p.id_provinsi
                  WHERE d.id_desa = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idDesa]);
        return $stmt->fetch();
    }

    public function getDesaNameById($idDesa)
    {
        $query = "SELECT nama FROM $this->tbDesa WHERE id_desa = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idDesa]);
        return $stmt->fetchColumn();
    }

    public function getKecamatanNameById($idKecamatan)
    {
        $query = "SELECT nama FROM $this->tbKecamatan WHERE id_kecamatan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idKecamatan]);
        return $stmt->fetchColumn();
    }

    public function getKabupatenNameById($idKabupaten)
    {
        $query = "SELECT nama FROM $this->tbKabupaten WHERE id_kabupaten = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idKabupaten]);
        return $stmt->fetchColumn();
    }

    public function getProvinsiNameById($idProvinsi)
    {
        $query = "SELECT nama FROM $this->tbProvinsi WHERE id_provinsi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idProvinsi]);
        return $stmt->fetchColumn();
    }

    public function getDesaByKecamatan($idKecamatan)
    {
        $query = "SELECT * FROM $this->tbDesa WHERE id_kecamatan = ? ORDER BY nama ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idKecamatan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKecamatanByKabupaten($idKabupaten)
    {
        $query = "SELECT * FROM $this->tbKecamatan WHERE id_kabupaten = ? ORDER BY nama ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idKabupaten]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKabupatenByProvinsi($idProvinsi)
    {
        $query = "SELECT * FROM $this->tbKabupaten WHERE id_provinsi = ? ORDER BY nama ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idProvinsi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNearestBusinessesByLocation($locationId, $locationType)
    {
        $query = "SELECT b.id_bisnis, b.id_desa, wd.id_kecamatan, wk.id_kabupaten, wp.id_provinsi
                    FROM bisnis b
                    JOIN $this->tbDesa wd ON wd.id_desa = b.id_desa
                    JOIN $this->tbKecamatan wk ON wk.id_kecamatan = wd.id_kecamatan
                    JOIN $this->tbKabupaten wp ON wp.id_kabupaten = wk.id_kabupaten
                    WHERE
                    CASE
                        WHEN 'desa' = :locationType THEN wd.id_desa = :locationId
                        WHEN 'kecamatan' = :locationType THEN wk.id_kecamatan = :locationId
                        WHEN 'kabupaten' = :locationType THEN wk.id_kabupaten = :locationId
                        WHEN 'provinsi' = :locationType THEN wp.id_provinsi = :locationId
                    END";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':locationId' => $locationId,
            ':locationType' => $locationType
        ]);
        return $stmt->fetchAll();
    }
}
