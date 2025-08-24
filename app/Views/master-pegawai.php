<?= $this->include('partials/main') ?>

    <head>
        
        <?= $title_meta ?>

        <!-- DataTables -->
        <link href="<?= base_url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="<?= base_url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" /> 

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
                                        <form id="filter-form" class="row g-2 align-items-end mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Gudang</label>
                                                <select name="gudang_id" id="gudang_id" class="form-select">
                                                    <option value="" disabled selected>Pilih Gudang</option>
                                                    <?php foreach ($gudang as $g): ?>
                                                        <option value="<?= esc($g->m_gudang_id) ?>"><?= esc($g->nama) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Role</label>
                                                <select name="role_id" id="role_id" class="form-select">
                                                    <option value="" read-only>Pilih Role</option>
                                                    <?php foreach ($userRole as $r): ?>
                                                        <option value="<?= esc($r->m_role_id) ?>"><?= esc($r->nama) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4 d-flex gap-2">
                                                <button type="button" id="applyPegawaiFilter" class="btn btn-primary">
                                                    <i class="mdi mdi-filter"></i> Apply Filter
                                                </button>
                                                <!-- <button type="button" id="btn-reset" class="btn btn-light">Reset</button> -->
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 dt-pegawaiTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Kode Pegawai</th>
                                                    <th>Nama</th>
                                                    <th>Role</th>
                                                    <th>Gudang</th>
                                                    <th>Email</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- end cardaa -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
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

        <!-- Required datatable js -->
        <script src="<?= base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
        <!-- Buttons examples -->
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/jszip/jszip.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/pdfmake/build/pdfmake.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/pdfmake/build/vfs_fonts.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/buttons.print.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') ?>"></script>

        <!-- Responsive examples -->
        <script src="<?= base_url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') ?>"></script>

        <!-- Datatable init js -->
        <script src="<?= base_url('assets/js/pages/datatables.init.js') ?>"></script>    

        <script src="<?= base_url('assets/js/app.js') ?>"></script>

        <!-- custom js -->
        <script type="text/javascript">
            var base_url = '<?= base_url() ?>';
        </script>
        <script src="<?= base_url('assets/js/content/master-pegawai.js') ?>"></script>
    </body>
</html>