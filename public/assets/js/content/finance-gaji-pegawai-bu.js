"use strict";

$((function () {
    initRangePicker('tg_periode_filter');
    applyFilterGajiPegawai();

    $("#btn-tambah-pengeluaran").on("click", function () {
        document.getElementById('financeGajiPegawaiModal')
        openModalGajiPegawai("add");
    });

    $(document).on('click', '.btn-edit-pengeluaran', function () {
        getDetailFinanceGajiPegawai(this);
    });

    $('#applyGajiPegawaiFilter').click(function() {
        const { start, end } = getIsoRange('tg_periode_filter');
        const gudang = $('#gp_gudang_id').val() || null;

        applyFilterGajiPegawai(gudang, start, end);
    });
    
    $('#resetGajiPegawaiFilter').click(function() {
        $('#gp_gudang_id').val('').trigger('change');

        const $el = $('#tg_periode_filter');
        $el.val('');
        if (hasDRP()) {
            const drp = $el.data('daterangepicker');

            if (drp) {
                drp.setStartDate(moment());
                drp.setEndDate(moment());

                $el.trigger('cancel.daterangepicker', drp);
            }
        }

        applyFilterGajiPegawai();
    });

    $("body").on("click", "#btn-save-gaji-pegawai", function (e) {
        e.preventDefault();
    
        var form = $("#finance-gaji-pegawai-form")[0];
        var action = $("#finance-gaji-pegawai-form").data("action");
        var id = $("#finance-gaji-pegawai-form").data("id") ?? '';
    
        const gudangValue = $("#gp_gudang_id").val();
        if (!gudangValue) {
            $("#gp_gudang_id").addClass("is-invalid");
        } else {
            $("#gp_gudang_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const pegawaiValue = $("#gp_pegawai_id").val();
        if (!pegawaiValue) {
            $("#gp_pegawai_id").addClass("is-invalid");
        } else {
            $("#gp_pegawai_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const statusValue = $("#gp_status").val();
        if (!statusValue) {
            $("#gp_status").addClass("is-invalid");
        } else {
            $("#gp_status").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $biaya = $("#biaya");
        $biaya.val(unmaskRupiah($biaya.val()));

        let url = '/finance/gaji-pegawai/add';
        if (action === 'edit') url = '/finance/gaji-pegawai/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-gaji-pegawai", { text: "Menyimpan Data..." });

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
                alert('Simpan Data Gaji Pegawai Berhasil!');
                $("#financeGajiPegawaiModal").modal("hide");

                applyFilterGajiPegawai();
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
            resetButton("btn-save-gaji-pegawai","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterGajiPegawai(gudang = null, start = '', end = '') {
    getDataGajiPegawai(gudang, start, end).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeFinanceGajiPegawaiTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataGajiPegawai(gudang = null, start = '', end = '') {
    return $.ajax({
        url: base_url + '/finance/gaji-pegawai/data',
        method: 'GET',
        data: {
            gudang_id: gudang,
            start_date: start,
            end_date: end
        },
        dataType: 'json'
    });
}

function initializeFinanceGajiPegawaiTable(data) {
    const $dgp = $(".dt-gajiPegawaiTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dgp)) {
        const dt = $dgp.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dgp.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: null, defaultContent: "" },
            { data: 'periode', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'upah_produksi', defaultContent: "-" },
            { data: 'lembur', defaultContent: "-" },
            { data: 'total_netto', defaultContent: "-" },
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
                    var tgPembayaran = row.tg_pembayaran ? formatTanggal(row.tg_pembayaran) : "-";

                    var statusMap = {
                        BELUM_BAYAR: { title: "Belum Bayar", class: "badge-soft-primary" },
                        PENDING: { title: "Pending", class: "badge-soft-warning" },
                        DIBAYAR: { title: "Sudah Dibayar", class: "badge-soft-success" },
                        GAGAL: { title: "Gagal", class: "badge-soft-danger" },
                    };

                    var meta = statusMap[row.status_pembayaran] || { title: "Unknown", class: "badge-soft-secondary" };

                    return `
                        <div class="d-flex flex-column align-items-start">
                            <span>${tgPembayaran}</span>
                            <span class="badge ${meta.class} font-size-12">${meta.title}</span>
                        </div>
                    `;
                }
            },
            {
                targets: [4-6],
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
                    var actionGajiPegawaiButton = '<div class="d-flex align-items-center gap-1">';

                    actionGajiPegawaiButton += '<button type="button" class="btn btn-icon btn-edit-pengeluaran" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Detail Pengeluaran" ' +
                        'data-bs-target="#detailPengeluaranModal" ' +
                        'data-id="' + row.mt_gaji_id + '"> ' +
                        '<i class="text-primary bx bx-pencil fs-5"></i>' +
                    '</button>';

                    actionGajiPegawaiButton += '</div>';
                
                    return actionGajiPegawaiButton;
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

function getDetailFinanceGajiPegawai(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/finance/gaji-pegawai/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalGajiPegawai("edit", response.data[0]);
            } else {
                alert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalGajiPegawai(mode, data = null) {
    $("#finance-gaji-pegawai-form")[0].reset();
    $("#finance-gaji-pegawai-form").removeClass("was-validated");
    $("#gp_gudang_id, #gp_pegawai_id, #gp_status").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#finance-gaji-pegawai-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#financeGajiPegawaiModal .modal-title").text("Edit Data Pengeluaran");
    
        $("#tg_pembayaran").val(data.tg_pembayaran.split("T")[0]);
        $("#peng_ktg_pengeluaran_id").val(data.ktg_pengeluaran_id).trigger("change");
        $("#peng_gudang_id").val(data.gudang_id).trigger("change");
        $("#peng_pegawai_id").val(data.kd_pegawai).trigger("change");
        $("#jumlah").val(data.jumlah);
        $("#biaya").val(formatRupiah(data.biaya));
        $("#peng_status").val(data.status).trigger("change");
    
        $("#finance-gaji-pegawai-form").data("action", "edit");
        $("#finance-gaji-pegawai-form").data("id", data.mt_gaji_id);

        $("#finance-gaji-pegawai-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#financeGajiPegawaiModal .modal-title").text("Tambah Data Pengeluaran");
    
        $("#finance-gaji-pegawai-form").data("action", "add");
        $("#finance-gaji-pegawai-form").removeData("id");
    }

    $("#financeGajiPegawaiModal").modal("show");
}