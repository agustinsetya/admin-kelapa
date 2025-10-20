<?= $this->include('partials/main') ?>
<head>
    <?= $title_meta ?>

    <!-- DataTables -->
    <link href="<?= base_url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="<?= base_url('assets/libs/daterangepicker/daterangepicker.min.css') ?>">

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
                                    <div class="col-md-12">
                                        <form id="filter-Report-form" class="row g-2 align-items-end mb-0">
                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Tanggal Pengolahan</label>
                                                <input type="text" class="form-control" id="tanggal_pengolahan" name="tanggal_pengolahan" placeholder="Tanggal Pengolahan">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Gudang</label>
                                                <select name="gudang_id" id="gudang_id" class="form-select">
                                                    <option value="" selected>Semua Gudang</option> <!-- ⬅️ ALL -->
                                                    <?php foreach ($gudang as $g): ?>
                                                        <option value="<?= esc($g->m_gudang_id) ?>"><?= esc($g->nama) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-4 d-flex gap-2 mt-3 mt-md-0">
                                                <button type="button" id="applyReportPengolahanFilter" class="btn btn-primary w-100 text-nowrap">
                                                    <i class="bx bx-filter"></i> Apply Filter
                                                </button>
                                                <button type="button" id="resetReportPengolahanFilter" class="btn btn-light w-100 text-nowrap">Reset</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="card-header">
                                <div class="row align-items-end g-2">
                                    <div class="col-md-6">
                                        <div class="col-md-12 mt-2 mt-md-0">
                                            <div class="col-12 col-md-auto">
                                                <h4 class="card-title mb-0">Laporan Hasil Olahan Daging, Kopra, dan Kulit</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart-scroll" class="chart-scroll">
                                    <div id="column_chart" data-colors='["#2ab57d", "#5156be", "#fd625e"]' class="apex-charts" dir="ltr"></div>
                                </div><br />

                                <table class="table table-bordered dt-responsive nowrap w-100 dt-pengolahanTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal Pengolahan</th>
                                            <th>Nama Gudang</th>
                                            <th>Hasil Olahan</th>
                                            <th>Rendemen</th>
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

<script src="<?= base_url('assets/libs/moment/moment.min.js') ?>"></script>

<script src="<?= base_url('assets/libs/daterangepicker/daterangepicker.min.js') ?>"></script>

<!-- apexcharts js -->
<script src="<?= base_url('assets/libs/apexcharts/apexcharts.min.js') ?>"></script>

<!-- apexcharts init -->
<script src="<?= base_url('assets/js/pages/apexcharts.init.js') ?>"></script>

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

<script src="<?= base_url('assets/js/app.js') ?>"></script>

<!-- custom js -->
<script type="text/javascript">
    var base_url = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/content/general.js') ?>"></script>
<script src="<?= base_url('assets/js/content/report-pengolahan.js') ?>"></script>

</body>
</html>