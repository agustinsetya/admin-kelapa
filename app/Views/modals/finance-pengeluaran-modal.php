<div id="financePengeluaranModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="finance-pengeluaran-form" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="tg_pengeluaran" class="form-label">Tanggal Pengeluaran</label>
                                <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" name="tg_pengeluaran" id="tg_pengeluaran">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Kategori</label>
                                <select name="peng_ktg_pengeluaran_id" id="peng_ktg_pengeluaran_id" class="form-select">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <?php foreach ($kategori as $ktg): ?>
                                        <option value="<?= esc($ktg->m_ktg_pengeluaran_id) ?>"><?= esc($ktg->nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Gudang</label>
                                <select name="peng_gudang_id" id="peng_gudang_id" class="form-select" <?= $roleScope == 'gudang' ? 'disabled' : '' ?>>
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
                                <select name="peng_pegawai_id" id="peng_pegawai_id" class="form-select">
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
                                <div class="input-group">
                                    <input type="number" name="jumlah" id="jumlah" min="1" required class="form-control" placeholder="<?= lang('Files.Input_Jumlah') ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="biaya" class="form-label"><?= lang('Files.Biaya') ?></label>
                                <input type="text" class="form-control currency-rupiah-mask" name="biaya" id="biaya" placeholder="<?= lang('Files.Input_Biaya') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="peng_status" class="form-label">Status</label>
                                <select name="peng_status" id="peng_status" class="form-select">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="BELUM_BAYAR">Belum Bayar</option>
                                    <option value="SUDAH_BAYAR">Sudah Bayar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-pengeluaran"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>