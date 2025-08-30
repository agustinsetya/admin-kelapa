<?= $this->include('partials/main') ?>

<head>

    <?= $title_meta ?>

    <?= $this->include('partials/head-css') ?>

    <link rel="stylesheet" href="<?= base_url('assets/libs/daterangepicker/daterangepicker.min.css') ?>">

</head>

<?= $this->include('partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('partials/menu') ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
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
                                        <form id="filter-Report-form" class="row g-2 align-items-end mb-0">
                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Gudang</label>
                                                <select name="gudang_id" id="gudang_id" class="form-select">
                                                    <option value="" selected>Semua Gudang</option> <!-- ⬅️ ALL -->
                                                    <?php foreach ($gudang as $g): ?>
                                                        <option value="<?= esc($g->m_gudang_id) ?>"><?= esc($g->nama) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Tanggal Pengolahan</label>
                                                <input type="text" class="form-control" id="tanggal_pengolahan" name="tanggal_pengolahan" placeholder="Tanggal Pengolahan">
                                            </div>

                                            <div class="col-12 col-md-4 d-flex gap-2 mt-3 mt-md-0">
                                                <button type="button" id="applyReportFilter" class="btn btn-primary w-100 text-nowrap">
                                                    <i class="bx bx-filter"></i> Apply Filter
                                                </button>
                                                <button type="button" id="resetReportFilter" class="btn btn-light w-100 text-nowrap">Reset</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="card-header">
                                <div class="row align-items-end g-2">
                                    <div class="col-md-6">
                                        <div class="col-md-6 mt-2 mt-md-0">
                                            <div class="col-12 col-md-auto">
                                                <h4 class="card-title mb-0">Laporan Hasil Olahan Daging dan Kopra</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart-scroll" class="chart-scroll">
                                    <div id="column_chart" data-colors='["#2ab57d", "#5156be", "#fd625e"]' class="apex-charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?= $this->include('partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?= $this->include('partials/right-sidebar') ?>

<?= $this->include('partials/vendor-scripts') ?>

<script src="<?= base_url('assets/libs/moment/moment.min.js') ?>"></script>

<script src="<?= base_url('assets/libs/daterangepicker/daterangepicker.min.js') ?>"></script>


<!-- apexcharts js -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- apexcharts init -->
<script src="assets/js/pages/apexcharts.init.js"></script>

<script src="assets/js/app.js"></script>

<!-- custom js -->
<script type="text/javascript">
    var base_url = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/content/report.js') ?>"></script>

</body>

</html>