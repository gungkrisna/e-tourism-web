<?
class Business {
    public $idBisnis;
    public $idPengguna;
    public $nama;
    public $deskripsi;
    public $telepon;
    public $email;
    public $website;
    public $alamat;
    public $idDesa;
    public $lat;
    public $lng;
    public $status;

    public function __construct($idBisnis, $idPengguna, $nama, $deskripsi, $telepon, $email, $website, $alamat, $idDesa, $lat, $lng, $status)
    {
        $this->idBisnis = $idBisnis;
        $this->idPengguna = $idPengguna;
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
        $this->telepon = $telepon;
        $this->email = $email;
        $this->website = $website;
        $this->alamat = $alamat;
        $this->idDesa = $idDesa;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->status = $status;
    }

    public function getIdBisnis()
    {
        return $this->idBisnis;
    }

    public function getIdPengguna()
    {
        return $this->idPengguna;
    }

    public function getNama()
    {
        return $this->nama;
    }
    public function getDeskripsi()
    {
        return $this->deskripsi;
    }

    public function getTelepon()
    {
        return $this->telepon;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function getAlamat()
    {
        return $this->alamat;
    }

    public function getIdDesa()
    {
        return $this->idDesa;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function getStatus()
    {
        return $this->status;
    }
}

