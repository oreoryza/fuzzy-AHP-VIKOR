<?php
require_once 'functions.php';
$PERIODE = _get('periode');
if ($mod == 'login') {
    $user = esc_field($_POST['user']);
    $pass = esc_field($_POST['pass']);

    $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user' AND pass='$pass'");
    if ($row) {
        $_SESSION['login'] = $row->user;
        redirect_js("index.php");
    } else {
        print_msg("Salah kombinasi username dan password.");
    }
} elseif ($act == 'logout') {
    unset($_SESSION['login']);
    header("location:index.php?m=login");
}


/** ALTERNATIF **/
elseif ($mod == 'alternatif_tambah') {
    $kode_alternatif = $_POST['kode_alternatif'];
    $nama_alternatif = $_POST['nama_alternatif'];
    $kategori = $_POST['kategori'];

    if ($kode_alternatif == '' || $nama_alternatif == '' || $kategori == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("INSERT INTO tb_alternatif (tanggal, kode_alternatif, nama_alternatif, kategori) VALUES ('$PERIODE', '$kode_alternatif', '$nama_alternatif', '$kategori')");
        $db->query("INSERT INTO tb_rel_alternatif(tanggal, kode_alternatif, kode_kriteria, nilai) SELECT '$PERIODE', '$kode_alternatif', kode_kriteria, 0 FROM tb_kriteria WHERE tanggal ='$PERIODE'");
        redirect_js("index.php?m=alternatif&periode=$PERIODE");
    }
} elseif ($mod == 'alternatif_ubah') {
    $kode_alternatif = $_POST['kode_alternatif'];
    $nama_alternatif = $_POST['nama_alternatif'];
    $kategori = $_POST['kategori'];

    if ($kode_alternatif == '' || $nama_alternatif == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_alternatif SET kode_alternatif='$kode_alternatif', nama_alternatif='$nama_alternatif', kategori='$kategori' WHERE kode_alternatif='$_GET[ID]'");
        redirect_js("index.php?m=alternatif&periode=$PERIODE");
    }
} elseif ($act == 'alternatif_hapus') {
    $db->query("DELETE FROM tb_rel_alternatif WHERE tanggal='$PERIODE' and kode_alternatif='$_GET[ID]'");
    $db->query("DELETE FROM tb_alternatif WHERE tanggal='$PERIODE' and kode_alternatif='$_GET[ID]'");
    header("location:index.php?m=alternatif&periode=$PERIODE");
}

/** EXPERTS **/
elseif ($mod == 'experts_tambah') {
    $kode_expert = $_POST['kode_expert'];
    $nama_expert = $_POST['nama_expert'];

    if ($kode_expert == '' || $nama_expert == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_experts WHERE kode_expert='$kode_expert'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("INSERT INTO tb_experts (tanggal, kode_expert, nama_expert) VALUES ('$PERIODE', '$kode_expert', '$nama_expert')");
        redirect_js("index.php?m=experts&periode=$PERIODE");
    }
} elseif ($mod == 'experts_ubah') {
    $kode_expert = $_POST['kode_expert'];
    $nama_expert = $_POST['nama_expert'];

    if ($kode_expert == '' || $nama_expert == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_experts SET kode_expert='$kode_expert', nama_expert='$nama_expert' WHERE kode_expert='$_GET[ID]'");
        redirect_js("index.php?m=experts&periode=$PERIODE");
    }
} elseif ($act == 'experts_hapus') {
    $db->query("DELETE FROM tb_experts WHERE tanggal='$PERIODE' and kode_axpert='$_GET[ID]'");
    header("location:index.php?m=expert&periode=$PERIODE");
}

