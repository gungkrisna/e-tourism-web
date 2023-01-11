<?php
class Pengguna
{
    private $conn;
    private $tableName = "pengguna";

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function read(int $id_pengguna)
    {
        $query = "SELECT * FROM $this->tableName WHERE id_pengguna = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_pengguna]);
        return $stmt->fetch();
    }

    public function update(int $id_pengguna, string $nama = null, string $username = null, string $email = null, string $password = null, string $avatar = null, string $tanggal_lahir = null, string $alamat = null, int $id_desa = null, string $level = null)
    {
        $query = "UPDATE $this->tableName SET";
        $data = array();
        if(!is_null($nama)){
            $query .= " nama = ?,";
            array_push($data, $nama);
        }
        if(!is_null($username)){
            $query .= " username = ?,";
            array_push($data, $username);
        }
        if(!is_null($email)){
            $query .= " email = ?,";
            array_push($data, $email);
        }
        if(!is_null($password)){
            $query .= " password = ?,";
            array_push($data, $password);
        }
        if(!is_null($avatar)){
            $query .= " avatar = ?,";
            array_push($data, $avatar);
        }
        if(!is_null($tanggal_lahir)){
            $query .= " tanggal_lahir = ?,";
            array_push($data, $tanggal_lahir);
        }
        if(!is_null($alamat)){
            $query .= " alamat = ?,";
            array_push($data, $alamat);
        }
        if(!is_null($id_desa)){
            $query .= " id_desa = ?,";
            array_push($data, $id_desa);
        }
        if(!is_null($level)){
            $query .= " level = ?,";
            array_push($data, $level);
        }
        $query = substr($query, 0, -1); //remove last comma
        $query .= " WHERE id_pengguna = ?";
        array_push($data, $id_pengguna);
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        return $stmt->rowCount() > 0;
    }


    public function delete(int $id_pengguna)
    {
        $query = "DELETE FROM $this->tableName WHERE id_pengguna = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_pengguna]);
        return $stmt->rowCount() > 0;
    }

    public function count()
    {
        $query = "SELECT COUNT(*) FROM $this->tableName";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getTotalUsersPerMonth()
    {
            $query = "SELECT
        MONTH(tanggal_daftar) AS Month,
        YEAR(tanggal_daftar) AS Year,
        COUNT(id_pengguna) AS TotalUsers
        FROM $this->tableName
        WHERE tanggal_daftar >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY Month
        ORDER BY Month(tanggal_daftar) DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
