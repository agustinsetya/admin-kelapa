"use strict";

$((function () {
    applyFilterUserRoles();

    $("#btn-tambah-user-roles").on("click", function () {
        openModalUserRoles("add");
    });

    $(document).on('click', '.btn-edit-user-roles', function () {
        getDetailMasterUserRoles(this);
    });

    $("body").on("click", "#btn-save-user-roles", function (e) {
        e.preventDefault();
    
        var form = $("#master-user-roles-form")[0];
        var action = $("#master-user-roles-form").data("action");
        var id = $("#master-user-roles-form").data("id") ?? '';
        
        const roleScopeVal = $("#lingkup_peran").val();
        if (!roleScopeVal) {
            $("#lingkup_peran").addClass("is-invalid");
        } else {
            $("#lingkup_peran").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = '/master/user-roles/add';
        if (action === 'edit') url = '/master/user-roles/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-user-roles", { text: "Menyimpan Data..." });

        $.ajax({
            url: base_url + url,
            method: 'POST',
            data: payload,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .done(function (response) {
            if (response?.csrf?.name && response?.csrf?.hash) {
                $('meta[name="csrf-token-name"]').attr('content', response.csrf.name);
                $('meta[name="csrf-token"]').attr('content', response.csrf.hash);
            }

            if (response?.success) {
                successAlert("Simpan Data User Roles Berhasil!");
                $("#masterUserRolesModal").modal("hide");

                applyFilterUserRoles();
            } else {
                const message = response?.errors ?? response?.message ?? 'Simpan Data Gagal!';
                errorAlert(message, 'Simpan Data Gagal!');
            }
        })
        .fail(function (jqXHR) {
            try {
                const res = jqXHR.responseJSON;
        
                if (res?.csrf?.name && res?.csrf?.hash) {
                    $('meta[name="csrf-token-name"]').attr('content', res.csrf.name);
                    $('meta[name="csrf-token"]').attr('content', res.csrf.hash);
                }
        
                const msg = res?.errors ?? res?.message ?? res?.error ?? 'Terjadi kesalahan saat menyimpan';
                errorAlert('Gagal Menyimpan', msg);
            } catch (e) {
                errorAlert('Error!', 'Terjadi kesalahan. Cek konsol.');
                console.error('Save error:', jqXHR.status, jqXHR.responseText);
            }
        })
        .always(function () {
            resetButton("btn-save-user-roles","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterUserRoles() {
    getDataMasterUserRoles().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeMasterUserRolesTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataMasterUserRoles() {
    return $.ajax({
        url: base_url + '/master/user-roles/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeMasterUserRolesTable(data) {
    const $dur = $(".dt-userRolesTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dur)) {
        const dt = $dur.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }
    
    $dur.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'nama', defaultContent: "-" },
            { data: 'role_scope', defaultContent: "-" },
            { data: null, defaultContent: "" }             
        ],
        columnDefs: [
            // Additional column
            {
                targets: 0,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                targets: 2,
                render: function (data, type, row) {
                    return data === 'all' ? 'All' : (data === 'gudang' ? 'Gudang' : '-');
                }
            },
            {
                targets: 3,
                className: 'no-export',
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: function (data, type, row, meta) {
                    var actionUserRolesButton = '<div class="d-flex align-items-center gap-1">';

                    actionUserRolesButton += '<button type="button" class="btn btn-icon btn-edit-user-roles" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Detail User Roles" ' +
                        'data-bs-target="#masterUserRolesModal" ' +
                        'data-id="' + row.m_role_id + '"> ' +
                        '<i class="text-primary bx bx-pencil fs-5"></i>' +
                    '</button>';

                    actionUserRolesButton += '</div>';
                
                    return actionUserRolesButton;
                }
            }
        ],
        lengthChange: false,
        buttons: ['excel'],
        dom: 
            '<"row align-items-center mb-2"' +
                '<"col-sm-12 col-md-6 d-flex justify-content-start"B>' +
                '<"col-sm-12 col-md-6 d-flex justify-content-md-end"f>' +
            '>' +
            't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    });
}

function getDetailMasterUserRoles(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/master/user-roles/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalUserRoles("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalUserRoles(mode, data = null) {
    $("#master-user-roles-form")[0].reset();
    $("#master-user-roles-form").removeClass("was-validated");
    $("#lingkup_peran").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#master-user-roles-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#masterUserRolesModal .modal-title").text("Edit Data User Roles");

        $("#nama_peran").val(data.nama);
        $("#lingkup_peran").val(data.role_scope).trigger("change");
    
        $("#master-user-roles-form").data("action", "edit");
        $("#master-user-roles-form").data("id", data.m_role_id );

        $("#master-user-roles-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#masterUserRolesModal .modal-title").text("Tambah Data User Roles");
    
        $("#master-user-roles-form").data("action", "add");
        $("#master-user-roles-form").removeData("id");
    }

    $("#masterUserRolesModal").modal("show");
}