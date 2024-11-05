<?php
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" href="assets/img/icon.png" />
	<title>Sistem Pendukung Keputusan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
      integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="d-flex justify-content-between page-size">
    <aside id="sidebar">
            <div class="position-fixed">
                <div class="d-flex">
                    <button class="toggle-btn" type="button">
                    <i class="bi bi-list"></i>
                    </button>
                    <div class="sidebar-logo">
                        <a href="index.php?m=home">DSS</a>
                    </div>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="index.php?m=home" class="sidebar-link" title="Home">
                            <i class="bi bi-house"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="?m=periode&periode=<?= _get('periode') ?>" class="sidebar-link" title="File">
                            <i class="bi bi-archive"></i>
                            <span>File</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="?m=alternatif&periode=<?= _get('periode')?>" class="sidebar-link" title="Alternative">
                            <i class="bi bi-card-list"></i>
                            <span>Alternative</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="?m=kriteria&periode=<?= _get('periode')?>" class="sidebar-link" title="Criteria">
                            <i class="bi bi-card-checklist"></i>
                            <span>Criteria</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="?m=experts&periode=<?= _get('periode')?>" class="sidebar-link" title="Expert">
                            <i class="bi bi-person"></i>
                            <span>Expert</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="?m=rel_kriteria&periode=<?= _get('periode')?>" class="sidebar-link collapsed has-dropdown"
                            data-bs-target="#auth" aria-expanded="false" aria-controls="auth" title="Weight">
                            <i class="bi bi-box2"></i>
                            <span>Weight</span>
                        </a>
                        <ul id="auth" class="sidebar-dropdown">
                            <li class="sidebar-item">
                                <a href="?m=rel_kriteria&periode=<?= _get('periode')?>" class="sidebar-link"><i class="bi bi-align-end"></i>Criteria</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="?m=rel_alternatif&periode=<?= _get('periode')?>" class="sidebar-link"><i class="bi bi-align-end"></i>Alternative</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="?m=hitung&periode=<?= _get('periode')?>" class="sidebar-link">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Result</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-footer">
                    <a href="logout.php" class="sidebar-link">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </aside>

        <div class="d-flex flex-column p-5 main-size">
        <?php
        if (file_exists($mod . '.php')) {
          if (!in_array($mod, ['periode', 'periode_cetak', 'periode_tambah', 'periode_ubah', 'home'])) {
              // Check periode
              if (is_null(_get('periode'))) {
                  $row = $db->get_row("SELECT * FROM tb_periode ORDER BY tanggal DESC LIMIT 1");
                  if (is_null($row)) {
                      // If periode doesn't exist
                      redirect_js("index.php?m=periode");
                  } else {
                      // Redirect to the latest periode if not valid
                      redirect_js("index.php?m=$mod&periode=$row->tanggal");
                  }
                  die;
              }
          }

          $PERIODE = _get('periode');
          include $mod . '.php';
      } else {
          include 'home.php';
      }
      ?>
        </div>
    </div>

  <script src="js/main.js"></script>
</body>

</html>