<h1 class="fs-1">Decision Support System</h1>
<p>A decision support system (DSS) is a computerized program used to support determinations, judgments, and courses of action in an organization or a business. The system helps analysts to make decisions on risk mitigation.</p>

<div class="form-group d-flex mt-2">
    <div>
        <a class="btn border border-info" href="#"><span class="glyphicon glyphicon-book"></span> Guidebook</a>
    </div>
    <div>
        <a class="btn btn-info mx-2 text-white" href="?m=periode&periode=<?= _get('periode') ?>">
        <i class="bi bi-play-fill"></i> Start
        </a>
    </div>
</div>

<!-- Tambahkan div untuk shortcut -->
<div class="shortcut-container mt-4 mb-4">
    <h3>Quick Access</h3>
    <div class="row g-3">
        <?php
        // Ambil semua periode dari database
        $periods = $db->get_results("SELECT * FROM tb_periode ORDER BY tanggal DESC");
        
        if($periods): 
        foreach($periods as $period):
        ?>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= $period->nama ?></h5>
                    <p class="card-text text-muted small"><?= $period->tanggal ?></p>
                    <p class="card-text"><?= $period->keterangan ? $period->keterangan : 'No description' ?></p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="?m=alternatif&periode=<?= $period->tanggal ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-card-list"></i> Alternative
                        </a>
                        <a href="?m=kriteria&periode=<?= $period->tanggal ?>" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-card-checklist"></i> Criteria
                        </a>
                        <a href="?m=experts&periode=<?= $period->tanggal ?>" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-person"></i> Expert
                        </a>
                        <a href="?m=rel_kriteria&periode=<?= $period->tanggal ?>" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-box2"></i> Weight
                        </a>
                        <a href="?m=hitung&periode=<?= $period->tanggal ?>" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-file-earmark-bar-graph"></i> Result
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info">
                No files have been created yet. 
                <a href="?m=periode" class="alert-link">Create one now</a>.
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>