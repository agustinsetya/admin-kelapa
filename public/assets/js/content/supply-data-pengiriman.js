"use strict";

$((function () {
    applyFilterPengiriman();

    $("#btn-tambah-pengiriman").on("click", function () {
        openModalPengiriman("add");
    });

    $(document).on('click', '.btn-edit-pengiriman', function () {
        getDetailSupplyPengiriman(this);
    });

    $("body").on("click", "#btn-save-pengiriman", function (e) {
        e.preventDefault();
    
        var form = $("#supply-pengiriman-form")[0];
        var action = $("#supply-pengiriman-form").data("action");
        var id = $("#supply-pengiriman-form").data("id") ?? '';
    
        const gudangValue = $("#peng_gudang_id").val();
        if (!gudangValue) {
            $("#peng_gudang_id").addClass("is-invalid");
        } else {
            $("#peng_gudang_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const jenisKirimValue = $("#jenis_kirim").val();
        if (!jenisKirimValue) {
            $("#jenis_kirim").addClass("is-invalid");
        } else {
            $("#jenis_kirim").removeClass("is-invalid").addClass("is-valid");
        }
        
        const armadaValue = $("#armada").val();
        if (!armadaValue) {
            $("#armada").addClass("is-invalid");
        } else {
            $("#armada").removeClass("is-invalid").addClass("is-valid");
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

        const $bonus_pengiriman = $("#bonus_pengiriman");
        $bonus_pengiriman.val(unmaskRupiah($bonus_pengiriman.val()));

        let url = '/supply-chain/pengiriman/add';
        if (action === 'edit') url = '/supply-chain/pengiriman/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-pengiriman", { text: "Menyimpan Data..." });

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
                successAlert('Simpan Data Pengiriman Berhasil!');
                $("#supplyPengirimanModal").modal("hide");

                applyFilterPengiriman();
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
            resetButton("btn-save-pengiriman","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterPengiriman() {
    getDataSupplyPengiriman().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPengirimanTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPengiriman() {
    return $.ajax({
        url: base_url + '/supply-chain/pengiriman/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPengirimanTable(data) {
    const $dpg = $(".dt-pengirimanTable").first();
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
            { data: 'tg_pengiriman', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'berat_daging', defaultContent: "-" },
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
                    let jenis_kirim = row.jenis_kirim === 'bongkar_container' ? 'Ngepok dari Container' : 'Distribusi';
            
                    html += `
                        <div>
                            <span class="badge bg-success mt-1">${jenis_kirim}</span><br/>
                            <small class="fst-italic text-muted">Armada: ${row.armada}</small>
                        </div>
                    `;
            
                    return html;
                }
            },
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
                    const tooltip = row.is_stat_gaji == 1 ? 'Sudah diproses' : 'Detail Pengiriman';
            
                    return `
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-icon btn-edit-pengiriman"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltip}" data-id="${row.mt_log_pengiriman_id}" ${isDisabled}>
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


function getDetailSupplyPengiriman(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/supply-chain/pengiriman/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPengiriman("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPengiriman(mode, data = null) {
    $("#supply-pengiriman-form")[0].reset();
    $("#supply-pengiriman-form").removeClass("was-validated");
    $("#peng_gudang_id, #jenis_kirim, #armada, #peng_pegawai_id").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#supply-pengiriman-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPengirimanModal .modal-title").text("Edit Data Pengiriman");
    
        $("#tg_pengiriman").val(data.tg_pengiriman.split("T")[0]);
        $("#peng_gudang_id").val(data.gudang_id).trigger("change");
        $("#jenis_kirim").val(data.jenis_kirim).trigger("change");
        $("#armada").val(data.armada).trigger("change");
        $("#peng_pegawai_id").val(data.kd_pegawai).trigger("change");
        $("#berat_daging").val(data.berat_daging);
        $("#bonus_pengiriman").val(formatRupiah(data.bonus) ?? 0);
    
        $("#supply-pengiriman-form").data("action", "edit");
        $("#supply-pengiriman-form").data("id", data.mt_log_pengiriman_id);

        $("#supply-pengiriman-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#supplyPengirimanModal .modal-title").text("Tambah Data Pengiriman");
    
        $("#supply-pengiriman-form").data("action", "add");
        $("#supply-pengiriman-form").removeData("id");
    }

    $("#supplyPengirimanModal").modal("show");
}