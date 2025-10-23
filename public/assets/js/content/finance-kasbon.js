"use strict";

$((function () {
    $("#kb_gudang_id").on("change", function () {
        let gudangId = $(this).val();

        reloadDropdownPegawaiByGudang('#kb_pegawai_id', gudangId, roleScope);
    });

    applyFilterKasbon();

    $("#btn-tambah-kasbon").on("click", function () {
        document.getElementById('financeKasbonModal')
        openModalKasbon("add");
    });

    $('#applyKasbonFilter').click(function() {
        const gudang = $('#fn_gudang_id').val() || null;

        applyFilterKasbon(gudang);
    });
    
    $('#resetKasbonFilter').click(function() {
        $('#fn_gudang_id').val('').trigger('change');

        applyFilterKasbon();
    });

    $("body").on("click", "#btn-save-kasbon", function (e) {
        e.preventDefault();
    
        var form = $("#finance-kasbon-form")[0];
        var action = $("#finance-kasbon-form").data("action");
        var id = $("#finance-kasbon-form").data("id") ?? '';
    
        const gudangValue = $("#kb_gudang_id").val();
        if (!gudangValue) {
            $("#kb_gudang_id").addClass("is-invalid");
        } else {
            $("#kb_gudang_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const pegawaiValue = $("#kb_pegawai_id").val();
        if (!pegawaiValue) {
            $("#kb_pegawai_id").addClass("is-invalid");
        } else {
            $("#kb_pegawai_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const statusValue = $("#kb_tipe").val();
        if (!statusValue) {
            $("#kb_tipe").addClass("is-invalid");
        } else {
            $("#kb_tipe").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $jumlah = $("#jumlah");
        $jumlah.val(unmaskRupiah($jumlah.val()));

        let payload = $(form).serialize();
        
        showBtnLoading("btn-save-kasbon", { text: "Menyimpan Data..." });

        $.ajax({
            url: base_url + '/finance/kasbon/add',
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
                successAlert('Simpan Data Kasbon Berhasil!');
                $("#financeKasbonModal").modal("hide");

                applyFilterKasbon();
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
            resetButton("btn-save-kasbon","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterKasbon(gudang = null) {
    getDataKasbon(gudang).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeFinanceKasbonTable(rows);
        reloadDropdownGudang("#kb_gudang_id", roleScope, penempatan);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataKasbon(gudang = null) {
    return $.ajax({
        url: base_url + '/finance/kasbon/data',
        method: 'GET',
        data: {
            gudang_id: gudang,
        },
        dataType: 'json'
    });
}

function initializeFinanceKasbonTable(data) {
    const $dpl = $(".dt-kasbonTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dpl)) {
        const dt = $dpl.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dpl.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: null, defaultContent: "" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'total_pinjaman', defaultContent: "-" },
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
                targets: 1,
                render: function (data, type, row) {
                    var tgKasbon = row.updated_at ? formatTanggal(row.updated_at) : "-";

                    var statusMap = {
                        BELUM_LUNAS: { title: "Belum Lunas", class: "badge-soft-warning" },
                        LUNAS: { title: "Lunas", class: "badge-soft-success" },
                    };

                    var meta = statusMap[row.status] || { title: "Unknown", class: "badge-soft-secondary" };

                    return `
                        <div class="d-flex flex-column align-items-start">
                            <span>${tgKasbon}</span>
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
                            <small class="fst-italic text-muted">${row.nama_gudang || '-'}</small>
                        </div>
                    `;
            
                    return html;
                }
            },
            {
                targets: 3,
                render: function(data, type, row, meta) {
                    let totalPinjaman = formatRupiah(data);
                    let sisaPinjaman = formatRupiah(row.sisa_pinjaman);

                    let sisaPinjamanClass = row.sisa_pinjaman > 0 ? 'text-warning' : 'text-success';
            
                    let html = `
                        <div>${totalPinjaman || '0'}</div>
                        <div>
                            <small class="fst-italic ${sisaPinjamanClass}">
                                Sisa Pinjaman: ${sisaPinjaman || '0'}
                            </small>
                        </div>
                    `;
            
                    return html;
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
                    var actionKasbonButton = '<div class="d-flex align-items-center gap-1">';

                    actionKasbonButton += '<button type="button" class="btn btn-icon btn-detail-log-kasbon" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Detail Kasbon" ' +
                        'onclick="window.location.href=\'' + base_url + '/finance/kasbon/detail/' + row.kd_pegawai + '\'"> ' +  // Arahkan ke route kasbon/detail dengan ID
                        '<i class="text-primary bx bx-info-circle fs-5"></i>' +
                    '</button>';

                    actionKasbonButton += '</div>';
                
                    return actionKasbonButton;
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

function openModalKasbon(mode, data = null) {
    $("#finance-kasbon-form")[0].reset();
    $("#finance-kasbon-form").removeClass("was-validated");
    $("#kb_gudang_id, #kb_pegawai_id, #kb_tipe").val(null).trigger("change").removeClass("is-invalid is-valid");

    $(".fake-input").remove(); 
    $("#kb_gudang_id, #kb_pegawai_id").show();

    $("#finance-kasbon-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#financeKasbonModal .modal-title").text("Edit Data Kasbon");
    
        $("#tg_kasbon")
            .val(data.tg_kasbon.split("T")[0])
            .prop("readonly", true);
        
        $("#kb_gudang_id")
            .val(data.gudang_id)
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                       value="${data.nama_gudang}">
            `);

        $("#kb_pegawai_id")
            .hide()
            .after(`
                <input type="text" readonly class="form-control fake-input" 
                    value="${data.nama_pegawai}">
                <input type="hidden" name="kb_pegawai_id" value="${data.kd_pegawai}">
            `);
        $("#jumlah").val(formatRupiah(data.jumlah)).prop("readonly", true);
        $("#kb_tipe").val(data.status).trigger("change");

        $("#finance-kasbon-form").data("action", "edit");
        $("#finance-kasbon-form").data("id", data.mt_kasbon_id);

        $("#finance-kasbon-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#financeKasbonModal .modal-title").text("Tambah Data Kasbon");

        $("#tg_pengeluaran, #jumlah").prop("readonly", false);
        $("#kb_gudang_id, #kb_pegawai_id")
            .val("")
            .trigger("change");

        reloadDropdownGudang("#kb_gudang_id", roleScope, penempatan);
    
        $("#finance-kasbon-form").data("action", "add");
        $("#finance-kasbon-form").removeData("id");
    }

    $("#financeKasbonModal").modal("show");
}