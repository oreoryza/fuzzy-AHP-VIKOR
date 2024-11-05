<?php
$ALTERNATIF = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif WHERE tanggal = '$_GET[periode]' ORDER BY kode_alternatif");
$KRITERIA = $db->get_results("SELECT kode_kriteria, nama_kriteria, atribut FROM tb_kriteria WHERE tanggal = '$_GET[periode]' ORDER BY kode_kriteria");
if ((array)$KRITERIA == null || (array)$ALTERNATIF == null){
    print_msg("Data alternatif atau kriteria kosong. Silahkan isi data terlebih dahulu pada halaman sebelumnya.");
}
?>

<h1>Nilai Bobot</h1>
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

<?php
if ($_POST) include 'aksi.php';
$rows = $db->get_results("SELECT k.nama_kriteria, rk.ID1, rk.ID2, nilai 
    FROM tb_rel_kriteria rk INNER JOIN tb_kriteria k ON k.kode_kriteria=rk.ID1 
    where rk.tanggal = '$_GET[periode]'
    ORDER BY ID1, ID2");
$criterias = array();
$data = array();
foreach ($rows as $row) {
    $criterias[$row->ID1] = $row->nama_kriteria;
    $data[$row->ID1][$row->ID2] = $row->nilai;
}
?>

<?php
$experts = $db->get_results("SELECT * FROM tb_experts WHERE tanggal='$_GET[periode]'");
foreach($experts as $expert) : ?>
    <div class="panel panel-default border border-subtle mt-4 p-3 rounded-3">
        <div class="panel-heading">
            <h4 class="panel-title my-3">Expert: <?=$expert->nama_expert?></h4>
        </div>
        <div class="panel-body">
            <form class="input-group" action="?m=rel_kriteria&periode=<?= _get('periode') ?>" method="post">
                <input type="hidden" name="kode_expert" value="<?=$expert->kode_expert?>">
                <input type="hidden" name="tanggal" value="<?= _get('periode') ?>">
            <div class="p-2">
                <select class="form-control" name="ID1">
                    <?= AHP_get_kriteria_option(set_value('ID1')) ?>
                </select>
            </div>
            <div class="p-2">
                <select class="form-control" name="nilai">
                    <?= AHP_get_nilai_option(set_value('nilai')) ?>
                </select>
            </div>
            <div class="p-2">
                <select class="form-control" name="ID2">
                    <?= AHP_get_kriteria_option(set_value('ID2')) ?>
                </select>
            </div>
            <div class="p-2">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Ubah</button>
            </div>
        </form>
    </div>
    <?php
    $data = get_rel_kriteria($expert->kode_expert);
    
    if (!empty($data) && count($data) > 1) { // Pastikan ada minimal 2 kriteria
        $baris_total = AHP_get_total_kolom($data);
        $normal = AHP_normalize($data, $baris_total);
        $rata = AHP_get_rata($normal);

        $cm = AHP_consistency_measure($data, $rata);
        
        // Hitung CI hanya jika ada lebih dari 1 kriteria
        if (count($cm) > 1) {
            $CI = ((array_sum($cm) / count($cm)) - count($cm)) / (count($cm) - 1);
            $RI = isset($nRI[count($data)]) ? $nRI[count($data)] : 0;
            $CR = ($RI == 0) ? 0 : $CI / $RI;
        } else {
            $CI = 0;
            $CR = 0;
        }
        ?>

        <div class="table-responsive">
            <?php
            if (count($data) > 1) { // Tampilkan pesan konsistensi hanya jika ada lebih dari 1 kriteria
                if ($CR > 0.1) {
                    print_msg('Perbandingan yang anda inputkan tidak konsisten. Pastikan mengisi perbandingan dengan sesuai supaya maksimal nilai CR 0.1.');
                } else {
                    print_msg("Konsisten", 'success');
                }
            }
            ?>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr class="text-primary">
                        <th>Kode</th>
                        <?php foreach ($data as $key => $val) : ?>
                            <th><?= $key ?></th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <?php foreach ($data as $key => $val) : ?>
                    <tr>
                        <th class="text-primary"><?= $key ?></th>
                        <?php foreach ($val as $k => $v) : ?>
                            <td><?= round($v, 3) ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            <?php 
            if (empty($data)) {
                echo "Belum ada data perbandingan untuk expert ini.";
            } else {
                echo "Minimal dibutuhkan 2 kriteria untuk melakukan perbandingan.";
            }
            ?>
        </div>
    <?php } ?>
    </div>
<?php endforeach; ?>

<div class="d-flex justify-content-end mt-3">
    <div style="d-flex">
        <a class="btn btn-default" href="index.php?m=experts&periode=<?= _get('periode')?>"><i class="bi bi-chevron-left"></i></a>
        <a class="btn btn-default" href="index.php?m=rel_alternatif&periode=<?= _get('periode')?>"><i class="bi bi-chevron-right"></i></a>
    </div>
</div>