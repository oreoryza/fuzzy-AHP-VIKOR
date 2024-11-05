<div>
    <h1>File</h1>
</div>
<div class="mt-3">
    <div class="py-3">
        <form class="input-group d-flex justify-content-between">
            <input type="hidden" name="m" value="periode" />
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Search" name="q" value="<?= _get('q') ?>" />
            </div>
            <div>
                <a class="btn btn-primary" href="?m=periode_tambah"><span class="glyphicon glyphicon-plus"></span>Tambah</a>
            </div>
        </form>
    </div>
    <table class="table table-bordered table-hover table-striped", style="table-layout: fixed">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php
        $q = esc_field(_get('q'));
        $rows = $db->get_results("SELECT * FROM tb_periode WHERE tanggal LIKE '%$q%' OR nama LIKE '%$q%' ORDER BY tanggal");
        foreach ($rows as $row) : ?>
            <tr>
                <td style="width:100px"><?= $row->tanggal ?></td>
                <td><a style="text-decoration:none" href="?m=alternatif&periode=<?=$row->tanggal?>"><?= $row->nama ?><a></td>
                <td>
                    <a class="btn btn-xs btn-warning" href="?m=periode_ubah&ID=<?= $row->tanggal ?>"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-xs btn-danger" href="aksi.php?act=periode_hapus&ID=<?= $row->tanggal ?>" onclick="return confirm('Hapus data?')"><i class="bi bi-trash3"></i></a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>