/** kriteria */
elseif ($mod == 'kriteria_tambah') {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $atribut = $_POST['atribut'];

    if ($kode_kriteria == '' || $nama_kriteria == '' || $atribut == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode_kriteria' and tanggal='$PERIODE'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("INSERT INTO tb_kriteria (tanggal, kode_kriteria, nama_kriteria, atribut) VALUES ('$PERIODE', '$kode_kriteria', '$nama_kriteria', '$atribut')");
        $db->query("INSERT INTO tb_rel_kriteria(tanggal, ID1, ID2, nilai) SELECT '$PERIODE', '$kode_kriteria', kode_kriteria, 1 FROM tb_kriteria WHERE tanggal = '$PERIODE'");
        $db->query("INSERT INTO tb_rel_kriteria(tanggal, ID1, ID2, nilai) SELECT '$PERIODE', kode_kriteria, '$kode_kriteria', 1 FROM tb_kriteria WHERE kode_kriteria<>'$kode_kriteria' AND tanggal = '$PERIODE'");

        $db->query("INSERT INTO tb_rel_alternatif(tanggal, kode_alternatif, kode_kriteria, nilai) SELECT '$PERIODE', kode_alternatif, '$kode_kriteria', 0  FROM tb_alternatif WHERE tanggal = '$PERIODE'");

        redirect_js("index.php?m=kriteria&periode=$PERIODE");
    }

} else if ($mod == 'kriteria_ubah') {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $atribut = $_POST['atribut'];

    if ($kode_kriteria == '' || $nama_kriteria == '' || $atribut == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_kriteria SET kode_kriteria='$kode_kriteria', nama_kriteria='$nama_kriteria', atribut='$atribut' WHERE kode_kriteria='$_GET[ID]' AND tanggal='$PERIODE'");
        redirect_js("index.php?m=kriteria&periode=$PERIODE");
    }
} else if ($act == 'kriteria_hapus') {
    $db->query("DELETE FROM tb_rel_kriteria WHERE ID1='$_GET[ID]' OR ID2='$_GET[ID]' AND tanggal='$PERIODE'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE kode_kriteria='$_GET[ID]' AND tanggal='$PERIODE'");
    $db->query("DELETE FROM tb_kriteria WHERE kode_kriteria='$_GET[ID]' AND tanggal='$PERIODE'");
    header("location:index.php?m=kriteria&periode=$PERIODE");
}


/** RELASI ALTERNATIF */
else if ($mod == 'rel_alternatif_ubah') {
    foreach ($_POST['nilai'] as $key => $val) {
        $db->query("UPDATE tb_rel_alternatif SET nilai='$val' WHERE ID='$key' AND tanggal='$PERIODE'");
    }
    redirect_js("index.php?m=rel_kriteria&periode=$PERIODE");
}

/** RELASI KRITERIA */
else if ($mod == 'rel_kriteria') {
    $ID1 = $_POST['ID1'];
    $ID2 = $_POST['ID2'];
    $nilai = abs($_POST['nilai']);

    if ($ID1 == $ID2 && $nilai <> 1)
        print_msg("Kriteria yang sama harus bernilai 1.");
    else {
        $db->query("UPDATE tb_rel_kriteria SET nilai=$nilai WHERE ID1='$ID1' AND ID2='$ID2' AND tanggal='$PERIODE'");
        $db->query("UPDATE tb_rel_kriteria SET nilai=1/$nilai WHERE ID2='$ID1' AND ID1='$ID2' AND tanggal='$PERIODE'");
        print_msg("Nilai kriteria berhasil diubah.", 'success');
    }
}

/** PERIODE */
elseif ($mod == 'periode_tambah') {
    $tanggal = $_POST['tanggal'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];

    if ($tanggal == '' || $nama == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_periode WHERE tanggal='$tanggal'"))
        print_msg("tanggal sudah ada!");
    else {
        $db->query("INSERT INTO tb_periode (tanggal, nama, keterangan) VALUES ('$tanggal', '$nama', '$keterangan')");
        redirect_js("index.php?m=periode");
    }
} else if ($mod == 'periode_ubah') {
    $tanggal = $_POST['tanggal'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];

    if ($tanggal == '' || $nama == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_periode WHERE tanggal='$tanggal' AND tanggal<>'$_GET[ID]'"))
        print_msg("tanggal sudah ada!");
    else {
        $db->query("UPDATE tb_periode SET tanggal='$tanggal', nama='$nama', keterangan='$keterangan' WHERE tanggal='$_GET[ID]'");
        redirect_js("index.php?m=periode");
    }
} else if ($act == 'periode_hapus') {
    $tanggal = $_POST['tanggal'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];

    $kode_alternatif = $_POST['kode_alternatif'];
    $nama_alternatif = $_POST['nama_alternatif'];
    $keterangan = $_POST['keterangan'];

    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $atribut = $_POST['atribut'];

    $db->query("DELETE FROM tb_rel_alternatif WHERE tanggal='$_GET[ID]'");
    $db->query("DELETE FROM tb_alternatif WHERE tanggal='$_GET[ID]'");
    $db->query("DELETE FROM tb_kriteria WHERE tanggal='$_GET[ID]'");
    $db->query("DELETE FROM tb_rel_kriteria WHERE tanggal='$_GET[ID]'");

    $db->query("DELETE FROM tb_periode WHERE tanggal='$_GET[ID]'");
    header("location:index.php?m=periode");
}