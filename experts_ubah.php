<?php
$row = $db->get_row("SELECT * FROM tb_experts WHERE kode_expert='$_GET[ID]'");
?>
<div class="page-header">
    <h1>Ubah Expert</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode_expert" readonly="readonly" value="<?= $row->kode_expert ?>" />
            </div>
            <div class="form-group">
                <label>Nama expert <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama_expert" value="<?= $row->nama_expert ?>" />
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=experts&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>