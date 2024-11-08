<h1>Tambah expert</h1>
<small>Periode <?=_get('periode') ?></small>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode_expert" value="<?= set_value('kode_expert') ?>" />
            </div>
            <div class="form-group">
                <label>Nama expert <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama_expert" value="<?= set_value('nama_expert') ?>" />
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=experts&periode=<?= _get('periode') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>