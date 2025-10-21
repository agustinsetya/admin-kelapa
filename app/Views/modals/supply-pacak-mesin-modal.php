<div id="supplyPacakMesinModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="supply-pacak-mesin-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="tg_pacak_mesin" class="form-label">Tanggal Pacak Mesin</label>
                                <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" name="tg_pacak_mesin" id="tg_pacak_mesin">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Gudang</label>
                                <select name="pcm_gudang_id" id="pcm_gudang_id" class="form-select">
                                    <option value="" disabled selected>Pilih Gudang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Pegawai</label>
                                <select name="pcm_pegawai_id" id="pcm_pegawai_id" class="form-select">
                                    <option value="" disabled selected>Pilih Pegawai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="jumlah_kelapa" class="form-label"><?= lang('Files.Jumlah_Kelapa') ?></label>
                                <div class="input-group">
                                    <input type="number" name="jumlah_kelapa" id="jumlah_kelapa" min="1" data-pristine-min-message="Berat Daging min. 1 Buah" required class="form-control" placeholder="<?= lang('Files.Input_Jumlah') ?>"/>
                                    <span class="input-group-text">Buah</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="bonus_pacak_mesin" class="form-label"><?= lang('Files.Bonus_Pacak_Mesin') ?></label>
                                <input type="text" class="form-control currency-rupiah-mask" name="bonus_pacak_mesin" id="bonus_pacak_mesin" placeholder="<?= lang('Files.Input_Bonus') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-pacak-mesin"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>