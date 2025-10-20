<?= $this->include('partials/main') ?>
    <head>
        <?= $title_meta ?>
        <!-- DataTables -->
        <link href="<?= base_url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />

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
                                        <p class="card-title-desc">Record perubahan acuan penghitungan gaji karyawan, berdasarkan hasil kerja pengolahan daging dan kopra per takaran. Selain itu acuan untuk gaji driver.</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered dt-responsive nowrap w-100 dt-komponenGajiTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Last Update</th>
                                                    <th>Nama Gudang</th>
                                                    <th>Takaran Daging (kg)</th>
                                                    <th>Upah per Takaran Daging</th>
                                                    <th>Takaran Kopra (kg)</th>
                                                    <th>Upah per Takaran Kopra</th>
                                                    <th>Takaran Kulit (kg)</th>
                                                    <th>Upah per Takaran Kulit</th>
                                                    <th>Gaji Driver</th>
                                                    <th>Gaji Pacak Mesin</th>
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

        <!-- form mask -->
        <script src="<?= base_url('assets/libs/imask/imask.min.js') ?>"></script>
        <!-- form mask init -->
        <script src="<?= base_url('assets/js/pages/form-mask.init.js') ?>"></script>

        <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

        <!-- custom js -->
        <script type="text/javascript">
            var base_url = '<?= base_url() ?>';
            var roleScope = '<?= $roleScope ?>';
        </script>
        <script src="<?= base_url('assets/js/content/general.js') ?>"></script>
        <script src="<?= base_url('assets/js/content/report-komponen-gaji.js') ?>"></script>
    </body>
</html>