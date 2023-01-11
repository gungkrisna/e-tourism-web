<?
class Article
{
    private $conn;
    private $tableName = "artikel";

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create(int $id_pengguna, string $judul, string $subjudul, string $banner, string $konten, string $status)
    {
        $query = "INSERT INTO $this->tableName (id_pengguna, judul, subjudul, banner, konten, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_pengguna, $judul, $subjudul, $banner, $konten, $status]);
        return $this->conn->lastInsertId();
    }

    public function read(int $id_artikel, string $status = null)
    {
        $query = "SELECT * FROM $this->tableName WHERE id_artikel = ?";
        if ($status != null) {
            $query .= " AND status = ?";
        }
        $stmt = $this->conn->prepare($query);
        if ($status != null) {
            $stmt->execute([$id_artikel, $status]);
        } else {
            $stmt->execute([$id_artikel]);
        }
        return $stmt->fetchAll();
    }

    public function readAll($offset, $limit, $keyword, $status, $sortBy = 'id_artikel', $sortOrder = 'DESC')
    {
        $query = "SELECT * FROM $this->tableName WHERE status = ?";

        // If the keyword parameter is not empty, add a WHERE clause to the query to search for the keyword in the judul and komentar fields
        if (isset($keyword)) {
            $query .= " AND (judul LIKE '%" . $keyword . "%' OR subjudul LIKE '%" . $keyword . "%' OR konten LIKE '%" . $keyword . "%')";
        }

        // Add a sorting clause to the query to sort the results by the specified field and order
        $query .= " ORDER BY $sortBy $sortOrder";

        // Add a LIMIT clause to the query to specify the offset and limit
        if (!is_null($offset) && !is_null($offset)) {
            $query .= " LIMIT " . intval($offset) . ", " . intval($limit);
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }


    public function update(int $id_artikel, int $id_pengguna, string $judul, string $subjudul, string $banner, string $konten, string $status)
    {
        $query = "UPDATE $this->tableName SET id_pengguna = ?, judul = ?, subjudul = ?, banner = ?, konten = ?, status = ? WHERE id_artikel = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_pengguna, $judul, $subjudul, $banner, $konten, $status, $id_artikel]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id_artikel)
    {
        $query = "DELETE FROM $this->tableName WHERE id_artikel = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_artikel]);
        return $stmt->rowCount() > 0;
    }

    public function count($status = null)
    {
        $query = "SELECT COUNT(*) FROM $this->tableName";
        if ($status != null) {
            $query .= " WHERE status = ?";
        }
        $stmt = $this->conn->prepare($query);
        if ($status != null) {
            $stmt->execute([$status]);
        } else {
            $stmt->execute();
        }
        return (int) $stmt->fetchColumn();
    }


    public function getTotalArticlePerMonth()
    {
        $query = "SELECT
        MONTH(tanggal) AS Month,
        YEAR(tanggal) AS Year,
        COUNT(id_artikel) AS TotalArtikel
        FROM $this->tableName
        WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY Month
        ORDER BY Month(tanggal) DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReadTimeInMinutes($word, $image)
    {
        $wpm = 265;
        $readtime = $word / $wpm;
        $readtime += floor((($image * 12) / 60) % 60);
        return floor($readtime);
    }
}
