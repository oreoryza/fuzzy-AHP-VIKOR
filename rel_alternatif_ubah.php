<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]' AND tanggal='$PERIODE'");
?>
<div class="page-header">
    <h1>Ubah Nilai Bobot &raquo; <small><?= $row->nama_alternatif ?></small></h1>
</div>
<div class="row">
    <div class="col-sm-4">
        <?php if ($_POST) include 'aksi.php'; ?>
        <form method="post">
            <input type="hidden" name="kode_alternatif" value="<?= $row->kode_alternatif ?>">
            <?php
            $experts = $db->get_results("SELECT * FROM tb_experts WHERE tanggal='$PERIODE'");
            $kriteria = $db->get_results("SELECT * FROM tb_kriteria WHERE tanggal='$PERIODE'");
            
            // Inisialisasi atau ambil data yang ada
            foreach ($experts as $expert) {
                foreach ($kriteria as $krit) {
                    // Cek apakah data sudah ada
                    $existing = $db->get_var("SELECT COUNT(*) FROM tb_rel_alternatif 
                        WHERE tanggal='$PERIODE' 
                        AND kode_alternatif='$row->kode_alternatif'
                        AND kode_kriteria='$krit->kode_kriteria'
                        AND kode_expert='$expert->kode_expert'");
                    
                    // Jika belum ada, insert nilai 0
                    if ($existing == 0) {
                        $db->query("INSERT INTO tb_rel_alternatif (tanggal, kode_alternatif, kode_kriteria, kode_expert, nilai) 
                            VALUES ('$PERIODE', '$row->kode_alternatif', '$krit->kode_kriteria', '$expert->kode_expert', 0)");
                    }
                }
            }
            
            foreach ($experts as $expert) : ?>
            <div class="card mt-4 p-3">
                <h4>Expert: <?= $expert->nama_expert ?></h4>
                <?php foreach ($kriteria as $krit) :
                    // Ambil nilai dari database, jika tidak ada gunakan 0
                    $nilai = $db->get_var("SELECT nilai FROM tb_rel_alternatif 
                        WHERE tanggal='$PERIODE' 
                        AND kode_alternatif='$row->kode_alternatif'
                        AND kode_kriteria='$krit->kode_kriteria'
                        AND kode_expert='$expert->kode_expert'");
                    
                    if ($nilai === null || $nilai === false) {
                        // Insert nilai 0 jika tidak ada data
                        $db->query("INSERT INTO tb_rel_alternatif (tanggal, kode_alternatif, kode_kriteria, kode_expert, nilai) 
                            VALUES ('$PERIODE', '$row->kode_alternatif', '$krit->kode_kriteria', '$expert->kode_expert', 0)");
                        $nilai = 0;
                    }
                ?>
                    <div class="form-group">
                        <label><?= $krit->nama_kriteria ?></label>
                        <input type="number" class="form-control" min="1" max="5" name="nilai[<?= $expert->kode_expert ?>][<?= $krit->kode_kriteria ?>]" value="<?= $nilai ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <div class="form-group mt-3">
                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=rel_alternatif&periode=<?= $PERIODE ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>