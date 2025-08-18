<?= $this->include('partials/main') ?>
    <head>
        <?= $title_meta ?>
        <?= $this->include('partials/head-css') ?>
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
                                        <h4 class="card-title"><?= lang('Files.Detail_Komponen_Gaji') ?></h4>
                                        <p class="card-title-desc">Acuan penghitungan gaji karyawan, meliputi UMK sesuai ketetapan pemerintah dan tarif borongan per pcs untuk karyawan yang dibayar berdasarkan hasil kerja.</p>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="example-text-input" class="form-label"><?= lang('Files.UMK') ?></label>
                                                    <input type="text" class="form-control currency-rupiah-mask">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div>
                                                    <div class="mb-3">
                                                        <label for="example-text-input" class="form-label"><?= lang('Files.Tarif_Borongan') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
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
        <!-- form mask -->
        <script src="<?= base_url('assets/libs/imask/imask.min.js') ?>"></script>
        <!-- form mask init -->
        <script src="<?= base_url('assets/js/pages/form-mask.init.js') ?>"></script>
        <script src="<?= base_url('assets/js/app.js') ?>"></script>
    </body>
</html>