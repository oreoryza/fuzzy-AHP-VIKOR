<?php

$nRI = array( //menyimpan nilai RATIO INDEX	
	1 => 0,
	2 => 0,
	3 => 0.52,
	4 => 0.89,
	5 => 1.11,
	6 => 1.25,
	7 => 1.35,
	8 => 1.40,
	9 => 1.45,
	10 => 1.49
);

/**
 * mengambil nilai perbandingan kriteria dari database 
 * kemudian menyimpan dalam array
 */
function get_rel_kriteria()
{
	global $db;
	global $PERIODE;
	$data = array();
	$rows = $db->get_results("SELECT * FROM tb_rel_kriteria WHERE tanggal='$_GET[periode]' ORDER BY ID1, ID2");
	foreach ($rows as $row) {
		$data[$row->ID1][$row->ID2] = $row->nilai;
	}
	return $data;
}

/**
 * mengambil nilai triangular FUZZY AHP
 */
function FAHP_get_triangular($nilai)
{
	$fahp_triangular = array(
		'1' => array(
			'name' => 'Sama penting dengan',
			'tfn' => array(1, 1, 1),
			'rec' => array(1, 1, 1),
		),
		'2' => array(
			'name' => 'Mendekati sedikit lebih penting dari',
			'tfn' => array(1, 2, 3),
			'rec' => array(1 / 3, 1 / 2, 1),
		),
		'3' => array(
			'name' => 'Sedikit lebih penting dari',
			'tfn' => array(2, 3, 4),
			'rec' => array(1 / 2, 2 / 3, 1),
		),
		'4' => array(
			'name' => 'Mendekati lebih penting dari',
			'tfn' => array(3, 4, 5),
			'rec' => array(1 / 5, 1 / 4, 1 / 3),
		),
		'5' => array(
			'name' => 'Lebih penting dari',
			'tfn' => array(4, 5, 6),
			'rec' => array(1 / 6, 1 / 5, 1 / 4),
		),
		'6' => array(
			'name' => 'Mendekati sangat lebih penting dari',
			'tfn' => array(5, 6, 7),
			'rec' => array(1 / 7, 1 / 6, 1 / 5),
		),
		'7' => array(
			'name' => 'Sangat lebih penting dari',
			'tfn' => array(6, 7, 8),
			'rec' => array(1 / 8, 1 / 7, 1 / 6),
		),
		'8' => array(
			'name' => 'Mendekati absolut lebih penting dari',
			'tfn' => array(7, 8, 9),
			'rec' => array(1 / 9, 1 / 8, 1 / 7),
		),
		'9' => array(
			'name' => 'Absolut lebih penting dari',
			'tfn' => array(9, 9, 9),
			'rec' => array(1 / 9, 1 / 9, 1 / 9),
		),
	);

	$keys = array_keys($fahp_triangular);
	$arr = array();
	foreach ($keys as $key) {
		$arr[round(1 / $key, 5) . ""] = $key;
	}

	if (array_key_exists($nilai, $fahp_triangular)) {
		return $fahp_triangular[$nilai]['tfn'];
	} else {
		return $fahp_triangular[$arr[round($nilai, 5) . ""]]['rec'];
	}
}

/**
 * mengambil nilai triangular berdasarkan nilai perbandingan kriteria
 */
function FAHP_get_relkriteria($matriks = array())
{
	$arr = array();
	foreach ($matriks as $key => $val) {
		foreach ($val as $k => $v) {
			$arr[$key][$k] = FAHP_get_triangular($v);
		}
	}
	return $arr;
}

/**
 * mencari nilai l, m, u
 */
function FAHP_get_lmu($matriks = array())
{
	$arr = array();
	foreach ($matriks as $key => $val) {
		foreach ($val as $k => $v) {
			if (!isset($arr[$key][0]))
				$arr[$key][0] = 0;
			if (!isset($arr[$key][1]))
				$arr[$key][1] = 0;
			if (!isset($arr[$key][2]))
				$arr[$key][2] = 0;
			$arr[$key][0] += $v[0];
			$arr[$key][1] += $v[1];
			$arr[$key][2] += $v[2];
		}
	}
	//print_r($arr);
	return $arr;
}

/**
 * mencari total nilai lmu
 */
function FAHP_get_total_lmu($total_baris = array())
{
	$arr = array();
	foreach ($total_baris as $val) {
		if (!isset($arr[0]))
			$arr[0] = 0;
		if (!isset($arr[1]))
			$arr[1] = 0;
		if (!isset($arr[2]))
			$arr[2] = 0;
		$arr[0] += $val[0];
		$arr[1] += $val[1];
		$arr[2] += $val[2];
	}
	return $arr;
}

/**
 * mencari nilai sintesis
 */
function FAHP_get_Si($lmu, $total_lmu)
{

	$arr = array();
	foreach ($lmu as $key => $val) {
		$arr[$key][0] = $val[0] / $total_lmu[2];
		$arr[$key][1] = $val[1] / $total_lmu[1];
		$arr[$key][2] = $val[2] / $total_lmu[0];
	}
	return $arr;
}

