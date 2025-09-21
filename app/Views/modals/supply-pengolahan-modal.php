<div id="supplyPengolahanModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="supply-pengolahan-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="tg_pengolahan" class="form-label">Tanggal Pengolahan</label>
                                <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" name="tg_pengolahan" id="tg_pengolahan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Gudang</label>
                                <select name="peng_gudang_id" id="peng_gudang_id" class="form-select">
                                    <option value="" disabled selected>Pilih Gudang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Pegawai</label>
                                <select name="peng_pegawai_id" id="peng_pegawai_id" class="form-select">
                                    <option value="" disabled selected>Pilih Pegawai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Kode Container</label>
                                <select name="peng_kode_container" id="peng_kode_container" class="form-select">
                                    <option value="" disabled selected>Pilih Kode Container</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="berat_daging" class="form-label"><?= lang('Files.Berat_Daging') ?></label>
                                <div class="input-group">
                                    <input type="number" name="berat_daging" id="berat_daging" min="0.1" step="0.001" data-pristine-min-message="Berat Daging min. 0.1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="berat_kopra" class="form-label"><?= lang('Files.Berat_Kopra') ?></label>
                                <div class="input-group">
                                    <input type="number" name="berat_kopra" id="berat_kopra" min="0.1" step="0.001" data-pristine-min-message="Berat Kopra min. 0.1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="berat_kulit" class="form-label"><?= lang('Files.Berat_Kulit') ?></label>
                                <div class="input-group">
                                    <input type="number" name="berat_kulit" id="berat_kulit" min="0.1" step="0.001" data-pristine-min-message="Berat Kulit min. 0.1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="bonus_produksi" class="form-label"><?= lang('Files.Bonus_Produksi') ?></label>
                                <input type="text" class="form-control currency-rupiah-mask" name="bonus_produksi" id="bonus_produksi" placeholder="<?= lang('Files.Input_Upah') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-pengolahan"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>