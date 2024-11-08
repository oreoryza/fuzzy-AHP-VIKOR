<h1>Tambah Alternatif</h1>
<small>Periode <?=_get('periode') ?></small>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode_alternatif" value="<?= set_value('kode_alternatif') ?>" />
            </div>
            <div class="form-group">
                <label>Nama Alternatif <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama_alternatif" value="<?= set_value('nama_alternatif') ?>" />
            </div>
            <div class="form-group">
                <label>Kategori <span class="text-danger">*</span></label>
                <select class="form-control" name="kategori">
                    <option value=""></option>
                    <?= get_ket_option(set_value('kategori')) ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=alternatif&periode=<?= _get('periode') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>