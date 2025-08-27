<?= $this->include('partials/main') ?>
    <head>
        <?= $title_meta ?>
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
                                        <h4 class="card-title"><?= lang('Files.Detail_Komponen_Gaji') ?></h4>
                                        <p class="card-title-desc">Acuan penghitungan gaji karyawan, berdasarkan hasil kerja pengolahan daging dan kopra per takaran.</p>
                                    </div>
                                    <div class="card-body p-4">
                                        <form class="needs-validation" id="detail-komponen-gaji-form">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="takaran_daging_kelapa" class="form-label"><?= lang('Files.Takaran_Daging') ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" name="takaran_daging_kelapa" id="takaran-daging-kelapa" placeholder="<?= lang('Files.Input_Berat') ?>">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="upah_takaran_daging" class="form-label"><?= lang('Files.Upah_Daging') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="upah_takaran_daging" id="upah-takaran-daging-kelapa" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="takaran_kopra_kelapa" class="form-label"><?= lang('Files.Takaran_Kopra') ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" name="takaran_kopra_kelapa" id="takaran-kopra-kelapa" placeholder="<?= lang('Files.Input_Berat') ?>">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="upah_takaran_kopra" class="form-label"><?= lang('Files.Upah_Kopra') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="upah_takaran_kopra" id="upah-takaran-kopra-kelapa" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3 text-end">
                                                        <button type="button" class="btn btn-primary" id="btn-save-komponen-gaji"><?= lang('Files.Update') ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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

        <!-- form mask -->
        <script src="<?= base_url('assets/libs/imask/imask.min.js') ?>"></script>
        <!-- form mask init -->
        <script src="<?= base_url('assets/js/pages/form-mask.init.js') ?>"></script>
        <script src="<?= base_url('assets/js/app.js') ?>"></script>

        <!-- custom js -->
        <script type="text/javascript">
            var base_url = '<?= base_url() ?>';
            var roleScope = '<?= $roleScope ?>';
        </script>
        <script src="<?= base_url('assets/js/content/master-komponen-gaji.js') ?>"></script>
    </body>
</html>