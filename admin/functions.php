<?php
error_reporting(); //menyembunyikan error NOTICE dan DEPRECATED
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

$mod = _get('m');
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

function get_rel_alternatif()
{
    global $db;
    global $PERIODE;
    $rows = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE tanggal = '$_GET[periode]' ORDER BY kode_alternatif, kode_kriteria");
    $arr = array();
    foreach ($rows as $row) {
        $arr[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
    }
    return $arr;
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
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"></span></button>' . $msg . '</div>');
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
