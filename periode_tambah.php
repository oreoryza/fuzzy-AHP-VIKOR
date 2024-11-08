<div class="page-header">
    <h1>Tambah Periode</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                <input class="form-control" type="date" name="tanggal" id="tanggal" value="<?= set_value('tanggal') ?>" required />
            </div>
            <div class="form-group">
                <label for="nama">Nama Periode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" id="nama" value="<?= set_value('nama') ?>" required />
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" cols="3" class="form-control"><?= set_value('nama') ?></textarea>
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=periode&periode=<?= _get('periode') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>