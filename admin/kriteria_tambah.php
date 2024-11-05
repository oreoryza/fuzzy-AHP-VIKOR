<div>
    <h1>Tambah Kriteria</h1>
    <small>Periode <?= _get('periode') ?></small>
</div>
<?php 
$auto = $db->query("SELECT max(kode_kriteria) as kode FROM tb_kriteria WHERE tanggal='$PERIODE'");
$dat = mysqli_fetch_array($auto);
$code = $dat['kode'];
$urutan = (int)substr($code, 1, 3);
$urutan++;
$huruf = "C";
$kodkrit = $huruf . sprintf("%01s", $urutan);
?>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode_kriteria" value="<?= set_value('kode_kriteria', $kodkrit) ?>" />
            </div>
            <div class="form-group">
                <label>Nama Kriteria <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama_kriteria" value="<?= set_value('nama_kriteria') ?>" />
            </div>
            <div class="form-group">
                <label>Atribut <span class="text-danger">*</span></label>
                <select class="form-control" name="atribut">
                    <option value=""></option>
                    <?= get_atribut_option(set_value('atribut')) ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary" type="submit" value="submit"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=kriteria&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>