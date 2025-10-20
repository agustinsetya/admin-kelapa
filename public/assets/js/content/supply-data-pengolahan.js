"use strict";

$((function () {
    applyFilterPengolahan();

    $("#peng_gudang_id").on("change", function () {
        let gudangId = $(this).val();

        reloadDropdownPegawaiByGudang('#peng_pegawai_id', gudangId, roleScope, '6');
        reloadDropdownContainerPembelianByGudang('#peng_kode_container', gudangId);
    });

    $("#btn-tambah-pengolahan").on("click", function () {
        openModalPengolahan("add");
    });

    $(document).on('click', '.btn-edit-pengolahan', function () {
        getDetailSupplyPengolahan(this);
    });

    $("body").on("click", "#btn-save-pengolahan", function (e) {
        e.preventDefault();
    
        var form = $("#supply-pengolahan-form")[0];
        var action = $("#supply-pengolahan-form").data("action");
        var id = $("#supply-pengolahan-form").data("id") ?? '';
    
        const gudangValue = $("#peng_gudang_id").val();
        if (!gudangValue) {
            $("#peng_gudang_id").addClass("is-invalid");
        } else {
            $("#peng_gudang_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const pegawaiValue = $("#peng_pegawai_id").val();
        if (!pegawaiValue) {
            $("#peng_pegawai_id").addClass("is-invalid");
        } else {
            $("#peng_pegawai_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const containerValue = $("#peng_kode_container").val();
        if (!containerValue) {
            $("#peng_kode_container").addClass("is-invalid");
        } else {
            $("#peng_kode_container").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $bonus_produksi = $("#bonus_produksi");
        $bonus_produksi.val(unmaskRupiah($bonus_produksi.val()));

        let url = 'supply-chain/pengolahan/add';
        if (action === 'edit') url = 'supply-chain/pengolahan/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-pengolahan", { text: "Menyimpan Data..." });

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
                successAlert('Simpan Data Pengolahan Berhasil!');
                $("#supplyPengolahanModal").modal("hide");

                applyFilterPengolahan();
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
                errorAlert(msg, 'Gagal Menyimpan');
            } catch (e) {
                errorAlert('Error!', 'Terjadi kesalahan. Cek konsol.');
                console.error('Save error:', jqXHR.status, jqXHR.responseText);
            }
        })
        .always(function () {
            resetButton("btn-save-pengolahan","Simpan","btn btn-primary waves-effect waves-light");
        });
    });

    $(document).on("click", ".btn-hapus-pengolahan", function (e) {
        e.preventDefault();
    
        let id = $(this).data('id');

        if (!id) {
            errorAlert('ID pengolahan tidak ditemukan.');
            return;
        }

        confirmDelete(() => deleteUserData(encodeURIComponent(id)));
    });
}));

function applyFilterPengolahan() {
    getDataSupplyPengolahan().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPengolahanTable(rows);
        reloadDropdownGudang("#peng_gudang_id", roleScope, penempatan);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPengolahan() {
    return $.ajax({
        url: base_url + 'supply-chain/pengolahan/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPengolahanTable(data) {
    const $dpg = $(".dt-logPengolahanTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dpg)) {
        const dt = $dpg.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dpg.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'tg_pengolahan', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'berat_daging', defaultContent: "-" },
            { data: 'berat_kopra', defaultContent: "-" },
            { data: 'berat_kulit', defaultContent: "-" },
            { data: 'bonus', defaultContent: "-" },
            { data: null, defaultContent: "-" },
        ],
        columnDefs: [
            { targets: 0, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1 },
            { 
                targets: 1, 
                render: function (data, type, row) {
                    let html = data ? `<div>${formatTanggal(data)}</div>` : '<div>-</div>';
            
                    if (row.is_stat_gaji == 1) {
                        const tglProses = row.tg_proses_gaji && row.tg_proses_gaji != "0000-00-00" ? formatTanggal(row.tg_proses_gaji) : "-";
                        html += `
                            <div>
                                <span class="badge bg-success mt-1">Gaji Sudah Diproses</span><br/>
                                <small class="fst-italic text-muted">Tgl Proses Gaji: ${tglProses}</small>
                            </div>
                        `;
                    }
            
                    return html;
                }
            },
            { 
                targets: 2, 
                render: function (data, type, row) {
                    let html = data ? `<div>${data}</div>` : '<div>-</div>';
            
                    html += `
                        <div>
                            <small class="fst-italic text-muted">Kode Container: ${row.kode_container || '-'}</small>
                        </div>
                    `;
            
                    return html;
                }
            },
            { targets: [4,5,6], render: (d) => d ? formatAngkaDecimal(d) : "-" },
            {
                targets: 7,
                render: function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            },
            {
                targets: 8,
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: (data, type, row) => {
                    const isDisabled = row.is_stat_gaji == 1 ? 'disabled' : '';
                    const tooltipEdit = row.is_stat_gaji == 1 ? 'Sudah diproses' : 'Detail Pengolahan';
                    const tooltipDelete = row.is_stat_gaji == 1 ? 'Sudah diproses' : 'Hapus Pengolahan';
            
                    return `
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-icon btn-edit-pengolahan"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltipEdit}" data-id="${row.mt_log_pengolahan_id}" ${isDisabled}>
                                <i class="text-primary bx bx-pencil fs-5"></i>
                            </button>
                            <button type="button" class="btn btn-icon btn-hapus-pengolahan"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltipDelete}" data-id="${row.mt_log_pengolahan_id}" ${isDisabled}>
                                <i class="text-danger bx bx-trash fs-5"></i>
                            </button>
                        </div>
                    `;
                }
            },
        ],
        lengthChange: false,
        buttons: ['excel'],
        dom:
            '<"row align-items-center mb-2"' +
                '<"col-sm-12 col-md-6 d-flex justify-content-start"B>' +
                '<"col-sm-12 col-md-6 d-flex justify-content-md-end"f>' +
            '>' +
            't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    });
}

function getDetailSupplyPengolahan(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + 'supply-chain/pengolahan/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPengolahan("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPengolahan(mode, data = null) {
    const $form = $("#supply-pengolahan-form");

    $form[0].reset();
    $form.removeClass("was-validated");
    $form.find(".is-invalid, .is-valid").removeClass("is-invalid is-valid");

    $("#peng_gudang_id, #peng_pegawai_id, #peng_kode_container").val(null).trigger("change").removeClass("is-invalid is-valid");

    $(".fake-input").remove(); 
    $("#peng_gudang_id, #peng_pegawai_id, #peng_kode_container").show();

    $form.find("input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPengolahanModal .modal-title").text("Edit Data Pengolahan");

        $("#tg_pengolahan")
            .val(data.tg_pengolahan.split("T")[0])
            .prop("readonly", true);

        $("#peng_gudang_id")
            .val(data.gudang_id)
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                       value="${data.nama_gudang}">
            `);

        $("#peng_pegawai_id")
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                    value="${data.nama_pegawai}">
                <input type="hidden" name="peng_pegawai_id" value="${data.kd_pegawai}">
            `);

        $("#peng_kode_container")
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                    value="${data.kode_container}">
                <input type="hidden" name="peng_kode_container" value="${data.kode_container}">
            `);

        $("#berat_daging").val(data.berat_daging);
        $("#berat_kopra").val(data.berat_kopra);
        $("#berat_kulit").val(data.berat_kulit);
        $("#bonus_produksi").val(formatRupiah(data.bonus) ?? 0);

        $form.data("action", "edit");
        $form.data("id", data.mt_log_pengolahan_id);
        $form.append('<input type="hidden" name="_method" value="PATCH">');
    } else {
        $("#supplyPengolahanModal .modal-title").text("Tambah Data Pengolahan");

        $("#tg_pengolahan").prop("readonly", false);
        $("#peng_gudang_id, #peng_pegawai_id, #peng_kode_container")
            .val("")
            .trigger("change");

        reloadDropdownGudang("#peng_gudang_id", roleScope, penempatan);

        $form.data("action", "add");
        $form.removeData("id");
    }

    $("#supplyPengolahanModal").modal("show");
}

function deleteUserData(pengolahanId) {
    $.ajax({
        url: base_url + `supply-chain/pengolahan/delete/${pengolahanId}`,
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    })
    .done(function (response) {
        if (response?.csrf?.name && response?.csrf?.hash) {
            $('meta[name="csrf-token-name"]').attr('content', response.csrf.name);
            $('meta[name="csrf-token"]').attr('content', response.csrf.hash);
        }

        if (response?.success) {
            successAlert('Hapus Data Pengolahan Berhasil!');

            applyFilterPengolahan();
        } else {
            const message = response?.errors ?? response?.message ?? 'Hapus Data Gagal!';
            errorAlert(message, 'Hapus Data Gagal!');
        }
    })
    .fail(function (jqXHR) {
        try {
            const res = jqXHR.responseJSON;
    
            if (res?.csrf?.name && res?.csrf?.hash) {
                $('meta[name="csrf-token-name"]').attr('content', res.csrf.name);
                $('meta[name="csrf-token"]').attr('content', res.csrf.hash);
            }
    
            const msg = res?.errors ?? res?.message ?? res?.error ?? 'Terjadi kesalahan saat Menghapus';
            errorAlert(msg, 'Gagal Menghapus');
        } catch (e) {
            errorAlert('Error!', 'Terjadi kesalahan. Cek konsol.');
            console.error('Save error:', jqXHR.status, jqXHR.responseText);
        }
    })
}