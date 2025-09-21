"use strict";

$((function () {
    applyFilterPenjualan();

    $("#penj_gudang_id").on("change", function () {
        let gudangId = $(this).val();

        reloadDropdownResiPengirimanByGudang('#log_pengiriman_id', gudangId);
    });

    $("#btn-tambah-penjualan").on("click", function () {
        openModalPenjualan("add");
    });

    $(document).on('click', '.btn-edit-penjualan', function () {
        getDetailSupplyPenjualan(this);
    });

    $("body").on("click", "#btn-save-penjualan", function (e) {
        e.preventDefault();
    
        var form = $("#supply-penjualan-form")[0];
        var action = $("#supply-penjualan-form").data("action");
        var id = $("#supply-penjualan-form").data("id") ?? '';
    
        const pengirimanValue = $("#log_pengiriman_id").val();
        if (!pengirimanValue) {
            $("#log_pengiriman_id").addClass("is-invalid");
        } else {
            $("#log_pengiriman_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const statusValue = $("#penj_status").val();
        if (!statusValue) {
            $("#penj_status").addClass("is-invalid");
        } else {
            $("#penj_status").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = '/supply-chain/penjualan/add';
        if (action === 'edit') url = '/supply-chain/penjualan/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-penjualan", { text: "Menyimpan Data..." });

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
                successAlert('Simpan Data Penjualan Berhasil!');
                $("#supplyPenjualanModal").modal("hide");

                applyFilterPenjualan();
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
            resetButton("btn-save-penjualan","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterPenjualan() {
    getDataSupplyPenjualan().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPenjualanTable(rows);
        reloadDropdownGudang("#penj_gudang_id", roleScope, penempatan);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPenjualan() {
    return $.ajax({
        url: base_url + '/supply-chain/penjualan/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPenjualanTable(data) {
    const $dpg = $(".dt-penjualanTable").first();
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
            { data: 'tg_penjualan', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: null, defaultContent: "-" },
            { data: null, defaultContent: "-" },
            { data: null, defaultContent: "-" },
        ],
        columnDefs: [
            { targets: 0, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1 },
            { 
                targets: 1, 
                render: function (data, type, row) {
                    var tgPenjualan = row.tg_penjualan ? formatTanggal(row.tg_penjualan) : "-";

                    var statusMap = {
                        BELUM_LUNAS: { title: "Belum Lunas", class: "badge-soft-warning" },
                        LUNAS: { title: "Lunas", class: "badge-soft-success" },
                    };

                    var meta = statusMap[row.status] || { title: "Unknown", class: "badge-soft-secondary" };

                    return `
                        <div class="d-flex flex-column align-items-start">
                            <span>${tgPenjualan}</span>
                            <span class="badge ${meta.class} font-size-12">${meta.title}</span>
                        </div>
                    `;
                }
            },
            { 
                targets: 2, 
                render: function (data, type, row) {
                    let html = data ? `<div>${data}</div>` : '<div>-</div>';
                    
                    html += `
                        <div>
                            <small class="fst-italic text-muted">Nomor Resi: ${row.nomor_resi}</small><br/>
                        </div>
                    `;
            
                    return html;
                }
            },
            {
                targets: 3,
                render: (data, type, row) => {
                    const daging_diterima = row.daging_kelapa_terima ? `${formatAngkaDecimal(row.daging_kelapa_terima)} kg` : "-";
                    const pendapatan_diterima  = row.pendapatan_terima ? `${formatRupiah(row.pendapatan_terima)}` : "-";
                    
                    return `
                        Berat : ${daging_diterima}<br>
                        Pendapatan  : ${pendapatan_diterima}
                    `;
                }
            },
            {
                targets: 4,
                render: (data, type, row) => {
                    const daging_ditolak = row.daging_kelapa_reject ? `${formatAngkaDecimal(row.daging_kelapa_reject)} kg` : "-";
                    const pendapatan_ditolak  = row.pendapatan_reject ? `${formatRupiah(row.pendapatan_reject)}` : "-";
            
                    return `
                        Berat : ${daging_ditolak}<br>
                        Pendapatan  : ${pendapatan_ditolak}
                    `;
                }
            },
            {
                targets: 5,
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: (data, type, row) => {
                    const isDisabled = row.status == 'LUNAS' ? 'disabled' : '';
                    const tooltip = row.status == 'LUNAS' ? 'Sudah Lunas' : 'Detail Penjualan';
            
                    return `
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-icon btn-edit-penjualan"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltip}" data-id="${row.mt_penjualan_id}" ${isDisabled}>
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


function getDetailSupplyPenjualan(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/supply-chain/penjualan/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPenjualan("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPenjualan(mode, data = null) {
    $("#supply-penjualan-form")[0].reset();
    $("#supply-penjualan-form").removeClass("was-validated");
    $("#penj_gudang_id, #log_pengiriman_id, #penj_status").val(null).trigger("change").removeClass("is-invalid is-valid");

    $(".fake-input").remove(); 
    $("#penj_gudang_id, #log_pengiriman_id").show();

    $("#supply-penjualan-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPenjualanModal .modal-title").text("Edit Data Penjualan");
    
        $("#tg_penjualan")
            .val(data.tg_penjualan.split("T")[0])
            .prop("readonly", true);
        $("#penj_gudang_id")
            .val(data.gudang_id)
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                       value="${data.nama_gudang}">
            `);
        $("#log_pengiriman_id")
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                    value="${data.nomor_resi}">
                <input type="hidden" name="log_pengiriman_id" value="${data.log_pengiriman_id}">
            `);
        $("#daging_kelapa_terima").val(data.daging_kelapa_terima);
        $("#daging_kelapa_reject").val(data.daging_kelapa_reject);
        $("#penj_status").val(data.status).trigger("change");
    
        $("#supply-penjualan-form").data("action", "edit");
        $("#supply-penjualan-form").data("id", data.mt_penjualan_id);

        $("#supply-penjualan-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#supplyPenjualanModal .modal-title").text("Tambah Data Penjualan");

        $("#tg_penjualan").prop("readonly", false);
        $("#penj_gudang_id, #log_pengiriman_id")
            .val("")
            .trigger("change");

        reloadDropdownGudang("#penj_gudang_id", roleScope, penempatan);
    
        $("#supply-penjualan-form").data("action", "add");
        $("#supply-penjualan-form").removeData("id");
    }

    $("#supplyPenjualanModal").modal("show");
}