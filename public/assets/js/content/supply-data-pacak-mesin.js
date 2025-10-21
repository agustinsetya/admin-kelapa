"use strict";

$((function () {
    applyFilterPacakMesin();

    $("#pcm_gudang_id").on("change", function () {
        let gudangId = $(this).val();

        reloadDropdownPegawaiByGudang('#pcm_pegawai_id', gudangId, roleScope, '6');
    });

    $("#btn-tambah-pacak-mesin").on("click", function () {
        openModalPacakMesin("add");
    });

    $(document).on('click', '.btn-edit-pacak-mesin', function () {
        getDetailSupplyPacakMesin(this);
    });

    $("body").on("click", "#btn-save-pacak-mesin", function (e) {
        e.preventDefault();
    
        var form = $("#supply-pacak-mesin-form")[0];
        var action = $("#supply-pacak-mesin-form").data("action");
        var id = $("#supply-pacak-mesin-form").data("id") ?? '';
    
        const gudangValue = $("#pcm_gudang_id").val();
        if (!gudangValue) {
            $("#pcm_gudang_id").addClass("is-invalid");
        } else {
            $("#pcm_gudang_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const pegawaiValue = $("#pcm_pegawai_id").val();
        if (!pegawaiValue) {
            $("#pcm_pegawai_id").addClass("is-invalid");
        } else {
            $("#pcm_pegawai_id").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $bonus_pacak_mesin = $("#bonus_pacak_mesin");
        $bonus_pacak_mesin.val(unmaskRupiah($bonus_pacak_mesin.val()));

        let url = 'supply-chain/pacak-mesin/add';
        if (action === 'edit') url = 'supply-chain/pacak-mesin/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-pacak-mesin", { text: "Menyimpan Data..." });

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
                successAlert('Simpan Data Pacak Mesin Berhasil!');
                $("#supplyPacakMesinModal").modal("hide");

                applyFilterPacakMesin();
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
            resetButton("btn-save-pacak-mesin","Simpan","btn btn-primary waves-effect waves-light");
        });
    });

    $(document).on("click", ".btn-hapus-pacak-mesin", function (e) {
        e.preventDefault();
    
        let id = $(this).data('id');

        if (!id) {
            errorAlert('ID Pacak Mesin tidak ditemukan.');
            return;
        }

        confirmDelete(() => deletePacakMesinData(encodeURIComponent(id)));
    });
}));

function applyFilterPacakMesin() {
    getDataSupplyPacakMesin().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPacakMesinTable(rows);
        reloadDropdownGudang("#pcm_gudang_id", roleScope, penempatan);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPacakMesin() {
    return $.ajax({
        url: base_url + 'supply-chain/pacak-mesin/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPacakMesinTable(data) {
    const $dpm = $(".dt-pacakMesinTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dpm)) {
        const dt = $dpm.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dpm.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'tg_pacak_mesin', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'jumlah_kelapa', defaultContent: "-" },
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
            { targets: [4], render: (d) => d ? formatAngkaDecimal(d) : "-" },
            {
                targets: 5,
                render: function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            },
            {
                targets: 6,
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: (data, type, row) => {
                    const isDisabled = row.is_stat_gaji == 1 ? 'disabled' : '';
                    const tooltipEdit = row.is_stat_gaji == 1 ? 'Sudah diproses' : 'Detail Pacak Mesin';
                    const tooltipDelete = row.is_stat_gaji == 1 ? 'Sudah diproses' : 'Hapus Pacak Mesin';
            
                    return `
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-icon btn-edit-pacak-mesin"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltipEdit}" data-id="${row.mt_pacak_mesin_id}" ${isDisabled}>
                                <i class="text-primary bx bx-pencil fs-5"></i>
                            </button>
                            <button type="button" class="btn btn-icon btn-hapus-pacak-mesin"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltipDelete}" data-id="${row.mt_pacak_mesin_id}" ${isDisabled}>
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

function getDetailSupplyPacakMesin(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + 'supply-chain/pacak-mesin/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPacakMesin("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPacakMesin(mode, data = null) {
    const $form = $("#supply-pacak-mesin-form");

    $form[0].reset();
    $form.removeClass("was-validated");
    $form.find(".is-invalid, .is-valid").removeClass("is-invalid is-valid");

    $("#pcm_gudang_id, #pcm_pegawai_id").val(null).trigger("change").removeClass("is-invalid is-valid");

    $(".fake-input").remove(); 
    $("#pcm_gudang_id, #pcm_pegawai_id").show();

    $form.find("input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPacakMesinModal .modal-title").text("Edit Data Pacak Mesin");

        $("#tg_pacak_mesin")
            .val(data.tg_pacak_mesin.split("T")[0])
            .prop("readonly", true);

        $("#pcm_gudang_id")
            .val(data.gudang_id)
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                       value="${data.nama_gudang}">
            `);

        $("#pcm_pegawai_id")
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                    value="${data.nama_pegawai}">
                <input type="hidden" name="pcm_pegawai_id" value="${data.kd_pegawai}">
            `);

        $("#jumlah_kelapa").val(data.jumlah_kelapa);
        $("#bonus_pacak_mesin").val(formatRupiah(data.bonus) ?? 0);

        $form.data("action", "edit");
        $form.data("id", data.mt_pacak_mesin_id);
        $form.append('<input type="hidden" name="_method" value="PATCH">');
    } else {
        $("#supplyPacakMesinModal .modal-title").text("Tambah Data Pacak Mesin");

        $("#tg_pacak_mesin").prop("readonly", false);
        $("#pcm_gudang_id, #pcm_pegawai_id")
            .val("")
            .trigger("change");

        reloadDropdownGudang("#pcm_gudang_id", roleScope, penempatan);

        $form.data("action", "add");
        $form.removeData("id");
    }

    $("#supplyPacakMesinModal").modal("show");
}

function deletePacakMesinData(pacakMesinId) {
    $.ajax({
        url: base_url + `supply-chain/pacak-mesin/delete/${pacakMesinId}`,
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
            successAlert('Hapus Data Pacak Mesin Berhasil!');

            applyFilterPacakMesin();
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