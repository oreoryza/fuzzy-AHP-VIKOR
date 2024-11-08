<h1>Alternatif</h1>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Alternatif</th>
            <th>Kategori</th>
        </tr>
    </thead>
    <?php
    $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE tanggal='$_GET[periode]' ORDER BY kode_alternatif ASC");
    $no = 0;
    foreach ($rows as $row) : ?>
        <tr>
            <td><?= ++$no ?></td>
            <td><?= $row->kode_alternatif ?></td>
            <td><?= $row->nama_alternatif ?></td>
            <td><?= $row->kategori ?></td>
        </tr>
    <?php endforeach ?>
</table>

<h1>Kriteria</h1>
<table>
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama Kriteria</th>
			<th>Atribut</th>
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
		</tr>
	<?php endforeach ?>
</table>

<?php
$data = get_rel_alternatif();
?>
<div class="page-header">
    <h1>Nilai Bobot Alternatif</h1>
</div>
<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Alternatif</th>
            <?php foreach ($KRITERIA as $key => $val) : ?>
                <th><?= $val->nama_kriteria ?></th>
            <?php endforeach ?>
        </tr>
    </thead>

    <?php
    foreach ($data as $key => $val) : ?>
        <tr>
            <td><?= $key ?></td>
            <td><?= $ALTERNATIF[$key]; ?></td>
            <?php foreach ($val as $k => $v) : ?>
                <td><?= $v ?></td>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
</table>

<h1 style="margin-top:50px">Perangkingan</h1>
<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Total</th>
            <th>Rank</th>
        </tr>
    </thead>
    <?php
    $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE tanggal='$_GET[periode]' ORDER BY total ASC");
    $no = 0;
    foreach ($rows as $row) : ?>
        <tr>
            <td><?= $row->kode_alternatif ?></td>
            <td><?= $row->nama_alternatif ?></td>
            <td><?= round($row->total, 3) ?></td>
            <td><?= ++$no ?></td>
        </tr>
    <?php endforeach ?>
</table>
<p>Jadi, risiko tertinggi adalah risiko <strong><?= $rows[0]->kode_alternatif ?></strong> dengan nilai <strong><?= round($rows[0]->total, 3) ?></strong> dari <strong><?= count($rows) ?></strong> alternatif.</p>