<div id="masterUserRolesModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" id="master-user-roles-form" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="nama_peran" class="form-label"><?= lang('Files.Nama_Peran') ?></label>
                                <div class="input-group">
                                    <input type="text" name="nama_peran" id="nama_peran" required class="form-control" placeholder="<?= lang('Files.Input_Nama') ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="lingkup_peran" class="form-label">Status</label>
                                <select name="lingkup_peran" id="lingkup_peran" class="form-select" required>
                                    <option value="" disabled selected>Pilih Lingkup Peran</option>
                                    <option value="all">All</option>
                                    <option value="gudang">Gudang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-save-user-roles"><?= lang('Files.Save') ?></button>
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal"><?= lang('Files.Close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>