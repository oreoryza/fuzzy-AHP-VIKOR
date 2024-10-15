<?php
$ALTERNATIF = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif WHERE tanggal = '$_GET[periode]' ORDER BY kode_alternatif");
$KRITERIA = $db->get_results("SELECT kode_kriteria, nama_kriteria, atribut FROM tb_kriteria WHERE tanggal = '$_GET[periode]' ORDER BY kode_kriteria");
if ((array)$KRITERIA == null || (array)$ALTERNATIF == null){
    print_msg("Data altenatif atau kriteria kosong. Silahkan isi data terlebih dahulu pada halaman sebelumnya.");
}
?>

<div class="page-header">
    <h1>Nilai Bobot</h1>
<form class="form-inline" action="" method="get">
        <?php
        $periodes = $db->get_results("SELECT * FROM tb_periode ORDER BY tanggal");
        ?>
        <input type="hidden" name="m" value="<?= _get('m') ?>">
        <div class="form-group">
            <select class="form-control" name="periode">
                <?php foreach ($periodes as $periode) { ?>
                    <option value="<?= $periode->tanggal ?>" <?= $periode->tanggal == _get('periode') ? 'selected' : '' ?>><?= $periode->nama ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-list-alt"></span> Set </button>
        </div>
    </form>
</div>

<div class="page-header">
    <h1>Nilai Bobot Kriteria</h1>
</div>
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
foreach ($EXPERT as $key => $val){ ?>
    <label style="color: red; margin-top: 30px">Expert: <?= $val->nama_expert ?></label>
<div class="panel panel-default"[<?= $key ?>] style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <form class="form-inline" action="?m=rel_kriteria&periode=<?= _get('periode') ?>" method="post">
            <div class="form-group">
                <select class="form-control" name="ID1">
                    <?= AHP_get_kriteria_option(set_value('ID1')) ?>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" name="nilai">
                    <?= AHP_get_nilai_option(set_value('nilai')) ?>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" name="ID2">
                    <?= AHP_get_kriteria_option(set_value('ID2')) ?>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Ubah</button>
            </div>
        </form>
    </div>
    <?php
    $data = get_rel_kriteria($key);
    $baris_total = AHP_get_total_kolom($data);
    $normal = AHP_normalize($data, $baris_total);
    $rata = AHP_get_rata($normal);

    $cm = AHP_consistency_measure($data, $rata);
    $CI = ((array_sum($cm) / count($cm)) - count($cm)) / (count($cm) - 1);
    /*CI = (eigen max - n)/(n-1) */
    $RI = $nRI[count($data)];
    $CR = ($RI == 0) ? 0 : $CI / $RI;
    ?>

    <div class="table-responsive"[<?= $key ?>]>
        <?php
        if ($CR > 0.1){
            print_msg('Perbandingan yang anda inputkan tidak konsisten. Pastikan mengisi perbandingan dengan sesuai supaya maksimal nilai CR 0.1.');
        } else {
            print_msg("<b class='glyphicon glyphicon-ok-sign' style='font-size:15px; font-weight:700'>    Konsisten</b>", 'success');
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
</div>
<?php } ?>


<div class="page-header" style="margin-top:50px">
    <h1>Nilai Bobot Alternatif</h1>
</div>
<div class="panel panel-default" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <form class="form-inline">
            <input type="hidden" name="m" value="rel_kriteria" />
            <input type="hidden" name="periode" value="<?= _get('periode') ?>" />
            <div class="form-group">
                <input class="form-control" type="text" name="q" value="<?= _get('q') ?>" placeholder="Pencarian..." />
            </div>
            <div class="form-group">
                <a class="btn btn-success" href="?m=rel_kriteria&periode="><span class="glyphicon glyphicon-refresh"></span></a>
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
            $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE nama_alternatif LIKE '%$q%' AND tanggal='$PERIODE' ORDER BY kode_alternatif");
            $arr = get_rel_alternatif();
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= $row->kode_alternatif ?></td>
                    <td><?= $row->nama_alternatif ?></td>
                    <?php foreach ($arr[$row->kode_alternatif] as $k => $v) : ?>
                        <td><?= $v ?></td>
                    <?php endforeach ?>
                    <td>
                        <a class="btn btn-xs btn-warning" href="?m=rel_alternatif_ubah&ID=<?= $row->kode_alternatif ?>&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-edit"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<div class="form-group">
    <div style="float: right">
        <a class="btn btn-default" href="index.php?m=hitung&periode=<?= _get('periode')?>">Lanjut  <span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
    <div style="float: left">
        <a class="btn btn-default" href="index.php?m=kriteria&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-chevron-left"></span>  Kembali</a>
    </div>
</div>