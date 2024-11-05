<?php
// Ambil data alternatif dan kriteria
$ALTERNATIF = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif WHERE tanggal = '$_GET[periode]' ORDER BY kode_alternatif");
$KRITERIA = $db->get_results("SELECT kode_kriteria, nama_kriteria, atribut FROM tb_kriteria WHERE tanggal = '$_GET[periode]' ORDER BY kode_kriteria");

// Cek apakah data alternatif atau kriteria kosong
if ((array)$KRITERIA == null || (array)$ALTERNATIF == null) {
    print_msg("Data alternatif atau kriteria kosong. Silahkan isi data terlebih dahulu pada halaman sebelumnya.");
}

// Panggil fungsi untuk mendapatkan relasi alternatif
$arr = get_rel_alternatif();
?>

<h1>Nilai Bobot Alternatif</h1>
<form class="mt-4" action="" method="get">
    <?php
    $periodes = $db->get_results("SELECT * FROM tb_periode ORDER BY tanggal");
    ?>
    <input type="hidden" name="m" value="<?= _get('m') ?>">
    <div class="input-group">
        <select class="form-control" name="periode">
            <?php foreach ($periodes as $periode) { ?>
                <option value="<?= $periode->tanggal ?>" <?= $periode->tanggal == _get('periode') ? 'selected' : '' ?>><?= $periode->nama ?></option>
            <?php } ?>
        </select>
        <div class="input-group-text">
            <button class="btn btn-primary" type="submit">Set File</button>
        </div>
    </div>
</form>
<div class="mt-2">
    <div class="py-3">
        <form class="form-inline">
            <input type="hidden" name="m" value="rel_kriteria" />
            <input type="hidden" name="periode" value="<?= _get('periode') ?>" />
            <div class="form-group">
                <input class="form-control" type="text" name="q" value="<?= _get('q') ?>" placeholder="Search" />
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Alternatif</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $val->nama_kriteria ?></th>
                    <?php endforeach ?>
                    <th>Aksi</th>
                </tr>
            </thead>

            <?php
            $q = esc_field(_get('q'));
            $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE nama_alternatif LIKE '%$q%' AND tanggal='$_GET[periode]' ORDER BY kode_alternatif");
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= $row->kode_alternatif ?></td>
                    <td><?= $row->nama_alternatif ?></td>
                    <?php foreach ($arr[$row->kode_alternatif] as $k => $v) : ?>
                        <td><?= $v ?></td>
                    <?php endforeach ?>
                    <td>
                        <a class="btn btn-xs btn-warning" href="?m=rel_alternatif_ubah&ID=<?= $row->kode_alternatif ?>&periode=<?= _get('periode') ?>"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<div class="d-flex justify-content-end mt-3">
    <div style="d-flex">
        <a class="btn btn-default" href="index.php?m=rel_kriteria&periode=<?= _get('periode')?>"><i class="bi bi-chevron-left"></i></a>
        <a class="btn btn-default" href="index.php?m=hitung&periode=<?= _get('periode')?>"><i class="bi bi-chevron-right"></i></a>
    </div>
</div>