/**
 * mengambil nilai alternatif dari database 
 * kemudian menyimpan dalam array
 */
function FAHP_get_rel_alternatif()
{
	global $db;

	$rows = $db->get_results("SELECT * FROM tb_rel_alternatif  WHERE tanggal='$_GET[periode]' ORDER BY kode_alternatif, kode_kriteria");
	$matriks = array();
	foreach ($rows as $row) {
		$matriks[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
	}
	return $matriks;
}

/**
 * mencari total kolom dari matriks
 */
function AHP_get_total_kolom($matriks = array())
{
	$total = array();
	foreach ($matriks as $key => $value) {
		foreach ($value as $k => $v) {
			if (!isset($total[$k]))
				$total[$k] = 0;
			$total[$k] += $v;
		}
	}
	return $total;
}

/**
 * menormalkan matriks
 */
function AHP_normalize($matriks = array(), $total = array())
{

	foreach ($matriks as $key => $value) {
		foreach ($value as $k => $v) {
			$matriks[$key][$k] = $matriks[$key][$k] / $total[$k];
		}
	}
	return $matriks;
}

/**
 * mencari nilai rata-rata matriks
 */
function AHP_get_rata($normal)
{
	$rata = array();
	foreach ($normal as $key => $value) {
		$rata[$key] = array_sum($value) / count($value);
	}
	return $rata;
}

/**
 * perkalian matriks
 */
function AHP_mmult($matriks = array(), $rata = array())
{
	$data = array();

	$rata = array_values($rata);

	foreach ($matriks as $key => $value) {
		$no = 0;
		$data[$key] = 0;
		foreach ($value as $k => $v) {
			$data[$key] += $v * $rata[$no];
			$no++;
		}
	}

	return $data;
}

/**
 * mengambil nilai konsistensi
 */
function AHP_consistency_measure($matriks, $rata)
{
	$matriks = AHP_mmult($matriks, $rata);
	foreach ($matriks as $key => $value) {
		$data[$key] = $value / $rata[$key];
	}
	return $data;
}
class VIKOR
{
	public $data, $atribut, $bobot, $index_vikor;
	public $minmax, $normal, $terbobot, $total_s, $total_r, $nilai_r, $nilai_s, $nilai_v, $rank;
	function __construct($data, $atribut, $bobot, $index_vikor)
	{
		$this->data = $data;
		$this->atribut = $atribut;
		$this->bobot = $bobot;
		$this->index_vikor = $index_vikor;
		$this->minmax();
		$this->normal();
		$this->terbobot();
		$this->total_sr();
		$this->nilai_sr();
		$this->nilai_v();
		$this->rank();
	}
	function rank()
	{
		$data = $this->nilai_v;
		asort($data);
		$no = 1;
		$this->rank = array();
		foreach ($data as $key => $value) {
			$this->rank[$key] = $no++;
		}
	}
	function nilai_v()
	{
		$this->nilai_v = array();
		foreach ($this->total_s as $key => $val) {
			$v = 0.5;
			$s = $this->total_s[$key];
			$r = $this->total_r[$key];
			$s_max = $this->nilai_s['max'];
			$s_min = $this->nilai_s['min'];
			$r_max = $this->nilai_r['max'];
			$r_min = $this->nilai_r['min'];
			$this->nilai_v[$key] = $v * ($s - $s_min) / ($s_max - $s_min) + (1 - $v) * ($r - $r_min) / ($r_max - $r_min);
		}
	}
	function nilai_sr()
	{
		$this->nilai_s['max'] = max($this->total_s);
		$this->nilai_s['min'] = min($this->total_s);
		$this->nilai_r['max'] = max($this->total_r);
		$this->nilai_r['min'] = min($this->total_r);
	}
	function total_sr()
	{
		foreach ($this->terbobot as $key => $val) {
			$this->total_s[$key] = array_sum($val);
			$this->total_r[$key] = max($val);
		}
	}
	function terbobot()
	{
		$arr = array();
		foreach ($this->normal as $key => $val) {
			foreach ($val as $k => $v) {
				$arr[$key][$k] = $v * $this->bobot[$k];
			}
		}
		$this->terbobot = $arr;
	}
	function normal()
	{
		$arr = array();
		foreach ($this->data as $key => $val) {
			foreach ($val as $k => $v) {
				$arr[$key][$k] = ($this->minmax[$k]['max'] - $v) / ($this->minmax[$k]['max'] - $this->minmax[$k]['min']);
			}
		}
		$this->normal = $arr;
	}
	function minmax()
	{
		$arr = array();
		foreach ($this->data as $key => $val) {
			foreach ($val as $k => $v) {
				$arr[$k][$key] = $v;
			}
		}
		$arr2 = array();
		foreach ($arr as $key => $val) {
			if ($this->atribut[$key] == 'benefit') {
				$arr2[$key]['min'] = min($val);
				$arr2[$key]['max'] = max($val);
			} else {
				$arr2[$key]['min'] = max($val);
				$arr2[$key]['max'] = min($val);
			}
		}
		$this->minmax = $arr2;
	}
}
