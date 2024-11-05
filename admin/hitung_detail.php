<div class="d-flex justify-content-between">
    <h1>Detail Perhitungan</h1>
    <a href="?m=hitung&periode=<?= _get('periode')?>">
        <i class="bi bi-x-lg"></i>
    </a>
</div>

<div >
    <div class="mt-5">
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
                    $matriks = get_final_nilai();
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

<div  >
    <div class="mt-5">
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
                    $matriks_fahp = get_final_nilai_fuzzy(); // Gunakan fungsi baru
                    foreach ($matriks_fahp as $key => $value) : ?>
                        <th colspan="3"><?= $key ?></th>
                    <?php endforeach ?>
                </tr>
                <tr>
                    <td></td>
                    <?php foreach ($matriks_fahp as $key => $value) : ?>
                        <th>l</th>
                        <th>m</th>
                        <th>u</th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($matriks_fahp as $key => $value) : ?>
                <tr>
                    <th><?= $key ?></th>
                    <?php foreach ($value as $k => $v) :
                        $class = ($key == $k) ? 'bg-success' : '';
                    ?>
                        <td class="<?= $class ?>"><?= round($v[0], 2) ?></td>
                        <td class="<?= $class ?>"><?= round($v[1], 2) ?></td>
                        <td class="<?= $class ?>"><?= round($v[2], 2) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>

<div  >
    <div class="mt-5">
        <h3 class="panel-title">
            Perhitungan nilai Sintesis (Si)
        </h3>
    </div>
    <div>
        <?php
        // Hitung total baris (lmu)
        $matriks_fahp = get_final_nilai_fuzzy();
        $lmu = array();
        foreach ($matriks_fahp as $key => $values) {
            $lmu[$key] = array(0, 0, 0);
            foreach ($values as $val) {
                $lmu[$key][0] += $val[0]; // Sum lower
                $lmu[$key][1] += $val[1]; // Sum middle
                $lmu[$key][2] += $val[2]; // Sum upper
            }
        }

        // Hitung total keseluruhan
        $total_lmu = array(0, 0, 0);
        foreach ($lmu as $values) {
            $total_lmu[0] += $values[0]; // Total lower
            $total_lmu[1] += $values[1]; // Total middle
            $total_lmu[2] += $values[2]; // Total upper
        }

        // Hitung nilai sintesis
        $Si = array();
        foreach ($lmu as $key => $values) {
            $Si[$key] = array(
                $values[0] / $total_lmu[2], // Lower
                $values[1] / $total_lmu[1], // Middle
                $values[2] / $total_lmu[0]  // Upper
            );
        }
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
            <tfoot>
                <tr>
                    <th>Total</th>
                    <td><?= round($total_lmu[0], 3) ?></td>
                    <td><?= round($total_lmu[1], 3) ?></td>
                    <td><?= round($total_lmu[2], 3) ?></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div >
    <div class="mt-5">
        <h3 class="panel-title">
            Penentuan Nilai Vektor (V) dan Nilai Ordinat Defuzzifikasi (d')
        </h3>
    </div>
    <div>
        <?php
        $mins = array();
        foreach ($KRITERIA as $kode => $kriteria_val) : ?>
            <div class="panel panel-info">
                <div class="mt-5">
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

<div  >
    <div class="mt-5">
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
} ?>

<div  >
    <div class="mt-5">
        <h3 class="panel-title">Perhitungan VIKOR</h3>
    </div>
    <div class="panel-body">
        <div class="panel panel-info">
            <div class="mt-5">
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
                    $rel_alternatif = get_final_alternatif();
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
                                <td><?= round($val['max'], 2) ?></td>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">Min</td>
                            <?php foreach ($vikor->minmax as $key => $val) : ?>
                                <td><?= round($val['min'], 2) ?></td>
                            <?php endforeach ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="mt-5">
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
            <div class="mt-5">
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
    </div>
    <div>
        <?php
        $best = key($vikor->rank);
        ?>
    </div>
