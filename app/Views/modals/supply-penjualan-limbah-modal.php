<div id="supplyPenjualanLimbahModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="supply-penjualan-limbah-form" novalidate>
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
                                <select name="penj_gudang_id" id="penj_gudang_id" class="form-select">
                                    <option value="" disabled selected>Pilih Gudang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="jenis_limbah" class="form-label">Jenis Limbah</label>
                                <select name="jenis_limbah" id="jenis_limbah" class="form-select">
                                    <option value="" disabled selected>Pilih Jenis Limbah</option>
                                    <option value="AIR_KELAPA">Air Kelapa</option>
                                    <option value="BATOK_KELAPA">Batok Kelapa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="berat_limbah" class="form-label"><?= lang('Files.Berat_Limbah') ?></label>
                                <div class="input-group">
                                    <input type="number" name="berat_limbah" id="berat_limbah" min="0.1" step="0.001" data-pristine-min-message="Berat Limbah min. 0.1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="pendapatan_limbah" class="form-label"><?= lang('Files.Pendapatan_Limbah') ?></label>
                                <input type="text" class="form-control currency-rupiah-mask" name="pendapatan_limbah" id="pendapatan_limbah" placeholder="Masukkan Pendapatan">
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
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-penjualan-limbah"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>