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
                                        <p class="card-title-desc">Pencatatan transaksi masuk kelapa dari pemasok dengan detail berupa daging, kulit, dan kopra sesuai hasil pemisahan.</p>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="tanggal-pembelian" class="form-label"><?= lang('Files.Date') ?></label>
                                                    <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" id="tanggal-pembelian">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="berat-daging-kelapa" class="form-label"><?= lang('Files.Daging_Kelapa') ?></label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" id="berat-daging-kelapa" placeholder="<?= lang('Files.Input_Berat') ?>">
                                                        <span class="input-group-text">Kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="berat-kulit-kelapa" class="form-label"><?= lang('Files.Kulit_Kelapa') ?></label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" id="berat-kulit-kelapa" placeholder="<?= lang('Files.Input_Berat') ?>">
                                                        <span class="input-group-text">Kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="berat-kopra" class="form-label"><?= lang('Files.Kopra') ?></label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" id="berat-kopra" placeholder="<?= lang('Files.Input_Berat') ?>">
                                                        <span class="input-group-text">Kg</span>
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
        <script src="<?= base_url('assets/js/app.js') ?>"></script>
    </body>
</html>