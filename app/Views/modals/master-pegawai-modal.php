<div id="masterPegawaiModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="master-pegawai-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="kd_pegawai" class="form-label"><?= lang('Files.Kode_Pegawai') ?></label>
                                <input type="number" name="kd_pegawai" id="kd_pegawai" required class="form-control" placeholder="<?= lang('Files.Input_Kode_Pegawai') ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="nama_pegawai" class="form-label"><?= lang('Files.Nama_Pegawai') ?></label>
                                <input type="text" name="nama_pegawai" id="nama_pegawai" maxlength="100" required class="form-control" placeholder="<?= lang('Files.Input_Nama_Pegawai') ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Role</label>
                                <select name="peg_role_id" id="peg_role_id" class="form-select">
                                    <option value="" disabled selected>Pilih Role</option>
                                    <?php foreach ($role as $r): ?>
                                        <option value="<?= esc($r->m_role_id) ?>"><?= esc($r->nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label mb-1">Penempatan</label>
                                <select name="pg_gudang_id" id="pg_gudang_id" class="form-select">
                                    <option value="" disabled selected>Pilih Gudang</option>
                                    <?php foreach ($gudang as $g): ?>
                                        <option value="<?= esc($g->m_gudang_id) ?>"><?= esc($g->nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-pegawai"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>