<div class="page-header">
    <h1>File</h1>
</div>
<div class="panel panel-default" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <form class="form-inline">
            <input type="hidden" name="m" value="periode" />
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?= _get('q') ?>" />
            </div>
            <div class="form-group">
                <a class="btn btn-success" href="?m=periode&periode"><span class="glyphicon glyphicon-refresh"></span></a>
            </div>
            <div class="form-group" style="float: right">
                <a class="btn btn-primary" href="?m=periode_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
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
                    <a class="btn btn-xs btn-warning" href="?m=periode_ubah&ID=<?= $row->tanggal ?>"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn btn-xs btn-danger" href="aksi.php?act=periode_hapus&ID=<?= $row->tanggal ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>