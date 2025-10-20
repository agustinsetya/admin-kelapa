<?= $this->include('partials/main') ?>
    <head>
        <?= $title_meta ?>

        <link href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />

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
                                        <p class="card-title-desc">Acuan penghitungan gaji Karyawan, berdasarkan hasil kerja pengolahan daging, kopra, dan kulit per takaran. Serta acuan perhitungan gaji Driver.</p>
                                    </div>
                                    <form class="needs-validation" id="detail-master-gudang-form" novalidate>
                                        <div class="card-body p-4">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="nama_gudang" class="form-label"><?= lang('Files.Nama_Gudang') ?></label>
                                                        <input type="text" name="nama_gudang" id="nama_gudang" maxlength="100" required class="form-control" placeholder="<?= lang('Files.Input_Nama_Gudang') ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="takaran_daging_kelapa" class="form-label"><?= lang('Files.Takaran_Daging') ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" name="takaran_daging_kelapa" id="takaran_daging_kelapa" min="0.1" step="0.001" data-pristine-min-message="Takaran Daging min. 0.1 Kg" required placeholder="<?= lang('Files.Input_Berat') ?>">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="upah_takaran_daging" class="form-label"><?= lang('Files.Upah_Daging') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="upah_takaran_daging" id="upah_takaran_daging" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="takaran_kopra_kelapa" class="form-label"><?= lang('Files.Takaran_Kopra') ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" name="takaran_kopra_kelapa" id="takaran_kopra_kelapa" min="0.1" step="0.001" data-pristine-min-message="Takaran Kopra min. 0.1 Kg" required placeholder="<?= lang('Files.Input_Berat') ?>">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="upah_takaran_kopra" class="form-label"><?= lang('Files.Upah_Kopra') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="upah_takaran_kopra" id="upah_takaran_kopra" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="takaran_kulit_kelapa" class="form-label"><?= lang('Files.Takaran_Kulit') ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" name="takaran_kulit_kelapa" id="takaran_kulit_kelapa" min="0.1" step="0.001" data-pristine-min-message="Takaran Kulit min. 0.1 Kg" required placeholder="<?= lang('Files.Input_Berat') ?>">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="upah_takaran_kulit" class="form-label"><?= lang('Files.Upah_Kulit') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="upah_takaran_kulit" id="upah_takaran_kulit" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="gaji_driver_distribusi" class="form-label"><?= lang('Files.Gaji_Driver_Distribusi') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="gaji_driver_distribusi" id="gaji_driver_distribusi" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="gaji_driver_ngepok_truk" class="form-label"><?= lang('Files.Gaji_Driver_Ngepok_Truk') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="gaji_driver_ngepok_truk" id="gaji_driver_ngepok_truk" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="gaji_driver_ngepok_pickup" class="form-label"><?= lang('Files.Gaji_Driver_Ngepok_Pickup') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="gaji_driver_ngepok_pickup" id="gaji_driver_ngepok_pickup" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="gaji_pacak_mesin" class="form-label"><?= lang('Files.Gaji_Pacak_Mesin') ?></label>
                                                        <input type="text" class="form-control currency-rupiah-mask" name="gaji_pacak_mesin" id="gaji_pacak_mesin" placeholder="<?= lang('Files.Input_Upah') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" class="btn btn-primary text-nowrap w-100 w-md-auto waves-effect waves-light" id="btn-save-detail-gudang"><?= lang('Files.Save') ?></button>
                                        </div>
                                    </form>
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

        <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

        <!-- custom js -->
        <script type="text/javascript">
            var base_url = '<?= base_url() ?>';
            var roleScope = '<?= $roleScope ?>';
            var penempatan = '<?= isset($penempatan) ? $penempatan : '' ?>';
        </script>
        <script src="<?= base_url('assets/js/content/general.js') ?>"></script>
        <script src="<?= base_url('assets/js/content/master-detail-gudang.js') ?>"></script>
    </body>
</html>