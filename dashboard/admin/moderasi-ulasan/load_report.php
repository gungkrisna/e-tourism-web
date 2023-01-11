<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/Report.php';
include '../../../src/Review.php';

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

$reports = new Report($conn);
$reviews = new Review($conn);
?>


<table id="mainReportDatatable" class="display" style="width:100%;">
    <thead>
        <tr>
            <th>Penulis ulasan</th>
            <th>Ulasan</th>
            <th>Jumlah laporan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="mainReportBody">
        <? foreach ($reports->readDistinct() as $r) : ?>
            <tr>
                <td><?= $pengguna->read($reviews->getReviewById($r['id_ulasan'])[0]['id_pengguna'])['nama'] ?></td>
                <td><?= $reviews->getReviewById($r['id_ulasan'])[0]['komentar'] ?></td>
                <td><?= count($reports->getReportByIdUlasan($r['id_ulasan'])) ?></td>
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item aksi-detail" id="<?= $r['id_ulasan'] ?>" data-bs-toggle="modal" data-bs-target="#allReport">Lihat detail laporan</a></li>
                            <li><a class="dropdown-item aksi-delete" id="<?= $r['id_ulasan'] ?>" href="#">Hapus ulasan</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>

<script>
    $('#mainReportDatatable').DataTable({
        "scrollCollapse": true,
    });
</script>