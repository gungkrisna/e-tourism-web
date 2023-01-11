<?
class FAQ
{
    private $conn;
    private $table_name = 'faq_bisnis';

    public $id_faq_bisnis;
    public $id_bisnis;
    public $pertanyaan;
    public $jawaban;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(FAQ $faq)
    {
        $query = "INSERT INTO $this->table_name (id_bisnis, pertanyaan, jawaban) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $faq->id_bisnis);
        $stmt->bindParam(2, $faq->pertanyaan);
        $stmt->bindParam(3, $faq->jawaban);

        return $stmt->execute();
    }

    public function read($idBisnis)
    {
        $query = "SELECT * FROM $this->table_name WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idBisnis);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function delete($idFAQBisnis)
    {
        $query = "DELETE FROM $this->table_name WHERE id_faq_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idFAQBisnis);

        return $stmt->execute();
    }

    public function deleteFAQByBusinessId($idBisnis)
    {
        $query = "DELETE FROM $this->table_name WHERE id_bisnis = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idBisnis);

        return $stmt->execute();
    }
}
