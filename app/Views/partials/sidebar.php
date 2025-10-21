<?php $userRole = session()->get('role'); ?>
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu"><?= lang('Files.Menu') ?></li>

                <li>
                    <a href="<?= url_to('dashboard') ?>">
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
                        <?php if ($userRole == 1): ?>
                        <li>
                            <a href="<?= url_to('master-user-roles') ?>">
                                <span data-key="t-master-user-roles"><?= lang('Files.User_Roles') ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?= url_to('master-pegawai') ?>">
                                <span data-key="t-master-pegawai"><?= lang('Files.Pegawai') ?></span>
                            </a>
                        </li>
                        <?php if ($userRole == 1): ?>
                        <li>
                            <a href="<?= url_to('master-user') ?>">
                                <span data-key="t-master-user"><?= lang('Files.User') ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?= url_to('master-gudang') ?>">
                                <span data-key="t-master-gudang"><?= lang('Files.Gudang') ?></span>
                            </a>
                        </li>
                        <?php if ($userRole == 1): ?>
                        <li>
                            <a href="<?= url_to('master-kategori-pengeluaran') ?>">
                                <span data-key="t-master-kategori-pengeluaran"><?= lang('Files.Kategori_Pengeluaran') ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
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
                            <a href="<?= url_to('supply-pembelian') ?>">
                                <span data-key="t-supply-pembelian"><?= lang('Files.Data_Pembelian') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('supply-pacak-mesin') ?>">
                                <span data-key="t-supply-pacak-mesin"><?= lang('Files.Data_Pacak_Mesin') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('supply-pengolahan') ?>">
                                <span data-key="t-supply-pengolahan"><?= lang('Files.Data_Pengolahan') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('supply-pengiriman') ?>">
                                <span data-key="t-supply-pengiriman"><?= lang('Files.Data_Pengiriman') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('supply-penjualan') ?>">
                                <span data-key="t-supply-penjualan"><?= lang('Files.Data_Penjualan') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('supply-penjualan-limbah') ?>">
                                <span data-key="t-supply-penjualan-limbah"><?= lang('Files.Data_Penjualan_Limbah') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="gift"></i>
                        <span data-key="t-finance"><?= lang('Files.Finance') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="<?= url_to('finance-pengeluaran') ?>">
                                <span data-key="t-pengeluaran"><?= lang('Files.Pengeluaran') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('finance-kasbon') ?>">
                                <span data-key="t-kasbon"><?= lang('Files.Kasbon') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('finance-gaji-driver') ?>">
                                <span data-key="t-gaji-driver"><?= lang('Files.Gaji_Driver') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('finance-gaji-pegawai') ?>">
                                <span data-key="t-gaji-pegawai"><?= lang('Files.Gaji_Pegawai') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <?php if ($userRole == 1): ?>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="pie-chart"></i>
                        <span data-key="t-report"><?= lang('Files.Report') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="<?= url_to('report-pengolahan') ?>">
                                <span data-key="t-report-pengolahan"><?= lang('Files.Data_Pengolahan') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('report-komponen-gaji') ?>">
                                <span data-key="t-report-komponen-gaji"><?= lang('Files.Komponen_Gaji') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('report-gaji-driver') ?>">
                                <span data-key="t-report-gaji-driver"><?= lang('Files.Gaji_Driver') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('report-gaji-pegawai') ?>">
                                <span data-key="t-report-gaji-pegawai"><?= lang('Files.Gaji_Pegawai') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>