"use strict";

$((function () {
    applyFilterPegawai();

    $('#applyPegawaiFilter').click(function() {
        var gudang = $('#gudang_id').val();
        var role = $('#role_id').val();

        applyFilterPegawai(gudang, role);
    });
    
    $('#resetPegawaiFilter').click(function() {
        $('#gudang_id, #role_id').val('').trigger('change');

        applyFilterPegawai();
    });

	const initialRole = $("#peg_role_id").val();
    toggleGudangField(initialRole);

    $("#peg_role_id").on("change", function () {
        toggleGudangField($(this).val());
    });

    $("#btn-tambah-pegawai").on("click", function () {
        openModalPegawai("add");
    });

    $(document).on('click', '.btn-edit-pegawai', function () {
        getDetailMasterPegawai(this);
    });

    $("body").on("click", "#btn-save-pegawai", function (e) {
        e.preventDefault();
    
        var form = $("#master-pegawai-form")[0];
        var action = $("#master-pegawai-form").data("action");
        var id = $("#master-pegawai-form").data("id") ?? '';
    
        const jkValue = $("#jenis_kelamin").val();
        if (!jkValue) {
            $("#jenis_kelamin").addClass("is-invalid");
        } else {
            $("#jenis_kelamin").removeClass("is-invalid").addClass("is-valid");
        }
        
        const roleValue = $("#peg_role_id").val();
        if (!roleValue) {
            $("#peg_role_id").addClass("is-invalid");
        } else {
            $("#peg_role_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const gudangValue = $("#pg_gudang_id").val();
		if ($(".pg_gudang").is(":visible") && !gudangValue) {
    		$("#pg_gudang_id").addClass("is-invalid");
		} else {
    		$("#pg_gudang_id").removeClass("is-invalid").addClass("is-valid");
		}
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = 'master/pegawai/add';
        if (action === 'edit') url = 'master/pegawai/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-pegawai", { text: "Menyimpan Data..." });

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
                successAlert("Simpan Data Pegawai Berhasil!");
                $("#masterPegawaiModal").modal("hide");
            
                applyFilterPegawai();
            } else {
                const message = response?.errors ?? response?.message ?? 'Simpan Data Gagal!';
                errorAlert('Simpan Data Gagal!', message);
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
            resetButton("btn-save-pegawai","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function toggleGudangField(roleValue) {
    if (roleValue !== "1" && roleValue !== null) {
        $(".pg_gudang").css('display', 'block');
    } else {
        $(".pg_gudang").css('display', 'none');
    }
}

function applyFilterPegawai(gudang = null, role = null) {
    getDataMasterPegawai(gudang, role).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeMasterPegawaiTable(rows);
        reloadDropdownRole("#peg_role_id", roleScope);
        reloadDropdownGudang("#pg_gudang_id", roleScope, penempatan);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataMasterPegawai(gudang = null, role = null) {
    return $.ajax({
        url: base_url + '/master/pegawai/data',
        method: 'GET',
        data: {
            gudang: gudang,
            role: role
        },
        dataType: 'json'
    });
}

function initializeMasterPegawaiTable(data) {
    const $dmp = $(".dt-pegawaiTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dmp)) {
        const dt = $dmp.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }
    
    $dmp.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'kd_pegawai', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'role_name', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'jenis_kelamin', defaultContent: "-" },
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
                render: function (data) {
                    return (data === null || data === undefined || data === "") ? 'All' : data;
                }
            },
            {
                targets: 5,
                render: function (data, type, row) {
                    return data === 'L' ? 'Laki-laki' : (data === 'P' ? 'Perempuan' : '-');
                }
            },
            {
                targets: 6,
                className: 'no-export',
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: function (data, type, row, meta) {
                    var actionPegawaiButton = '<div class="d-flex align-items-center gap-1">';

                    actionPegawaiButton += '<button type="button" class="btn btn-icon btn-edit-pegawai" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Detail Pegawai" ' +
                        'data-bs-target="#masterPegawaiModal" ' +
                        'data-id="' + row.mt_pegawai_id + '"> ' +
                        '<i class="text-primary bx bx-pencil fs-5"></i>' +
                    '</button>';

                    actionPegawaiButton += '</div>';
                
                    return actionPegawaiButton;
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

function getDetailMasterPegawai(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/master/pegawai/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPegawai("edit", response.data[0]);
            } else {
                errorAlert('', "Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert('', "Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPegawai(mode, data = null) {
    $("#master-pegawai-form")[0].reset();
    $("#master-pegawai-form").removeClass("was-validated");
    $("#jenis_kelamin, #peg_role_id, #pg_gudang_id").show().val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#master-pegawai-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#masterPegawaiModal .modal-title").text("Edit Data Pegawai");

        $("#kd_pegawai").val(data.kd_pegawai).prop("readonly", true);
        $("#nama_pegawai").val(data.nama_pegawai);
        $("#jenis_kelamin").val(data.jenis_kelamin).trigger("change");
        $("#peg_role_id").val(data.role_id).trigger("change");
    	$("#pg_gudang_id").val(data.penempatan_id).trigger("change");
    
        $("#master-pegawai-form").data("action", "edit");
        $("#master-pegawai-form").data("id", data.mt_pegawai_id);

        $("#master-pegawai-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#masterPegawaiModal .modal-title").text("Tambah Data Pegawai");
    	$("#kd_pegawai").prop("readonly", false);
        
        reloadDropdownRole("#peg_role_id", roleScope);
        reloadDropdownGudang("#pg_gudang_id", roleScope, penempatan);
    
        $("#master-pegawai-form").data("action", "add");
        $("#master-pegawai-form").removeData("id");
    }

    $("#masterPegawaiModal").modal("show");
}