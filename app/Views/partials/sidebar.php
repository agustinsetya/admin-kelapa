<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu"><?= lang('Files.Menu') ?></li>

                <li>
                    <a href="dashboard">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard"><?= lang('Files.Dashboard') ?></span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid"></i>
                        <span data-key="t-master"><?= lang('Files.Data_Utama') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="<?= route_to('master-komponen-gaji') ?>">
                                <span data-key="t-master-komponen-gaji"><?= lang('Files.Komponen_Gaji') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-title mt-2" data-key="t-components"><?= lang('Files.Elements') ?></li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="box"></i>
                        <span data-key="t-supply-chain"><?= lang('Files.Supply_Chain') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="<?= route_to('supply-view-pembelian') ?>">
                                <span data-key="t-supply-pembelian"><?= lang('Files.Data_Pembelian') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= route_to('supply-view-pengolahan') ?>">
                                <span data-key="t-supply-pengolahan"><?= lang('Files.Data_Pengolahan') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="gift"></i>
                        <span data-key="t-payroll"><?= lang('Files.Payroll') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="<?= route_to('payroll-view-absensi') ?>">
                                <span data-key="t-absensi"><?= lang('Files.Absensi') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= route_to('payroll-view-lembur') ?>">
                                <span data-key="t-lembur"><?= lang('Files.Lembur') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= route_to('payroll-view-penggajian') ?>">
                                <span data-key="t-penggajian"><?= lang('Files.Penggajian') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="">
                        <i data-feather="pie-chart"></i>
                        <span data-key="t-report"><?= lang('Files.Report') ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->