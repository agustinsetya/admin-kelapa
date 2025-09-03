"use strict";

$((function () {
    applyFilterPengolahan();

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
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $bonus_produksi = $("#bonus_produksi");
        $bonus_produksi.val(unmaskRupiah($bonus_produksi.val()));

        let url = '/supply-chain/pengolahan/add';
        if (action === 'edit') url = '/supply-chain/pengolahan/update';

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
}));

function applyFilterPengolahan() {
    getDataSupplyPengolahan().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPengolahanTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPengolahan() {
    return $.ajax({
        url: base_url + '/supply-chain/pengolahan/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPengolahanTable(data) {
    const $dpg = $(".dt-pengolahanTable").first();
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
                targets: 6,
                render: function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            },
            {
                targets: 7,
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: (data, type, row) => {
                    const isDisabled = row.is_stat_gaji == 1 ? 'disabled' : '';
                    const tooltip = row.is_stat_gaji == 1 ? 'Sudah diproses' : 'Detail Pengolahan';
            
                    return `
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-icon btn-edit-pengolahan"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltip}" data-id="${row.mt_pengolahan_id}" ${isDisabled}>
                                <i class="text-primary bx bx-pencil fs-5"></i>
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
        url: base_url + '/supply-chain/pengolahan/detail',
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
    $("#supply-pengolahan-form")[0].reset();
    $("#supply-pengolahan-form").removeClass("was-validated");
    $("#peng_gudang_id, #peng_pegawai_id").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#supply-pengolahan-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPengolahanModal .modal-title").text("Edit Data Pengolahan");
    
        $("#tg_pengolahan").val(data.tg_pengolahan.split("T")[0]);
        $("#peng_gudang_id").val(data.gudang_id).trigger("change");
        $("#peng_pegawai_id").val(data.kd_pegawai).trigger("change");
        $("#berat_daging").val(data.berat_daging);
        $("#berat_kopra").val(data.berat_kopra);
        $("#bonus").val(formatRupiah(data.bonus) ?? 0);
    
        $("#supply-pengolahan-form").data("action", "edit");
        $("#supply-pengolahan-form").data("id", data.mt_pengolahan_id);

        $("#supply-pengolahan-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#supplyPengolahanModal .modal-title").text("Tambah Data Pengolahan");
    
        $("#supply-pengolahan-form").data("action", "add");
        $("#supply-pengolahan-form").removeData("id");
    }

    $("#supplyPengolahanModal").modal("show");
}