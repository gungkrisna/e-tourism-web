<?php

class BusinessSearch
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function search($params)
    {
        // Build the SQL query
        $sql = "SELECT b.id_bisnis, b.nama, AVG(u.rating) AS avg_rating, COUNT(u.id_ulasan) AS total_reviews, kec.nama AS kecamatan, kab.nama AS kabupaten, b.alamat FROM bisnis b
                LEFT JOIN ulasan u ON b.id_bisnis = u.id_bisnis
                LEFT JOIN wilayah_desa d ON b.id_desa = d.id_desa
                LEFT JOIN wilayah_kecamatan kec ON d.id_kecamatan = kec.id_kecamatan
                LEFT JOIN wilayah_kabupaten kab ON kec.id_kabupaten = kab.id_kabupaten
                WHERE b.status = 'disetujui'";

        // Initialize the array of WHERE clause conditions
        $filters = [];

        // Add the WHERE clause conditions for each set parameter
        if (isset($params['query'])) {
            $filters[] = "(b.nama LIKE :query OR b.deskripsi LIKE :query OR b.alamat LIKE :query)";
        }
        if (isset($params['rating'])) {
            $filters[] = "(SELECT FLOOR(AVG(rating)) FROM ulasan WHERE id_bisnis = b.id_bisnis) IN (" . $params['rating'] . ")";
        }
        if (isset($params['kategori'])) {
            $filters[] = "b.id_bisnis IN (SELECT id_bisnis FROM kategori_bisnis WHERE id_kategori IN (" . $params['kategori'] . "))";
        }
        if (isset($params['desa'])) {
            $filters[] = "b.id_desa IN (" . $params['desa'] . ")";
        }
        if (isset($params['kecamatan'])) {
            $filters[] = "kec.id_kecamatan IN (" . $params['kecamatan'] . ")";
        }
        if (isset($params['kabupaten'])) {
            $filters[] = "kab.id_kabupaten IN (" . $params['kabupaten'] . ")";
        }
        if (isset($params['provinsi'])) {
            $filters[] = "kab.id_provinsi IN (" . $params['provinsi'] . ")";
        }

        // Add the WHERE clause conditions to the query
        if (!empty($filters)) {
            $sql .= " AND " . implode(" AND ", $filters);
        }

        $sql .= " GROUP BY b.id_bisnis";
        if (isset($params['sort']) && $params['sort'] == 'latest') {
            $sql .= " ORDER BY b.id_bisnis DESC";
        } elseif (isset($params['sort']) && $params['sort'] == 'most_reviewed') {
            $sql .= " ORDER BY total_reviews DESC";
        }

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);

        // Bind the parameters
        if ($params['query']) {
            $stmt->bindValue(':query', "%{$params['query']}%");
        }

        // Execute the query and fetch the results
        $stmt->execute();
        $results = $stmt->fetchAll();

        // Return the results
        return $results;
    }
}
