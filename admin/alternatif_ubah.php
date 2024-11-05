<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]'");
?>
<div class="page-header">
    <h1>Ubah Alternatif</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode_alternatif" readonly="readonly" value="<?= $row->kode_alternatif ?>" />
            </div>
            <div class="form-group">
                <label>Nama Alternatif <span class="text-danger">*</span></label>
                <textarea class="form-control" type="text" name="nama_alternatif" value="<?= $row->nama_alternatif ?>"></textarea>
            </div>
            <div class="form-group">
                <label>Kategori <span class="text-danger">*</span></label>
                <select class="form-control" name="kategori">
                    <option value=""></option>
                    <?= get_ket_option(set_value('kategori', $row->kategori)) ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=alternatif&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>