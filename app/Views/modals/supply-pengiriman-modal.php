<div id="supplyPengirimanModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="supply-pengiriman-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="tg_pengiriman" class="form-label">Tanggal Pengiriman</label>
                                <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" name="tg_pengiriman" id="tg_pengiriman">
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
                                <label for="jenis_kirim" class="form-label">Jenis Pengiriman</label>
                                <select name="jenis_kirim" id="jenis_kirim" class="form-select" required>
                                    <option value="" disabled selected>Pilih Jenis Pengiriman</option>
                                    <option value="distribusi">Distribusi</option>
                                    <option value="bongkar_container">Ngepok</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="armada" class="form-label">Armada</label>
                                <select name="armada" id="armada" class="form-select" required>
                                    <option value="" disabled selected>Pilih Armada</option>
                                    <option value="truk">Truk</option>
                                    <option value="pickup">Pick Up</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="nomor_resi" class="form-label">Nomor Resi</label>
                                <input type="text" name="nomor_resi" id="nomor_resi" maxlength="100" required class="form-control" placeholder="Masukkan Nomor Resi"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="jumlah_perjalanan" class="form-label"><?= lang('Files.Jumlah_Perjalanan') ?></label>
                                <div class="input-group">
                                <input type="number" name="jumlah_perjalanan" id="jumlah_perjalanan" min="1" data-pristine-min-message="Jumlah Perjalanan min. 1 kali" required class="form-control" placeholder="<?= lang('Files.Input_Jumlah') ?>"/>
                                    <span class="input-group-text">Kali</span>
                                </div>
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
                                <label for="berat_daging" class="form-label"><?= lang('Files.Berat_Daging') ?></label>
                                <div class="input-group">
                                    <input type="number" name="berat_daging" id="berat_daging" min="1" data-pristine-min-message="Berat Daging Kelapa min. 1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="bonus_pengiriman" class="form-label"><?= lang('Files.Bonus_Pengiriman') ?></label>
                                <input type="text" class="form-control currency-rupiah-mask" name="bonus_pengiriman" id="bonus_pengiriman" placeholder="<?= lang('Files.Input_Upah') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-pengiriman"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>