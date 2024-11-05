<h1>Alternatif</h1>
<form class="mt-4" action="" method="get">
    <?php
    print_msg("Pastikan untuk memilih file yang diinginkan terlebih dahulu", "info");
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
            <button class="btn btn-primary" type="submit" href="?m=alternatif&periode=<?= _get('periode')?>">Set File</button>
        </div>
    </div>
</form>

<div class="mt-2">
    <div class="py-3">
        <form class="input-group d-flex justify-content-between">
            <input type="hidden" name="m" value="alternatif" />
            <input type="hidden" name="periode" value="<?= _get('periode') ?>" />
            <div>
                <input class="form-control" type="text" placeholder="Search" name="q" value="<?= _get('q') ?>" />
            </div>
            <div>
                <a class="btn btn-primary" href="<?= $_GET['periode'] ? '?m=alternatif_tambah&periode=' . $_GET['periode'] : '' ?>">Tambah</a>
            </div>
        </form>
    </div>
    <table class="table table-bordered table-hover table-striped", style="table-layout: auto">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Alternatif</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php
        $q = esc_field(_get('q'));
        $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE nama_alternatif LIKE '%$q%' AND tanggal= '$PERIODE' ORDER BY kode_alternatif");
        $no = 0;
        foreach ($rows as $row) : ?>
            <tr>
                <td><?= ++$no ?></td>
                <td style="width:100px"><?= $row->kode_alternatif ?></td>
                <td><?= $row->nama_alternatif ?></td>
                <td>
                    <a class="btn btn-xs btn-warning" href="?m=alternatif_ubah&ID=<?= $row->kode_alternatif ?>&periode=<?= _get('periode') ?>"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-xs btn-danger" href="aksi.php?act=alternatif_hapus&ID=<?= $row->kode_alternatif ?>&periode=<?= _get('periode') ?>" onclick="return confirm('Hapus data?')"><i class="bi bi-trash3"></i></a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
<div class="d-flex justify-content-end mt-3">
    <div style="d-flex">
        <a class="btn btn-default" href="index.php?m=periode&periode=<?= _get('periode')?>"><i class="bi bi-chevron-left"></i></a>
        <a class="btn btn-default" href="index.php?m=kriteria&periode=<?= _get('periode')?>"><i class="bi bi-chevron-right"></i></a>
    </div>
</div>
