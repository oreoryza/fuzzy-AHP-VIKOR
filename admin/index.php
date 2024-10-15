<?php
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" href="assets/img/icon.png" />

	<title>Sistem Pendukung Keputusan</title>
	<link href="assets/css/default-bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/general.css" rel="stylesheet" />
	
	<style>
		
		*{
			margin:0;
			padding:0;
			box-sizing: border-box;
			font-family: helvetica;
		}

		.navbar{
			position: flex;
			top: 0;
			left:0;
			padding:20px 30px;
			background: white;
			display: relative;
			align-items: center;
			border-radius: 0;
			margin-bottom: 40px;
			box-shadow: rgba(0, 0, 0, 0.10) 0px 5px 15px;;
		}

		.navbar a{
			font-size: 16px;
			color: #333333;
			font-weight: 500;
			text-decoration: none;
			margin-left: 10px;
			margin-right: 20px;
			margin-bottom: 0px;
			cursor: pointer;
			transition-duration: 0.4s;
		}

		.navbar a:hover{
			color: #337AB7;
		}

		.navbar a::before{
			content:'';
			position: absolute;
			top: 100%;
			left: 0;
			width: 0;
			height: 2px;
			background: #337AB7;
			transition: .5s;
		}

		.navbar a:hover::before{
			width: 100%;
		}

		.navbar a span{
			color: #337AB7;
		}

	</style>
</head>

<body>
	<header class="header">
	<nav class="navbar">
		<a href="?m=home"><img src="assets/img/icon.png" style="width:2%; margin-top:-5px"></img></a>
		<a class="?m=periode" href="?m=periode&periode=<?= _get('periode') ?>"><span class="glyphicon glyphicon-file"></span> File</a>
		<a class="?m=alternatif" href="?m=alternatif&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-th-list"></span> Alternative</a>
		<a class="?m=kriteria" href="?m=kriteria&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-th-large"></span> Criteria</a>
		<a class="?m=experts" href="?m=experts&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-user"></span> Experts</a>
		<a class="?m=rel_kriteria" href="?m=rel_kriteria&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-tasks"></span> Weight</a>
		<a class="?m=hitung" href="?m=hitung&periode=<?= _get('periode')?>"><span class="glyphicon glyphicon-stats"></span> Result</a>
		<a href="?m=logout" class="nav navbar-right" style="margin-right: 10px"><span class="glyphicon glyphicon-log-out"></span></a>
	</nav>
	</header>
	<div class="container">
    <?php
    if (file_exists($mod . '.php')) {
      if (!in_array($mod, ['periode', 'periode_cetak', 'periode_tambah', 'periode_ubah'])) {
        // cek periode
        if (is_null(_get('periode'))) {
          $row = $db->get_row("SELECT * FROM tb_periode order by tanggal desc limit 1");
          if (is_null($row)) {
            // jika periode belum ada
            redirect_js("index.php?m=periode");
          } else {
            // lempar jika periode tidak valid ke periode terbaru
            redirect_js("index.php?m=$mod&periode=$row->tanggal");
          }
          die;
        }

        // jika parameter periode ada
        $row = $db->get_row("SELECT * FROM tb_periode WHERE tanggal='" . _get('periode') . "'");
        if (is_null($row)) {
          // jika periode tidak valid
          $row = $db->get_row("SELECT * FROM tb_periode order by tanggal desc limit 1");

          if (is_null($row)) {
            // jika periode belum ada
            redirect_js("index.php?m=periode");
          } else {
            // lempar jika periode tidak valid ke periode terbaru
            redirect_js("index.php?m=$mod&periode=$row->tanggal");
          }
        }
      }

      $PERIODE = _get('periode');
      include $mod . '.php';
    } else {
      include 'home.php';
    }
    ?>
  </div>
  <footer class="footer bg-light">
		<div class="container">
			<p>Copyright &copy; <?= date('Y') ?> <em class="pull-right"></em></p>
		</div>
	</footer>
</body>

</html>