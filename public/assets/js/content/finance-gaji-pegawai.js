"use strict";

let _activeFilter = {
    gudang: null,
    start_date: '',
    end_date: ''
};

$((function () {
    initRangePicker('tg_periode_filter');
    applyFilterGajiPegawai();

    $('#applyGajiPegawaiFilter').click(function() {
        const { start, end } = getRangeISO('#tg_periode_filter');
        const gudang = $('#gp_gudang_id').val() || null;

        _activeFilter = { gudang, start_date: start, end_date: end };

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

        _activeFilter = { gudang: null, start_date: '', end_date: '' };

        applyFilterGajiPegawai();
    });

    $("body").on("click", "#btn-proses-gaji-pegawai", function () {
        let selectedData = [];
        const buttonId = this.id;
    
        $(".proses-gaji-pegawai:checked").each(function () {
            const dataPeg = String($(this).data("id") || '');
            const [kdPegawai, gudangId] = dataPeg.split("#");
            if (kdPegawai && gudangId) {
                selectedData.push({ kdPegawai, gudangId });
            }
        });
    
        if (!selectedData.length) {
            alert("Tidak ada data karyawan yang dipilih!");
            return;
        }

        // Ambil range dari state aktif; fallback ke input DRP kalau belum pernah apply
        let { start_date, end_date } = _activeFilter;
        if (!start_date || !end_date) {
            const r = getRangeISO('#tg_periode_filter');
            start_date = r.start;
            end_date   = r.end;
        }
        if (!start_date || !end_date) {
            alert('Pilih periode terlebih dahulu.');
            return;
        }

        const csrfName = $('meta[name="csrf-token-name"]').attr('content');
        const csrfHash = $('meta[name="csrf-token"]').attr('content');

        showBtnLoading(buttonId, { text: "Proses Gaji Pegawai..." });

        $.ajax({
            url: base_url + '/finance/gaji-pegawai/add',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify({ data: selectedData, start_date, end_date, [csrfName]: csrfHash }),
            // data: {
            //     data: selectedData,
            //     start_date,
            //     end_date,
            //     [csrfName]: csrfHash
            // }
        })
        .done(function (response) {
            if (response?.csrf?.name && response?.csrf?.hash) {
                $('meta[name="csrf-token-name"]').attr('content', response.csrf.name);
                $('meta[name="csrf-token"]').attr('content', response.csrf.hash);
            }

            if (response?.success) {
                alert('Proses Gaji Pegawai Berhasil!');
                applyFilterGajiPegawai(_activeFilter.gudang, _activeFilter.start_date, _activeFilter.end_date);
            } else {
                alert(response?.message || 'Proses Gaji Pegawai Gagal!');
            }
        })
        .fail(function (jqXHR) {
            try {
                const res = jqXHR.responseJSON;
                if (res?.csrf?.name && res?.csrf?.hash) {
                    $('meta[name="csrf-token-name"]').attr('content', res.csrf.name);
                    $('meta[name="csrf-token"]').attr('content', res.csrf.hash);
                }
                const msg = res?.message || res?.error || 'Terjadi kesalahan saat menyimpan';
                alert(msg);
            } catch (e) {
                alert('Terjadi kesalahan. Cek konsol.');
                console.error('Save error:', jqXHR.status, jqXHR.responseText);
            }
        })
        .always(function () {
            resetButton(buttonId,"Proses Gaji","btn btn-warning waves-effect waves-light");
        });
    });
}));

function applyFilterGajiPegawai(gudang = null, start = '', end = '') {
    if (start || end || gudang !== null) {
        _activeFilter = { gudang, start_date: start, end_date: end };
    }
    getDataGajiPegawai(_activeFilter.gudang, _activeFilter.start_date, _activeFilter.end_date)
        .done(function(response) {
            const rows = Array.isArray(response?.data) ? response.data : [];
            initializeFinanceGajiPegawaiTable(rows);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
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
            { data: null, defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'upah_total_daging', defaultContent: "-" },
            { data: 'upah_total_kopra', defaultContent: "-" },
            { data: 'bonus_total', defaultContent: "-" },
            { data: 'total_gaji_bersih', defaultContent: "-" },
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
                render: function () {
                    const s = _activeFilter.start_date;
                    if (!s) return '-';
                    const m = moment(s, 'YYYY-MM-DD', true);
                    
                    return m.isValid() ? m.format('MMMM YYYY') : s;
                }
            },
            {
                targets: 2,
                render: function(data, type, row, meta) {
                    var namaPegawai = data ? data : "-";
                    var gudang = row.nama_gudang ? row.nama_gudang : "-";

                    return `
                        <div class="d-flex flex-column align-items-start">
                            <span>${namaPegawai}</span>
                            <span>${gudang}</span>
                        </div>
                    `;
                }
            },
            {
                targets: [3,4,5,6],
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
                    return (
                        '<div class="d-flex flex-column align-items-center"><input type="checkbox" class="form-check-input proses-gaji-pegawai" data-id="' +
                        row.kd_pegawai + '#' + row.gudang_id + '"/></div>'
                    );
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