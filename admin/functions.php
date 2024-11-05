<?php
error_reporting(1); //menyembunyikan error NOTICE dan DEPRECATED
session_start();

include 'config.php'; //panggil file config.php
include 'includes/db.php'; //panggil file db.php
$db = new DB($config['server'], $config['username'], $config['password'], $config['database_name']);
include 'includes/fahp_vikor.php';

function _post($key, $val = null)
{
    global $_POST;
    if (isset($_POST[$key]))
        return $_POST[$key];
    else
        return $val;
}

function _get($key, $val = null)
{
    global $_GET;
    if (isset($_GET[$key]))
        return $_GET[$key];
    else
        return $val;
}

function _session($key, $val = null)
{
    global $_SESSION;
    if (isset($_SESSION[$key]))
        return $_SESSION[$key];
    else
        return $val;
}

$mod = isset($_GET['m']) ? $_GET['m'] : 'home';
$act = _get('act');

/**
 * mengambil data alternatif dari database 
 * kemudian menyimpan dalam array
 */

$ALTERNATIF = array();
$rows = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif WHERE tanggal = '$_GET[periode]' ORDER BY kode_alternatif");
foreach ($rows as $row) {
    $ALTERNATIF[$row->kode_alternatif] = $row->nama_alternatif;
}

/**
 * mengambil data kriteria dari database 
 * kemudian menyimpan dalam array
 */

$KRITERIA = array();
$rows = $db->get_results("SELECT kode_kriteria, nama_kriteria, atribut FROM tb_kriteria WHERE tanggal = '$_GET[periode]' ORDER BY kode_kriteria");
foreach ($rows as $row) {
    $KRITERIA[$row->kode_kriteria] = $row;
}

$EXPERT = array();
$rows = $db->get_results("SELECT kode_expert, nama_expert FROM tb_experts WHERE tanggal = '$_GET[periode]' ORDER BY kode_expert");
foreach ($rows as $row) {
    $EXPERT[$row->kode_expert] = $row;
}

