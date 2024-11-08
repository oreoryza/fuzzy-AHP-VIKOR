<h1>Kriteria</h1>
<form class="mt-4" action="" method="get">
        <?php
        $_GET['periode'] ? '' : print_msg("Pastikan untuk memilih file yang diinginkan terlebih dahulu dengan benar", "info");
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
<div class="mt-2">
<div class="py-3">
    <form class="input-group d-flex justify-content-between">
        <input type="hidden" name="m" value="kriteria" />
        <input type="hidden" name="periode" value="<?= _get('periode') ?>" />
        <div class="form-group">
            <input class="form-control" type="text" placeholder="Search" name="q" value="<?= _get('q') ?>" />
        </div>
        <div class="form-group">
            <a class="btn btn-primary" href="<?= $_GET['periode'] ? '?m=kriteria_tambah&periode=' . $_GET['periode'] : '' ?>"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
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
                    <a class="btn btn-xs btn-warning" href="?m=kriteria_ubah&ID=<?= $row->kode_kriteria ?>&periode=<?= _get('periode') ?>"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-xs btn-danger" href="aksi.php?act=kriteria_hapus&ID=<?= $row->kode_kriteria ?>&periode=<?= _get('periode') ?>" onclick="return confirm('Hapus data?')"><i class="bi bi-trash3"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>
<div class="d-flex justify-content-end mt-3">
    <div style="d-flex">
        <a class="btn btn-default" href="index.php?m=alternatif&periode=<?= _get('periode')?>"><i class="bi bi-chevron-left"></i></a>
        <a class="btn btn-default" href="index.php?m=experts&periode=<?= _get('periode')?>"><i class="bi bi-chevron-right"></i></a>
    </div>
</div>