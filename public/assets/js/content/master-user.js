"use strict";

$((function () {
    applyFilterUser();

    $("#btn-tambah-user").on("click", function () {
        openModalUser("add");
    });

    $(document).on('click', '.btn-edit-user', function () {
        getDetailMasterUser(this);
    });

    $("body").on("click", "#btn-save-user", function (e) {
        e.preventDefault();
    
        var form = $("#master-user-form")[0];
        var action = $("#master-user-form").data("action");
        var id = $("#master-user-form").data("id") ?? '';
    
        const pegawaiValue = $("#us_pegawai_id").val();
        if (!pegawaiValue) {
            $("#us_pegawai_id").addClass("is-invalid");
        } else {
            $("#us_pegawai_id").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = 'master/user/add';
        if (action === 'edit') url = 'master/user/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-user", { text: "Menyimpan Data..." });

        $.ajax({
            url: base_url + url,
            method: 'POST',
            data: payload,
            dataType: 'json',
        })
        .done(function (response) {
            if (response?.csrf) {
                const name = response.csrf.name;
                const hash = response.csrf.hash;
                $('input[name="'+ name +'"]').val(hash);
            }

            if (response?.success) {
                alert('Simpan Data User Berhasil!');
                $("#masterUserModal").modal("hide");

                applyFilterUser();
            } else {
                alert(response?.message || 'Simpan Data Gagal!');
            }
        })
        .fail(function (jqXHR) {
            try {
                const res = jqXHR.responseJSON;
                if (res?.csrf) {
                    $('input[name="'+ res.csrf.name +'"]').val(res.csrf.hash);
                }
                const msg = res?.message || res?.error || 'Terjadi kesalahan saat menyimpan';
                alert(msg);
            } catch (e) {
                alert('Terjadi kesalahan. Cek konsol.');
                console.error('Save error:', jqXHR.status, jqXHR.responseText);
            }
        })
        .always(function () {
            resetButton("btn-save-user","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterUser() {
    getDataMasterUser().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeMasterUserTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataMasterUser() {
    return $.ajax({
        url: base_url + '/master/user/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeMasterUserTable(data) {
    const $dmu = $(".dt-userTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dmu)) {
        const dt = $dmu.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }
    
    $dmu.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'kd_pegawai', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'email', defaultContent: "-" },
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
                targets: 4,
                className: 'no-export',
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: function (data, type, row, meta) {
                    var actionUserButton = '<div class="d-flex align-items-center gap-1">';

                    actionUserButton += '<button type="button" class="btn btn-icon btn-edit-user" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Detail User" ' +
                        'data-id="' + row.mt_user_id + '"> ' +
                        '<i class="text-primary bx bx-pencil fs-5"></i>' +
                    '</button>';
                    
                    actionUserButton += '<button type="button" class="btn btn-icon btn-hapus-user" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Hapus User" ' +
                        'data-bs-target="#hapusUserModal" ' +
                        'data-id="' + row.mt_user_id + '"> ' +
                        '<i class="text-danger bx bx-trash fs-5"></i>' +
                    '</button>';

                    actionUserButton += '</div>';
                
                    return actionUserButton;
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

function getDetailMasterUser(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/master/user/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalUser("edit", response.data[0]);
            } else {
                alert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalUser(mode, data = null) {
    $("#master-user-form")[0].reset();
    $("#master-user-form").removeClass("was-validated");
    $("#us_pegawai_id").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#master-user-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#masterUserModal .modal-title").text("Edit Data User");

        $("#us_pegawai_id").val(data.kd_pegawai).trigger("change");
        $("#email").val(data.email);
    
        $("#master-user-form").data("action", "edit");
        $("#master-user-form").data("id", data.mt_user_id);

        $("#master-user-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#masterUserModal .modal-title").text("Tambah Data User");
    
        $("#master-user-form").data("action", "add");
        $("#master-user-form").removeData("id");
    }

    $("#masterUserModal").modal("show");
}