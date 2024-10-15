
<?php
//warning
$alt = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif WHERE tanggal = '$_GET[periode]'");
$kri = $db->get_results("SELECT kode_kriteria, nama_kriteria, atribut FROM tb_kriteria WHERE tanggal = '$_GET[periode]'");
if ((array)$kri == null || (array)$alt == null){
    print_msg("Data altenatif atau kriteria kosong. Silahkan isi data terlebih dahulu pada halaman sebelumnya.");
}

$nils = $db->get_results("SELECT nilai FROM tb_rel_kriteria WHERE tanggal = '$_GET[periode]'");
if ((array)$nils == 1){
    print_msg("Data nilai altenatif kosong. Silahkan isi data terlebih dahulu pada halaman sebelumnya.");
}


$nil = $db->get_results("SELECT nilai FROM tb_rel_alternatif WHERE tanggal = '$_GET[periode]'");
if ((array)$nil == null){
    print_msg("Data nilai altenatif kosong. Silahkan isi data terlebih dahulu pada halaman sebelumnya.");
}

?>

<div class="page-header">
    <h1>Perhitungan</h1>
<form class="form-inline" action="" method="get">
        <?php
        //ganti periode
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

<div class="panel panel-primary" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <h3 class="panel-title">
            Matriks Perbandingan Kriteria AHP
        </h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <?php
                    //memanggil fungsi get_rel_kriteria
                    $matriks = get_rel_kriteria();
                    foreach ($matriks as $key => $value) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach ?>
                <tr>
            </thead>
            <?php
            //menampilkan matriks dalam bentuk tabel
            foreach ($matriks as $key => $value) : ?>
                <tr>
                    <td><?= $key  ?></td>
                    <td><?= $KRITERIA[$key]->nama_kriteria ?></td>
                    <?php foreach ($value as $k => $v) : ?>
                        <td><?= round($v, 3) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>

<div class="panel panel-primary" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <h3 class="panel-title"> 
            Matriks Perbandingan Kriteria Fuzzy AHP
        </h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th></th>
                    <?php
                    $matriks_fahp = FAHP_get_relkriteria($matriks); //memanggil fungsi FAHP_get_relkriteria
                    $lmu = FAHP_get_lmu($matriks_fahp); //memanggil fungsi FAHP_get_lmu        
                    $total_lmu = FAHP_get_total_lmu($lmu); //memanggil fungsi FAHP_get_total_lmu 

                    foreach ($matriks as $key => $value) : ?>
                        <th colspan="3"><?= $key ?></th>
                    <?php endforeach ?>
                    <th colspan="3">Jumlah Baris</th>
                <tr>
            </thead>
            <tr>
                <td></td>
                <?php foreach ($matriks_fahp as $key => $value) : ?>
                    <th>l</th>
                    <th>m</th>
                    <th>u</th>
                <?php endforeach ?>
                <th>l</th>
                <th>m</th>
                <th>u</th>
            </tr>
            <?php
            //menampilkan matriks_fahp dalam bentuk tabel 
            foreach ($matriks_fahp as $key => $value) : ?>
                <tr>
                    <th><?= $key ?></th>
                    <?php foreach ($value as $k => $v) :
                        $class = ($key == $k) ? 'bg-success' : '';
                    ?>
                        <td class="<?= $class ?>"><?= round($v[0], 2) ?></td>
                        <td class="<?= $class ?>"><?= round($v[1], 2) ?></td>
                        <td class="<?= $class ?>"><?= round($v[2], 2) ?></td>
                    <?php endforeach ?>
                    <td><?= round($lmu[$key][0], 2) ?>
                    <td><?= round($lmu[$key][1], 2) ?>
                    <td><?= round($lmu[$key][2], 2) ?>
                </tr>
            <?php endforeach ?>
            <tr>
                <td colspan="<?= count($matriks) * 3 + 1 ?>">Total [l, m, u]</td>
                <td><?= round($total_lmu[0], 2) ?>
                <td><?= round($total_lmu[1], 2) ?>
                <td><?= round($total_lmu[2], 2) ?>
            </tr>
        </table>
    </div>
