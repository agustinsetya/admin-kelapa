<div id="masterUserModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="master-user-form" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Pegawai</label>
                                <select name="us_pegawai_id" id="us_pegawai_id" class="form-select">
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
                                <label for="email" class="form-label"><?= lang('Files.Email') ?></label>
                                <input class="form-control" type="email" name="email" id="email" placeholder="ex: email@example.com">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="password" class="form-label"><?= lang('Files.Password') ?></label>
                                <p class="form-control-static"><b>Password default sama dengan Kode Pegawai</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-user"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>