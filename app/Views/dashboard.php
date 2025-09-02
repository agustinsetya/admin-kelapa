<?= $this->include('partials/main') ?>

    <head>

        <?= $title_meta ?>

        <!-- plugin css -->
        <link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

        <?= $this->include('partials/head-css') ?>

    </head>

        <?= $this->include('partials/body') ?>

        <!-- Begin page -->
        <div id="layout-wrapper">

            <?= $this->include('partials/menu') ?>
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <?= $page_title ?>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Selamat Datang, <b> <?= session()->get('user')->nama_pegawai ?> 👋🏻 </b></h3>
                                    </div><!-- end card header -->

                                    <div class="card-body">
                                        
                                        <div class="row justify-content-center">
                                            <div class="col-xl-10">
                                                <div class="timeline">
                                                    <div class="timeline-container">
                                                        <div class="timeline-end">
                                                            <p>Start</p>
                                                        </div>
                                                        <div class="timeline-continue">
                                                            <div class="row timeline-right">
                                                                <div class="col-md-6">
                                                                    <div class="timeline-icon">
                                                                        <i class="bx bx-briefcase-alt-2 text-primary h2 mb-0"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="timeline-box">
                                                                        <div class="timeline-date bg-primary text-center rounded">
                                                                            <h6 class="text-white mb-0">2015</h6>
                                                                            <p class="mb-0 text-white-50">June</p>
                                                                        </div>
                                                                        <div class="event-content">
                                                                            <div class="timeline-text">
                                                                                <h3 class="font-size-18">Perusahaan Didirikan</h3>
                                                                                <p class="mb-0 mt-2 pt-1 text-muted">Perusahaan kami resmi berdiri di tahun 2015 dengan fokus awal pada distribusi bahan baku kelapa.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                
                                                            <div class="row timeline-left">
                                                                <div class="col-md-6 d-md-none d-block">
                                                                    <div class="timeline-icon">
                                                                        <i class="bx bx-user-pin text-primary h2 mb-0"></i>
                                                                        </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="timeline-box">
                                                                        <div class="timeline-date bg-primary text-center rounded">
                                                                            <h6 class="text-white mb-0">2017</h6>
                                                                            <p class="mb-0 text-white-50">June</p>
                                                                        </div>
                                                                        <div class="event-content">
                                                                            <div class="timeline-text">
                                                                                <h3 class="font-size-18">Ekspansi Gudang Pertama</h3>
                                                                                <p class="mb-0 mt-2 pt-1 text-muted">Membuka gudang operasional pertama di wilayah Kab. Malang untuk mendukung peningkatan volume produksi.</p>
                            
                                                                                <div class="d-flex flex-wrap align-items-start event-img mt-3 gap-2">
                                                                                    <img src="assets/images/small/img-2.jpg" alt=""
                                                                                        class="img-fluid rounded" width="60">
                                                                                    <img src="assets/images/small/img-5.jpg" alt=""
                                                                                        class="img-fluid rounded" width="60">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 d-md-block d-none">
                                                                    <div class="timeline-icon">
                                                                        <i class="bx bx-user-pin text-primary h2 mb-0"></i>
                                                                        </div>
                                                                </div>
                                                            </div>

                                                            <div class="row timeline-right">
                                                                <div class="col-md-6">
                                                                    <div class="timeline-icon">
                                                                        <i class="bx bx-bar-chart-square text-primary h2 mb-0"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="timeline-box">
                                                                        <div class="timeline-date bg-primary text-center rounded">
                                                                            <h6 class="text-white mb-0">2023</h6>
                                                                            <p class="mb-0 text-white-50">Des</p>
                                                                        </div>
                                                                        <div class="event-content">
                                                                            <div class="timeline-text">
                                                                                <h3 class="font-size-18">Produksi Mandiri</h3>
                                                                                <p class="mb-0 mt-2 pt-1 text-muted">Memulai lini produksi internal untuk meningkatkan kualitas dan efisiensi.</p>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row timeline-left">
                                                                <div class="col-md-6 d-md-none d-block">
                                                                    <div class="timeline-icon">
                                                                        <i class="bx bx-camera text-primary h2 mb-0"></i>
                                                                        </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="timeline-box">
                                                                        <div class="timeline-date bg-primary text-center rounded">
                                                                            <h6 class="text-white mb-0">2025</h6>
                                                                            <p class="mb-0 text-white-50">August</p>
                                                                        </div>
                                                                        <div class="event-content">
                                                                            <div class="timeline-text">
                                                                                <h3 class="font-size-18">Digitalisasi Sistem</h3>
                                                                                <p class="mb-0 mt-2 pt-1 text-muted">Menerapkan sistem ERP internal untuk mengelola proses produksi dan distribusi secara efisien.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 d-md-block d-none">
                                                                    <div class="timeline-icon">
                                                                        <i class="bx bx-camera text-primary h2 mb-0"></i>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-start">
                                                            <p>End</p>
                                                        </div>
                                                        <div class="timeline-launch">
                                                            <div class="timeline-box">
                                                                <div class="timeline-text">
                                                                    <h3 class="font-size-18">Perusahaan kami diresmikan pada 21 Juni 2021</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
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

        <!-- apexcharts -->
        <script src="<?= base_url('assets/libs/apexcharts/apexcharts.min.js') ?>"></script>

        <!-- Plugins js-->
        <script src="<?= base_url('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') ?>"></script>
        <!-- dashboard init -->
        <script src="<?= base_url('assets/js/pages/dashboard.init.js') ?>"></script>

        <script src="<?= base_url('assets/js/app.js') ?>"></script>

    </body>

</html>