</div>

<div class="panel panel-primary" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <h3 class="panel-title">
            Perhitungan nilai Sintesis (Si)
        </h3>
    </div>
    <div>
        <?php
        $Si = FAHP_get_Si($lmu, $total_lmu); //memanggil fungsi FAHP_get_Si            
        ?>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th colspan="3">Jumlah Baris</th>
                    <th colspan="3">Nilai Sintesis</th>
                </tr>
                <tr>
                    <th></th>
                    <td>l</td>
                    <td>m</td>
                    <td>u</td>
                    <td>l</td>
                    <td>m</td>
                    <td>u</td>
                </tr>
            </thead>
            <?php foreach ($lmu as $key => $val) : ?>
                <tr>
                    <th><?= $key ?></th>
                    <td><?= round($val[0], 3) ?></td>
                    <td><?= round($val[1], 3) ?></td>
                    <td><?= round($val[2], 3) ?></td>
                    <td><?= round($Si[$key][0], 3) ?></td>
                    <td><?= round($Si[$key][1], 3) ?></td>
                    <td><?= round($Si[$key][2], 3) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>

<div class="panel panel-primary" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <h3 class="panel-title">
            Penentuan Nilai Vektor (V) dan Nilai Ordinat Defuzzifikasi (d')
        </h3>
    </div>
    <div>
        <?php
        $mins = array();
        foreach ($KRITERIA as $kode => $kriteria_val) : ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $kriteria_val->nama_kriteria ?></h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>V</th>
                                <th>d'</th>
                            </tr>
                        </thead>
                        <?php
                        $d_aksen = array();
                        $temp = array();
                        foreach ($Si as $key => $val) : ?>
                            <?php if ($kode != $key) :
                                $a = $val[0] - $Si[$kode][2];
                                $b = $Si[$kode][1] - $Si[$kode][2];
                                $c = $val[1] - $val[0];
                                $d = $b - $c;
                                $e = $a / $d;
                                $d_aksen[$key] = ($Si[$kode][1] >= $Si[$key][1]) ? 1 : (($Si[$key][0] >= $Si[$kode][2]) ? 0 : $e);
                                // if ($d_aksen[$key] != 0)
                                    $temp[] = $d_aksen[$key];
                            ?>
                                <tr>
                                    <td><?= $kode . '&gt;' . $key ?></td>
                                    <td><?= round($e, 3) ?></td>
                                    <td><?= round($d_aksen[$key], 3) ?></td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    </table>
                </div>
                <?php
                $mins[$kode] = min($temp);
                ?>
                <div class="panel-footer">MIN : <?= round($mins[$kode], 3) ?></div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<div class="panel panel-primary" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <h3 class="panel-title">
            Normalisasi Bobot Vektor (W)
        </h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kriteria</th>
                    <th>W'</th>
                    <th>W</th>
                </tr>
            </thead>
            <?php
            $sum = array_sum($mins);
            foreach ($mins as $key => $val) : ?>
                <tr>
                    <th><?= $key ?></th>
                    <td><?= round($val, 3) ?></td>
                    <td><?= round($val / $sum, 3) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<?php
//echo '<pre>';
$bobot  = array();
foreach ($mins as $key => $val) {
    $bobot[$key] = $val / $sum;
}

?>
<div class="panel panel-primary" style="box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 5px;">
    <div class="panel-heading">
        <h3 class="panel-title">Perhitungan VIKOR</h3>
    </div>
    <div class="panel-body">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                        Alternatif Kriteria
                </h3>
            </div>
            <div>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <?php foreach ($KRITERIA as $key => $val) : ?>
                                <th><?= $val->nama_kriteria ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <?php
                    $atribut = array();
                    foreach ($KRITERIA as $key => $val) {
                        $atribut[$key] = $val->atribut;
                    }
                    $rel_alternatif = get_rel_alternatif();
                    $vikor = new VIKOR($rel_alternatif, $atribut, $bobot, 0.5);
                    $minmax = array();
                    
                    foreach ($vikor->data as $key => $val) : ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $ALTERNATIF[$key] ?></td>
                            <?php foreach ($val as $k => $v) : $minmax[$k][$key] = $v ?>
                                <td><?= round($v, 3) ?></td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right">Max</td>
                            <?php foreach ($vikor->minmax as $key => $val) : ?>
                                <td><?= round($val['max'], 3) ?></td>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">Min</td>
                            <?php foreach ($vikor->minmax as $key => $val) : ?>
                                <td><?= round($val['min'], 3) ?></td>
                            <?php endforeach ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                        Normalisasi Matriks
                </h3>
            </div>
            <div>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <?php foreach ($KRITERIA as $key => $val) : ?>
                                <th><?= $key ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <?php foreach ($vikor->normal as $key => $val) : ?>
                        <tr>
                            <td><?= $key ?></td>
                            <?php foreach ($val as $k => $v) : ?>
                                <td><?= round($v, 3) ?></td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                        Nilai Utilitas (S) dan Ukuran Regret (R)
                </h3>
            </div>
            <div>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <?php foreach ($KRITERIA as $key => $val) : ?>
                                <th><?= $key ?></th>
                            <?php endforeach ?>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <td>Bobot</td>
                            <?php foreach ($vikor->bobot as $key => $val) : ?>
                                <td><?= round($val, 4) ?></td>
                            <?php endforeach ?>
                            <td>S</td>
                            <td>R</td>
                        </tr>
                    </thead>
                    <?php foreach ($vikor->terbobot as $key => $val) : ?>
                        <tr>
                            <td><?= $key ?></td>
                            <?php foreach ($val as $k => $v) : ?>
                                <td><?= round($v, 4) ?></td>
                            <?php endforeach ?>
                            <td><?= round($vikor->total_s[$key], 4) ?></td>
                            <td><?= round($vikor->total_r[$key], 4) ?></td>
                        </tr>
                    <?php endforeach ?>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="<?= count($KRITERIA) + 1 ?>">S*</td>
                            <td><?= round($vikor->nilai_s['max'], 4) ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="<?= count($KRITERIA) + 1 ?>">S-</td>
                            <td><?= round($vikor->nilai_s['min'], 4) ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="<?= count($KRITERIA) + 1 ?>">R*</td>
                            <td>&nbsp;</td>
                            <td><?= round($vikor->nilai_r['max'], 4) ?></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="<?= count($KRITERIA) + 1 ?>">R-</td>
                            <td>&nbsp;</td>
                            <td><?= round($vikor->nilai_r['min'], 4) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                        Perangkingan
                </h3>
            </div>
            <div>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Q</th>
                        </tr>
                    </thead>
                    <?php
                    $data = [];
                    $categories = [];
                    foreach ($vikor->rank as $key => $val) :
                        $db->query("UPDATE tb_alternatif SET total='{$vikor->nilai_v[$key]}' WHERE kode_alternatif='$key'");
                        $data[] = $vikor->nilai_v[$key] * 1;
                        $categories[] = $ALTERNATIF[$key];

                    ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $ALTERNATIF[$key] ?></td>
                            <td><?= round($vikor->nilai_v[$key], 4) ?></td>
                        </tr>
                    </div>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
    </div>
    <div>
        <?php
        $best = key($vikor->rank);
        ?>
    </div>
</div>
<div style="float: right">
<p><a class="btn btn-success" href="cetak.php?m=hitung&periode=<?=_get('periode') ?>"><span class="glyphicon glyphicon-print"></span> Cetak Hasil</a></p>
</div>
