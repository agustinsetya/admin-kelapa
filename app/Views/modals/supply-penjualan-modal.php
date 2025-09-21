<div id="supplyPenjualanModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="supply-penjualan-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="tg_penjualan" class="form-label">Tanggal Penjualan</label>
                                <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" name="tg_penjualan" id="tg_penjualan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Gudang</label>
                                <select name="penj_gudang_id" id="penj_gudang_id" class="form-select" <?= $roleScope == 'gudang' ? 'disabled' : '' ?>>
                                    <option value="" disabled selected>Pilih Gudang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="log_pengiriman_id" class="form-label">Nomor Resi</label>
                                <select name="log_pengiriman_id" id="log_pengiriman_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih Nomor Resi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="daging_kelapa_terima" class="form-label"><?= lang('Files.Berat_Daging_Diterima') ?></label>
                                <div class="input-group">
                                    <input type="number" name="daging_kelapa_terima" id="daging_kelapa_terima" min="0.1" step="0.001" data-pristine-min-message="Berat Daging min. 0.1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="daging_kelapa_reject" class="form-label"><?= lang('Files.Berat_Daging_Ditolak') ?></label>
                                <div class="input-group">
                                    <input type="number" name="daging_kelapa_reject" id="daging_kelapa_reject" min="0.1" step="0.001" data-pristine-min-message="Berat Daging min. 0.1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="penj_status" class="form-label">Status</label>
                                <select name="penj_status" id="penj_status" class="form-select">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="BELUM_LUNAS">Belum Lunas</option>
                                    <option value="LUNAS">Lunas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-penjualan"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>