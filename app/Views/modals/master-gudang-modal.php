<div id="masterGudangModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="master-gudang-form" novalidate>
                <div class="modal-body">
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
                                    <input class="form-control" type="number" name="takaran_daging_kelapa" id="takaran_daging_kelapa" placeholder="<?= lang('Files.Input_Berat') ?>">
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
                                    <input class="form-control" type="number" name="takaran_kopra_kelapa" id="takaran_kopra_kelapa" placeholder="<?= lang('Files.Input_Berat') ?>">
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
                                <label for="gaji_driver" class="form-label"><?= lang('Files.Gaji_Driver') ?></label>
                                <input type="text" class="form-control currency-rupiah-mask" name="gaji_driver" id="gaji_driver" placeholder="<?= lang('Files.Input_Upah') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-gudang"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>