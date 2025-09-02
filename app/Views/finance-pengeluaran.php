<?= $this->include('partials/main') ?>
    <head>
        <?= $title_meta ?>

        <!-- DataTables -->
        <link href="<?= base_url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/libs/daterangepicker/daterangepicker.min.css') ?>" rel="stylesheet">

        <link href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />

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
                                                <form id="filter-pengeluaran-form" class="row g-2 align-items-end mb-0">
                                                    <div class="col-md-4">
                                                        <label class="form-label mb-1">Tanggal Pengeluaran</label>
                                                        <input type="text" class="form-control" id="tg_pengeluaran_filter" name="tg_pengeluaran_filter" placeholder="Pilih Tanggal">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label mb-1">Gudang</label>
                                                        <select name="fn_gudang_id" id="fn_gudang_id" class="form-select">
                                                            <option value="" disabled selected>Pilih Gudang</option>
                                                            <?php foreach ($gudang as $g): ?>
                                                                <option value="<?= esc($g->m_gudang_id) ?>"><?= esc($g->nama) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-4 d-flex gap-2 mt-3 mt-md-0">
                                                        <button type="button" id="applyPengeluaranFilter" class="btn btn-primary w-100 text-nowrap">
                                                            <i class="bx bx-filter"></i> Apply Filter
                                                        </button>
                                                        <button type="button" id="resetPengeluaranFilter" class="btn btn-light w-100 text-nowrap">Reset</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-6 mt-2 mt-md-0">
                                                <div class="row justify-content-md-end">
                                                    <div class="col-12 col-md-auto">
                                                        <button type="button"
                                                                id="btn-tambah-pengeluaran"
                                                                class="btn btn-success w-100 text-nowrap"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#financePengeluaranModal">
                                                            <i class="bx bx-plus me-1"></i>Tambah
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered dt-responsive nowrap w-100 dt-pengeluaranTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal Pengeluaran</th>
                                                    <th>Kategori</th>
                                                    <th>Gudang</th>
                                                    <th>Jumlah</th>
                                                    <th>Biaya</th>
                                                    <th>Nama Pegawai</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Include the modal -->
                                    <?= $this->include('modals/finance-pengeluaran-modal') ?>
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

        <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

        <!-- custom js -->
        <script type="text/javascript">
            var base_url = '<?= base_url() ?>';
        </script>
        <script src="<?= base_url('assets/js/content/general.js') ?>"></script>
        <script src="<?= base_url('assets/js/content/finance-pengeluaran.js') ?>"></script>
    </body>
</html>