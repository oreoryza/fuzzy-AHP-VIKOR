<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]' AND tanggal='$PERIODE'");
?>
<div class="page-header">
    <h1>Ubah Nilai Bobot &raquo; <small><?= $row->nama_alternatif ?></small></h1>
</div>
<div class="row">
    <div class="col-sm-4">
    <?php if ($_POST) include 'aksi.php';
    foreach ($EXPERT as $key => $val){ ?>
    <label style="color: red; margin-top: 30px">Expert: <?= $val->nama_expert ?></label>
        <form method="post">
            <?php
            $rows = $db->get_results("SELECT ra.ID, k.kode_kriteria, k.nama_kriteria, ra.nilai FROM tb_rel_alternatif ra INNER JOIN tb_kriteria k ON k.kode_kriteria=ra.kode_kriteria AND k.tanggal=ra.tanggal WHERE k.tanggal='$_GET[periode]' AND kode_alternatif='$_GET[ID]' ORDER BY kode_kriteria");
            foreach ($rows as $row) : ?>
                <div class="form-group">
                    <label><?= $row->nama_kriteria ?></label>
                    <input class="form-control" type="text" name="nilai[<?= $row->ID ?>]" value="<?= $row->nilai ?>" />
                </div>
            <?php endforeach ?>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=rel_kriteria&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
        <?php } ?>
    </div>
</div>