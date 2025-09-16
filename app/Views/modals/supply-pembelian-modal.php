<div id="supplyPembelianModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="supply-pembelian-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="tg_pembelian" class="form-label">Tanggal Pembelian</label>
                                <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" name="tg_pembelian" id="tg_pembelian">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Gudang</label>
                                <select name="pem_gudang_id" id="pem_gudang_id" class="form-select" <?= $roleScope == 'gudang' ? 'disabled' : '' ?>>
                                    <option value="" disabled selected>Pilih Gudang</option>
                                    <?php foreach ($gudang as $g): ?>
                                        <option value="<?= esc($g->m_gudang_id) ?>"><?= esc($g->nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="kode_container" class="form-label"><?= lang('Files.Kode_Container') ?></label>
                                <input type="text" name="kode_container" id="kode_container" maxlength="100" required class="form-control" placeholder="<?= lang('Files.Input_Kode_Container') ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="berat_kelapa" class="form-label"><?= lang('Files.Berat_Kelapa') ?></label>
                                <div class="input-group">
                                    <input type="number" name="berat_kelapa" id="berat_kelapa" min="0.1" step="0.001" data-pristine-min-message="Berat Kelapa min. 0.1 Kg" required class="form-control" placeholder="<?= lang('Files.Input_Berat') ?>"/>
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-pembelian"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>