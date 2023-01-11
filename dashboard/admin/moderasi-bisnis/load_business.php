<?php
include '../../../src/conn.php';
include '../../../src/Business.php';
include '../../../src/BusinessService.php';
include '../../../src/BusinessSearch.php';
include '../../../src/Pengguna.php';
include '../../../src/Place.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $pengguna = new Pengguna($conn);
    $user = $pengguna->read($_SESSION['user_id']);
} else {
    header('Location: ../../../login');
}

if ($user['level'] != 'admin') {
    header('Location: ../../');
}

$status = $_POST['status'] ?? 'pending';

$search = new BusinessSearch($conn);
$business_service = new BusinessService($conn);
$pengguna = new Pengguna($conn);
$place = new Place($conn);

$params = [];
$params['status'] = $status;
$result = $search->search($params);
?>


<table id="mainBusinessDatatable" class="display" style="width:100%;">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Nama Bisnis</th>
            <th>Pemilik</th>
            <th>Email</th>
            <th>Desa</th>
            <th>Kecamatan</th>
            <th>Kabupaten</th>
            <th>Provinsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($result as $business) : ?>
            <tr>
                <td><?= $business_service->getCategoryByBusinessId($business['id_bisnis'])['nama'] ?></td>
                <td><?= $business['nama'] ?></td>
                <td><?= $pengguna->read($business_service->getBusinessById($business['id_bisnis'])->idPengguna)['nama'] ?></td>
                <td><?= $business_service->getBusinessById($business['id_bisnis'])->email ?></td>
                <td><?= $business['desa'] ?></td>
                <td><?= $business['kecamatan'] ?></td>
                <td><?= $business['kabupaten'] ?></td>
                <td><?= $business['provinsi'] ?></td>
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item aksi-preview" data-status="<?= $status ?>" id="<?= $business['id_bisnis'] ?>" data-bs-toggle="modal" data-bs-target="#previewBusiness">Preview bisnis</a></li>
                            <li><a class="dropdown-item aksi-delete" id="<?= $business['id_bisnis'] ?>" href="#">Hapus bisnis</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>

<script>
    $('#mainBusinessDatatable').DataTable({
        "scrollCollapse": true,
    });
</script>