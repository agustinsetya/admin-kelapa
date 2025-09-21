"use strict";

$((function () {
    initRangePicker('tg_pengeluaran_filter');

    $("#peng_gudang_id").on("change", function () {
        let gudangId = $(this).val();

        reloadDropdownPegawaiByGudang('#peng_pegawai_id', gudangId);
    });

    applyFilterPengeluaran();

    $("#btn-tambah-pengeluaran").on("click", function () {
        document.getElementById('financePengeluaranModal')
        openModalPengeluaran("add");
    });

    $(document).on('click', '.btn-edit-pengeluaran', function () {
        getDetailFinancePengeluaran(this);
    });

    $('#applyPengeluaranFilter').click(function() {
        const { start, end } = getIsoRange('tg_pengeluaran_filter');
        const gudang = $('#fn_gudang_id').val() || null;

        applyFilterPengeluaran(gudang, start, end);
    });
    
    $('#resetPengeluaranFilter').click(function() {
        $('#fn_gudang_id').val('').trigger('change');

        const $el = $('#tg_pengeluaran_filter');
        $el.val('');
        if (hasDRP()) {
            const drp = $el.data('daterangepicker');

            if (drp) {
                drp.setStartDate(moment());
                drp.setEndDate(moment());

                $el.trigger('cancel.daterangepicker', drp);
            }
        }

        applyFilterPengeluaran();
    });

    $("body").on("click", "#btn-save-pengeluaran", function (e) {
        e.preventDefault();
    
        var form = $("#finance-pengeluaran-form")[0];
        var action = $("#finance-pengeluaran-form").data("action");
        var id = $("#finance-pengeluaran-form").data("id") ?? '';
    
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
        
        const ktgPengeluaranValue = $("#peng_ktg_pengeluaran_id").val();
        if (!ktgPengeluaranValue) {
            $("#peng_ktg_pengeluaran_id").addClass("is-invalid");
        } else {
            $("#peng_ktg_pengeluaran_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const statusValue = $("#peng_status").val();
        if (!statusValue) {
            $("#peng_status").addClass("is-invalid");
        } else {
            $("#peng_status").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $biaya = $("#biaya");
        $biaya.val(unmaskRupiah($biaya.val()));

        let url = '/finance/pengeluaran/add';
        if (action === 'edit') url = '/finance/pengeluaran/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-pengeluaran", { text: "Menyimpan Data..." });

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
                successAlert('Simpan Data Pengeluaran Berhasil!');
                $("#financePengeluaranModal").modal("hide");

                applyFilterPengeluaran();
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
            resetButton("btn-save-pengeluaran","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterPengeluaran(gudang = null, start = '', end = '') {
    getDataPengeluaran(gudang, start, end).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeFinancePengeluaranTable(rows);
        reloadDropdownGudang("#peng_gudang_id");
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataPengeluaran(gudang = null, start = '', end = '') {
    return $.ajax({
        url: base_url + '/finance/pengeluaran/data',
        method: 'GET',
        data: {
            gudang_id: gudang,
            start_date: start,
            end_date: end
        },
        dataType: 'json'
    });
}

function initializeFinancePengeluaranTable(data) {
    const $dpl = $(".dt-pengeluaranTable").first();
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
            { data: 'nama_ktg_pengeluaran', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'jumlah', defaultContent: "-" },
            { data: 'biaya', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
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
                    var tgPengeluaran = row.tg_pengeluaran ? formatTanggal(row.tg_pengeluaran) : "-";

                    var statusMap = {
                        BELUM_BAYAR: { title: "Belum Bayar", class: "badge-soft-warning" },
                        SUDAH_BAYAR: { title: "Sudah Bayar", class: "badge-soft-success" },
                    };

                    var meta = statusMap[row.status] || { title: "Unknown", class: "badge-soft-secondary" };

                    return `
                        <div class="d-flex flex-column align-items-start">
                            <span>${tgPengeluaran}</span>
                            <span class="badge ${meta.class} font-size-12">${meta.title}</span>
                        </div>
                    `;
                }
            },
            {
                targets: 5,
                render: function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            },
            {
                targets: 7,
                className: 'no-export',
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: function (data, type, row, meta) {
                    var actionPengeluaranButton = '<div class="d-flex align-items-center gap-1">';

                    actionPengeluaranButton += '<button type="button" class="btn btn-icon btn-edit-pengeluaran" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Detail Pengeluaran" ' +
                        'data-bs-target="#detailPengeluaranModal" ' +
                        'data-id="' + row.mt_pengeluaran_id + '"> ' +
                        '<i class="text-primary bx bx-pencil fs-5"></i>' +
                    '</button>';

                    actionPengeluaranButton += '</div>';
                
                    return actionPengeluaranButton;
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

function getDetailFinancePengeluaran(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/finance/pengeluaran/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPengeluaran("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPengeluaran(mode, data = null) {
    $("#finance-pengeluaran-form")[0].reset();
    $("#finance-pengeluaran-form").removeClass("was-validated");
    $("#peng_ktg_pengeluaran_id, #peng_gudang_id, #peng_pegawai_id, #peng_status").val(null).trigger("change").removeClass("is-invalid is-valid");

    $(".fake-input").remove(); 
    $("#peng_gudang_id, #peng_pegawai_id").show();

    $("#finance-pengeluaran-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#financePengeluaranModal .modal-title").text("Edit Data Pengeluaran");
    
        $("#tg_pengeluaran")
            .val(data.tg_pengeluaran.split("T")[0])
            .prop("readonly", true);
        $("#peng_ktg_pengeluaran_id").val(data.ktg_pengeluaran_id).trigger("change");
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
        $("#jumlah").val(data.jumlah);
        $("#biaya").val(formatRupiah(data.biaya));
        $("#peng_status").val(data.status).trigger("change");
    
        $("#finance-pengeluaran-form").data("action", "edit");
        $("#finance-pengeluaran-form").data("id", data.mt_pengeluaran_id);

        $("#finance-pengeluaran-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#financePengeluaranModal .modal-title").text("Tambah Data Pengeluaran");

        $("#tg_pengeluaran").prop("readonly", false);
        $("#peng_gudang_id, #peng_pegawai_id")
            .val("")
            .trigger("change");

        reloadDropdownGudang("#peng_gudang_id");
    
        $("#finance-pengeluaran-form").data("action", "add");
        $("#finance-pengeluaran-form").removeData("id");
    }

    $("#financePengeluaranModal").modal("show");
}