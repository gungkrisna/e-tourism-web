<?
class Review
{
    private $conn;
    private $tableName = "ulasan";

    public $idUlasan;
    public $idBisnis;
    public $idPengguna;
    public $rating;
    public $judul;
    public $komentar;
    public $tanggal;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(Review $review)
    {
        $query = "INSERT INTO $this->tableName SET id_bisnis=:id_bisnis, id_pengguna=:id_pengguna, rating=:rating, judul=:judul, komentar=:komentar, status=:status";

        $stmt = $this->conn->prepare($query);

        $review->idBisnis = htmlspecialchars(strip_tags($review->idBisnis));
        $review->idPengguna = htmlspecialchars(strip_tags($review->idPengguna));
        $review->rating = htmlspecialchars(strip_tags($review->rating));
        $review->judul = htmlspecialchars(strip_tags($review->judul));
        $review->komentar = htmlspecialchars(strip_tags($review->komentar));
        $review->status = htmlspecialchars(strip_tags($review->status));

        $stmt->bindParam(':id_bisnis', $review->idBisnis);
        $stmt->bindParam(':id_pengguna', $review->idPengguna);
        $stmt->bindParam(':rating', $review->rating);
        $stmt->bindParam(':judul', $review->judul);
        $stmt->bindParam(':komentar', $review->komentar);
        $stmt->bindParam(':status', $review->status);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function read($idBisnis, $offset, $limit, $stars, $keyword, $sort)
    {
        $query = "SELECT * FROM $this->tableName WHERE id_bisnis = :id_bisnis AND status = 'publik'";

        if (isset($stars)) {
            $query .= " AND rating IN (" . $stars . ")";
        }

        if (isset($keyword)) {
            $query .= " AND (judul LIKE '%" . $keyword . "%' OR komentar LIKE '%" . $keyword . "%')";
        }

        if (isset($sort)) {
            if ($sort == 'Ulasan terbaru') {
                $query .= " ORDER BY waktu DESC";
            } elseif ($sort == 'Ulasan terlama') {
                $query .= " ORDER BY waktu ASC";
            } elseif ($sort == 'Rating tertinggi') {
                $query .= " ORDER BY rating DESC, waktu DESC";
            } elseif ($sort == 'Rating terendah') {
                $query .= " ORDER BY rating ASC, waktu DESC";
            }
        }

        if (!is_null($offset) && !is_null($offset)) {
            $query .= " LIMIT " . intval($offset) . ", " . intval($limit);
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_bisnis', $idBisnis);

        $stmt->execute();

        return $stmt->fetchAll();
    } 
    
    public function getReviewById($idUlasan)
    {
        $query = "SELECT * FROM ulasan WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idUlasan]);
        return $stmt->fetchAll();
    }


    public function update(Review $review)
    {
        $query = "UPDATE ulasan
                  SET id_pengguna = ?, id_bisnis = ?, rating = ?, judul = ?, komentar = ?, waktu = NOW(), status = ? 
                  WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $review->idPengguna,
            $review->idBisnis,
            $review->rating,
            $review->judul,
            $review->komentar,
            $review->status,
            $review->idUlasan
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete($idUlasan)
    {
        $query = "DELETE FROM ulasan WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idUlasan]);
        return $stmt->rowCount() > 0;
    }

    public function getTotalReviewsById($idBisnis)
    {
        $query = "SELECT
                    COUNT(id_ulasan)
                    AS TotalReviews
                FROM
                    $this->tableName
                WHERE
                    id_bisnis = :id_bisnis AND status = 'publik'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_bisnis', $idBisnis);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['TotalReviews'];
    }

    public function getTotalReviewsPerMonth($idBisnis)
    {
        $query = "SELECT
        MONTH(waktu) AS Month,
        YEAR(waktu) AS Year,
        COUNT(id_ulasan) AS TotalReviews
      FROM $this->tableName
      WHERE id_bisnis = :id_bisnis AND status = 'publik' AND waktu >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
      GROUP BY Month
      ORDER BY Month(waktu) DESC
      ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_bisnis', $idBisnis);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRatingById($idBisnis)
    {
        $query = "SELECT AVG(rating) as average_rating FROM $this->tableName WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idBisnis]);
        $row = $stmt->fetch();
        return $row['average_rating'];
    }

    public function createBusinessReply($idUlasan, $judul, $komentar)
    {
        $query = "INSERT INTO balasan_ulasan SET id_ulasan = ?, judul = ?, komentar = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idUlasan, $judul, $komentar]);
        return $stmt->rowCount() > 0;
    }

    public function readBusinessReply($idUlasan)
    {
        $query = "SELECT * FROM balasan_ulasan WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idUlasan]);
        return $stmt->fetch();
    }

    public function deleteBusinessReply($idUlasan)
    {
        $query = "DELETE FROM balasan_ulasan WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idUlasan]);
        return $stmt->rowCount() > 0;
    }
}