function get_rel_alternatif($expert = null) 
{
    global $db;
    global $PERIODE;
    $data = array();
    
    // Ambil semua alternatif
    $alternatifs = $db->get_results("SELECT kode_alternatif FROM tb_alternatif WHERE tanggal='$PERIODE'");
    
    // Ambil semua kriteria
    $kriterias = $db->get_results("SELECT kode_kriteria FROM tb_kriteria WHERE tanggal='$PERIODE'");
    
    // Ambil semua expert
    if (!$expert) {
        $experts = $db->get_results("SELECT kode_expert FROM tb_experts WHERE tanggal='$PERIODE'");
    } else {
        $experts = array((object)array('kode_expert' => $expert));
    }

    // Inisialisasi array dengan nilai 0
    foreach ($alternatifs as $alt) {
        foreach ($kriterias as $krit) {
            foreach ($experts as $exp) {
                // Cek apakah relasi sudah ada di database
                $existing = $db->get_var("SELECT COUNT(*) FROM tb_rel_alternatif 
                    WHERE tanggal='$PERIODE' 
                    AND kode_alternatif='$alt->kode_alternatif' 
                    AND kode_kriteria='$krit->kode_kriteria'
                    AND kode_expert='$exp->kode_expert'");

                // Jika tidak ada, masukkan nilai 0
                if ($existing == 0) {
                    // Insert nilai 0 ke dalam database
                    $db->query("INSERT INTO tb_rel_alternatif (tanggal, kode_alternatif, kode_kriteria, kode_expert, nilai) 
                        VALUES ('$PERIODE', '$alt->kode_alternatif', '$krit->kode_kriteria', '$exp->kode_expert', 0)");
                }
            }
            // Set nilai 0 ke dalam array data
            $data[$alt->kode_alternatif][$krit->kode_kriteria] = 0;
        }
    }

    // Jika expert spesifik diminta
    if ($expert) {
        // Ambil nilai yang sudah ada di database untuk expert tersebut
        $rows = $db->get_results("SELECT kode_alternatif, kode_kriteria, nilai 
            FROM tb_rel_alternatif 
            WHERE tanggal='$PERIODE' 
            AND kode_expert='$expert'");
        
        // Update nilai yang sudah ada
        foreach ($rows as $row) {
            $data[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
        }
    } else {
        // Ambil rata-rata nilai dari semua expert
        $rows = $db->get_results("SELECT kode_alternatif, kode_kriteria, AVG(nilai) as nilai 
            FROM tb_rel_alternatif 
            WHERE tanggal='$PERIODE'
            GROUP BY kode_alternatif, kode_kriteria");
        
        // Update nilai dengan rata-rata
        foreach ($rows as $row) {
            $data[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
        }
    }

    return $data;
}
/**
 * option untuk nilai kriteria
 */
function AHP_get_nilai_option($selected = '')
{
    $nilai = array(
        '1' => 'Sama penting dengan',
        '2' => 'Mendekati sedikit lebih penting dari',
        '3' => 'Sedikit lebih penting dari',
        '4' => 'Mendekati lebih penting dari',
        '5' => 'Lebih penting dari',
        '6' => 'Mendekati sangat penting dari',
        '7' => 'Sangat penting dari',
        '8' => 'Mendekati mutlak dari',
        '9' => 'Mutlak sangat penting dari',
    );
    $a = '';
    foreach ($nilai as $key => $value) {
        if ($selected == $key)
            $a .= "<option value='$key' selected>$key - $value</option>";
        else
            $a .= "<option value='$key'>$key - $value</option>";
    }
    return $a;
}

/**
 * option untuk kriteria
 */
function AHP_get_kriteria_option($selected = '')
{
    global $db;
    global $PERIODE;
    $rows = $db->get_results("SELECT kode_kriteria, nama_kriteria FROM tb_kriteria WHERE tanggal='$_GET[periode]' ORDER BY kode_kriteria");
    $a = '';
    foreach ($rows as $row) {
        if ($row->kode_kriteria == $selected)
            $a .= "<option value='$row->kode_kriteria' selected>$row->kode_kriteria - $row->nama_kriteria</option>";
        else
            $a .= "<option value='$row->kode_kriteria'>$row->kode_kriteria - $row->nama_kriteria</option>";
    }
    return $a;
}

function esc_field($str)
{
    if ($str)
        return addslashes($str);
}


function redirect_js($url)
{
    echo '<script type="text/javascript">window.location.replace("' . $url . '");</script>';
}

function alert($url)
{
    echo '<script type="text/javascript">alert("' . $url . '");</script>';
}

function print_msg($msg, $type = 'danger')
{
    echo ('<div class="alert alert-' . $type . ' alert-dismissible" role="alert">
  <span aria-hidden="true"></span></button>' . $msg . '</div>');
}

/**
 * Menampilkan value dari variabel POST atau GET
 * @param string $key nama field atau variabel
 * @param string $default data asli jika null
 * @return string Isi variabel POST atau get
 */
function set_value($key = null, $default = null)
{
    global $_POST;
    if (isset($_POST[$key]))
        return $_POST[$key];

    if (isset($_GET[$key]))
        return $_GET[$key];

    return $default;
}
function kode_oto($field, $table, $prefix, $length)
{
    global $db;
    $var = $db->get_var("SELECT $field FROM $table WHERE $field REGEXP '{$prefix}[0-9]{{$length}}' ORDER BY $field DESC");
    if ($var) {
        return $prefix . substr(str_repeat('0', $length) . ((int) substr((string)$var, -$length) + 1), -$length);
    } else {
        return $prefix . str_repeat('0', $length - 1) . 1;
    }
}

function get_atribut_option($selected = '')
{
    $atribut = array('benefit' => 'Benefit', 'cost' => 'Cost');
    $a = '';
    foreach ($atribut as $key => $val) {
        if ($selected == $key)
            $a .= "<option value='$key' selected>$val</option>";
        else
            $a .= "<option value='$key'>$val</option>";
    }
    return $a;
}

function get_ket_option($selected = '')
{
    $atribut = array('Ancaman' => 'Ancaman', 'Peluang' => 'Peluang');
    $a = '';
    foreach ($atribut as $key => $val) {
        if ($selected == $key)
            $a .= "<option value='$key' selected>$val</option>";
        else
            $a .= "<option value='$key'>$val</option>";
    }
    return $a;
}

function isActive($name)
{

    if (is_array($name)) {
        $result = false;
        foreach ($name as $m) {
            if ($m == get('m')) {
                $result = true;
            }
        }
        return  $result ?  'active'  : null;
    }

    return get('m') == $name ?  'active'  : null;
}

function get_final_nilai() {
    global $db;
    $data = array();
    $experts = $db->get_results("SELECT kode_expert FROM tb_experts WHERE tanggal='$_GET[periode]'");
    foreach($experts as $expert) {
        $nilai = get_rel_kriteria($expert->kode_expert);
        // Gabungkan nilai dari setiap expert
        foreach($nilai as $k1 => $v1) {
            foreach($v1 as $k2 => $v2) {
                if(!isset($data[$k1][$k2])) 
                    $data[$k1][$k2] = 0;
                $data[$k1][$k2] += $v2;
            }
        }
    }
    // Hitung rata-rata
    $count = count($experts);
    foreach($data as $k1 => $v1) {
        foreach($v1 as $k2 => $v2) {
            $data[$k1][$k2] = $v2 / $count;
        }
    }
    return $data;
}

function get_final_nilai_fuzzy() {
    global $db;
    $data = array();
    $experts = $db->get_results("SELECT kode_expert FROM tb_experts WHERE tanggal='$_GET[periode]'");
    
    foreach($experts as $expert) {
        $nilai = get_rel_kriteria($expert->kode_expert);
        // Konversi dan gabungkan nilai dari setiap expert
        foreach($nilai as $k1 => $v1) {
            foreach($v1 as $k2 => $v2) {
                if(!isset($data[$k1][$k2])) {
                    $data[$k1][$k2] = array(0, 0, 0); // Inisialisasi array fuzzy
                }
                $fuzzy = FAHP_get_triangular($v2); // Konversi ke bilangan fuzzy
                $data[$k1][$k2][0] += $fuzzy[0]; // Lower
                $data[$k1][$k2][1] += $fuzzy[1]; // Middle
                $data[$k1][$k2][2] += $fuzzy[2]; // Upper
            }
        }
    }
    
    // Hitung rata-rata
    $count = count($experts);
    foreach($data as $k1 => &$v1) {
        foreach($v1 as $k2 => &$v2) {
            $v2[0] /= $count; // Rata-rata Lower
            $v2[1] /= $count; // Rata-rata Middle
            $v2[2] /= $count; // Rata-rata Upper
        }
    }
    
    return $data;
}

function get_final_alternatif() {
    global $db;
    $data = array();
    $experts = $db->get_results("SELECT kode_expert FROM tb_experts WHERE tanggal='$_GET[periode]'");
    foreach($experts as $expert) {
        $nilai = get_rel_alternatif($expert->kode_expert);
        // Gab ungkan nilai ke dalam array data
        foreach ($nilai as $kode_alternatif => $nilai_kriteria) {
            if (!isset($data[$kode_alternatif])) {
                $data[$kode_alternatif] = array();
            }
            foreach ($nilai_kriteria as $kode_kriteria => $nilai_value) {
                if (!isset($data[$kode_alternatif][$kode_kriteria])) {
                    $data[$kode_alternatif][$kode_kriteria] = 0;
                }
                $data[$kode_alternatif][$kode_kriteria] += $nilai_value;
            }
        }
    }

    // Hitung rata-rata
    foreach ($data as $kode_alternatif => $nilai_kriteria) {
        foreach ($nilai_kriteria as $kode_kriteria => $total_nilai) {
            $jumlah_expert = count($experts);
            $data[$kode_alternatif][$kode_kriteria] = $jumlah_expert > 0 ? $total_nilai / $jumlah_expert : 0;
        }
    }

    return $data;
}