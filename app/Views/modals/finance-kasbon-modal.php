<div id="financeKasbonModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="finance-kasbon-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="tg_kasbon" class="form-label">Tanggal Kasbon</label>
                                <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" name="tg_kasbon" id="tg_kasbon">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Gudang</label>
                                <select name="kb_gudang_id" id="kb_gudang_id" class="form-select" <?= $roleScope == 'gudang' ? 'disabled' : '' ?>>
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
                                <label class="form-label mb-1">Pegawai</label>
                                <select name="kb_pegawai_id" id="kb_pegawai_id" class="form-select">
                                    <option value="" disabled selected>Pilih Pegawai</option>
                                    <?php foreach ($pegawai as $p): ?>
                                        <option value="<?= esc($p->kd_pegawai) ?>"><?= esc($p->nama_pegawai) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label"><?= lang('Files.Jumlah') ?></label>
                                <input type="text" class="form-control currency-rupiah-mask" name="jumlah" id="jumlah" placeholder="<?= lang('Files.Input_Jumlah') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="kb_status" class="form-label">Status</label>
                                <select name="kb_status" id="kb_status" class="form-select">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="BELUM_LUNAS">Belum Lunas</option>
                                    <option value="SUDAH_LUNAS">Sudah Lunas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-kasbon"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>