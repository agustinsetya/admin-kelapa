<?= $this->include('partials/main') ?>
    <head>
        <?= $title_meta ?>

        <!-- DataTables -->
        <link href="<?= base_url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/libs/daterangepicker/daterangepicker.min.css') ?>" rel="stylesheet">

        <!-- custom css -->
        <link href="<?= base_url('assets/css/content/general.css') ?>" rel="stylesheet" type="text/css" />
        
        <?= $this->include('partials/head-css') ?>
    </head>
    <?= $this->include('partials/body') ?>
        <div id="layout-wrapper">
            <?= $this->include('partials/menu') ?>
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <?= $page_title ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row align-items-end g-2">
                                            <div class="col-md-6">
                                                <form id="filter-report-gaji-driver-form" class="row g-2 align-items-end mb-0">
                                                    <div class="col-md-4">
                                                        <label class="form-label mb-1">Periode</label>
                                                        <input type="text" class="form-control" id="tg_proses_gaji_filter" name="tg_proses_gaji_filter" placeholder="Pilih Tanggal">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label mb-1">Gudang</label>
                                                        <select name="rp_gudang_id" id="rp_gudang_id" class="form-select">
                                                            <option value="" disabled selected>Pilih Gudang</option>
                                                            <?php foreach ($gudang as $g): ?>
                                                                <option value="<?= esc($g->m_gudang_id) ?>"><?= esc($g->nama) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-4 d-flex gap-2 mt-3 mt-md-0">
                                                        <button type="button" id="applyReportGajiDriverFilter" class="btn btn-primary w-100 text-nowrap">
                                                            <i class="bx bx-filter"></i> Apply Filter
                                                        </button>
                                                        <button type="button" id="resetReportGajiDriverFilter" class="btn btn-light w-100 text-nowrap">Reset</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered dt-responsive nowrap w-100 dt-reportGajiDriverTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal Proses Gaji</th>
                                                    <th>Nama</th>
                                                    <th>Gudang</th>
                                                    <th>Total Upah Perjalanan</th>
                                                    <th>Bonus</th>
                                                    <th>Gaji Bersih</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->include('partials/footer') ?>
            </div>
        </div>
        <?= $this->include('partials/right-sidebar') ?>
        <?= $this->include('partials/vendor-scripts') ?>

        <!-- Required datatable js -->
        <script src="<?= base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
        <!-- Buttons -->
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/jszip/jszip.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/buttons.print.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') ?>"></script>
        <!-- Datatable init js -->
        <script src="<?= base_url('assets/js/pages/datatables.init.js') ?>"></script>
        <script src="<?= base_url('assets/js/app.js') ?>"></script>
        <!-- Responsive table -->
        <script src="<?= base_url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') ?>"></script>
        <!-- Daterange -->
        <script src="<?= base_url('assets/libs/moment/moment.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/daterangepicker/daterangepicker.min.js') ?>"></script>
        <!-- form mask -->
        <script src="<?= base_url('assets/libs/imask/imask.min.js') ?>"></script>
        <!-- form mask init -->
        <script src="<?= base_url('assets/js/pages/form-mask.init.js') ?>"></script>

        <!-- custom js -->
        <script type="text/javascript">
            var base_url = '<?= base_url() ?>';
        </script>
        <script src="<?= base_url('assets/js/content/general.js') ?>"></script>
        <script src="<?= base_url('assets/js/content/report-gaji-driver.js') ?>"></script>
    </body>
</html>