</div>
<div class="panel panel-info">
    <div class="mt-5 d-flex justify-content-between">
        <h3 class="panel-title">
            Perangkingan
        </h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Q</th>
                </tr>
            </thead>
            <?php
            $data = [];
            $categories = [];
            $ranked_alternatives = [];
            
            // Urutkan alternatif berdasarkan nilai Q (ascending)
            $temp_rank = $vikor->nilai_v;
            asort($temp_rank);
            
            $rank = 1;
            foreach ($temp_rank as $key => $val) {
                $ranked_alternatives[] = [
                    'kode' => $key,
                    'nama' => $ALTERNATIF[$key],
                    'nilai' => $val,
                    'rank' => $rank
                ];
                $rank++;
            }

            // Validasi Kondisi 1: Acceptable Advantage
            $DQ = 1 / (count($ranked_alternatives) - 1); // Threshold DQ
            $advantage_gap = $ranked_alternatives[1]['nilai'] - $ranked_alternatives[0]['nilai'];
            $acceptable_advantage = $advantage_gap >= $DQ;

            // Validasi Kondisi 2: Acceptable Stability
            $acceptable_stability = false;
            if ($ranked_alternatives[0]['nilai'] == min($vikor->total_s) || 
                $ranked_alternatives[0]['nilai'] == min($vikor->total_r) || 
                $ranked_alternatives[0]['nilai'] == min($temp_rank)) {
                $acceptable_stability = true;
            }

            foreach ($ranked_alternatives as $alt) :
                $db->query("UPDATE tb_alternatif SET total='{$alt['nilai']}' WHERE kode_alternatif='{$alt['kode']}'");
                $data[] = $alt['nilai'] * 1;
                $categories[] = $alt['nama'];
            ?>
                <tr>
                    <td><?= $alt['rank'] ?></td>
                    <td><?= $alt['kode'] ?></td>
                    <td><?= $alt['nama'] ?></td>
                    <td><?= round($alt['nilai'], 4) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="mt-4">
            <h4>Validasi Solusi Kompromi:</h4>
            <div class="card p-3">
                <p><strong>1. Acceptable Advantage (Keuntungan yang Dapat Diterima)</strong></p>
                <?php if ($acceptable_advantage): ?>
                    <p class="text-success">✓ Terpenuhi: Gap antara alternatif terbaik pertama dan kedua (<?= round($advantage_gap, 4) ?>) 
                    lebih besar atau sama dengan threshold DQ (<?= round($DQ, 4) ?>)</p>
                <?php else: ?>
                    <p class="text-danger">✗ Tidak Terpenuhi: Gap antara alternatif terbaik pertama dan kedua (<?= round($advantage_gap, 4) ?>) 
                    lebih kecil dari threshold DQ (<?= round($DQ, 4) ?>)</p>
                    <p>Solusi kompromi: Alternatif berikut memiliki nilai yang hampir sama:</p>
                    <ul>
                    <?php
                    $similar_alternatives = [];
                    $current_value = $ranked_alternatives[0]['nilai'];
                    foreach ($ranked_alternatives as $alt) {
                        if ($alt['nilai'] - $current_value <= $DQ) {
                            echo "<li>{$alt['kode']} - {$alt['nama']}</li>";
                        } else {
                            break;
                        }
                    }
                    ?>
                    </ul>
                <?php endif; ?>

                <p class="mt-3"><strong>2. Acceptable Stability in Decision Making (Stabilitas dalam Pengambilan Keputusan)</strong></p>
                <?php if ($acceptable_stability): ?>
                    <p class="text-success">✓ Terpenuhi: Alternatif terbaik juga merupakan yang terbaik dalam salah satu atau lebih dari: 
                    nilai S (group utility), nilai R (individual regret), atau nilai Q (compromise solution)</p>
                <?php else: ?>
                    <p class="text-danger">✗ Tidak Terpenuhi: Alternatif terbaik tidak konsisten dalam penilaian S, R, atau Q</p>
                <?php endif; ?>

                <p class="mt-3"><strong>Kesimpulan:</strong></p>
                <?php if ($acceptable_advantage && $acceptable_stability): ?>
                    <p class="text-success">Solusi kompromi valid dan stabil.</p>
                <?php elseif ($acceptable_advantage): ?>
                    <p class="text-warning">Solusi kompromi memiliki keuntungan yang dapat diterima tetapi kurang stabil.</p>
                <?php elseif ($acceptable_stability): ?>
                    <p class="text-warning">Solusi kompromi stabil tetapi keuntungannya kurang signifikan.</p>
                <?php else: ?>
                    <p class="text-danger">Solusi kompromi tidak memenuhi kedua kondisi validasi.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>