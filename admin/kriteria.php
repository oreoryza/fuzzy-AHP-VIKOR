<div class="page-header">
    <h1>Kriteria</h1>
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
<div class="panel panel-default" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
<div class="panel-heading">
    <form class="form-inline">
        <input type="hidden" name="m" value="kriteria" />
        <input type="hidden" name="periode" value="<?= _get('periode') ?>" />
        <div class="form-group">
            <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?= _get('q') ?>" />
        </div>
        <div class="form-group">
            <button class="btn btn-success" href="?m=kriteria&periode="><span class="glyphicon glyphicon-refresh"></span></button>
        </div>
        <div class="form-group" style="float:right">
            <a class="btn btn-primary" href="?m=kriteria_tambah&periode=<?= _get('periode') ?>"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
        </div>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Kriteria</th>
                <th>Atribut</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php
        $q = esc_field(_get('q'));
        $rows = $db->get_results("SELECT * FROM tb_kriteria WHERE nama_kriteria LIKE '%$q%' AND tanggal='$_GET[periode]' ORDER BY kode_kriteria");
        foreach ($rows as $row) : ?>
            <tr>
                <td><?= $row->kode_kriteria ?></td>
                <td><?= $row->nama_kriteria ?></td>
                <td><?= $row->atribut ?></td>
                <td>
                    <a class="btn btn-xs btn-warning" href="?m=kriteria_ubah&ID=<?= $row->kode_kriteria ?>&periode=<?= _get('periode') ?>"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn btn-xs btn-danger" href="aksi.php?act=kriteria_hapus&ID=<?= $row->kode_kriteria ?>&periode=<?= _get('periode') ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>
<div class="form-group">
<div style="float: right">
    <a class="btn btn-default" href="index.php?m=rel_kriteria&periode=<?= _get('periode')?>">Lanjut  <span class="glyphicon glyphicon-chevron-right"></span></a>
</div>
<div style="float: left">
    <a class="btn btn-default" href="index.php?m=alternatif&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-chevron-left"></span>  Kembali</a>
</div>
</div>