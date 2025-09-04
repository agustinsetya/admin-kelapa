"use strict";

let _activeFilter = {
    gudang: null,
    start_date: '',
    end_date: ''
};

$((function () {
    initRangePicker('tg_periode_filter');
    applyFilterGajiDriver();

    $('#applyGajiDriverFilter').click(function() {
        const { start, end } = getIsoRange('tg_periode_filter');
        const gudang = $('#fn_gudang_id').val() || null;

        _activeFilter = { gudang, start_date: start, end_date: end };

        applyFilterGajiDriver(gudang, start, end);
    });
    
    $('#resetGajiDriverFilter').click(function() {
        $('#fn_gudang_id').val('').trigger('change');

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

        applyFilterGajiDriver();
    });

    $("body").on("click", "#btn-proses-gaji-driver", function () {
        let selectedData = [];
        const buttonId = this.id;
    
        $(".proses-gaji-driver:checked").each(function () {
            const dataPeg = String($(this).data("id") || '');
            const [kdDriver, gudangId] = dataPeg.split("#");
            if (kdDriver && gudangId) {
                selectedData.push({ kdDriver, gudangId });
            }
        });
    
        if (!selectedData.length) {
            errorAlert("Tidak ada data karyawan yang dipilih!");
            return;
        }

        let { start_date, end_date } = _activeFilter;
        if (!start_date || !end_date) {
            const r = getIsoRange('tg_periode_filter');
            start_date = r.start;
            end_date   = r.end;
        }

        showBtnLoading(buttonId, { text: "Proses Gaji Driver..." });

        $.ajax({
            url: base_url + '/finance/gaji-driver/add',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({ data: selectedData, start_date, end_date })
        })
        .done(function (response) {
            if (response?.csrf?.name && response?.csrf?.hash) {
                $('meta[name="csrf-token-name"]').attr('content', response.csrf.name);
                $('meta[name="csrf-token"]').attr('content', response.csrf.hash);
            }

            if (response?.success) {
                successAlert('Proses Gaji Driver Berhasil!');
                applyFilterGajiDriver(_activeFilter.gudang, _activeFilter.start_date, _activeFilter.end_date);
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
            resetButton(buttonId,"Proses Gaji","btn btn-warning waves-effect waves-light");
        });
    });
}));

function applyFilterGajiDriver(gudang = null, start = '', end = '') {
    if (start || end || gudang !== null) {
        _activeFilter = { gudang, start_date: start, end_date: end };
    }

    getDataGajiDriver(_activeFilter.gudang, _activeFilter.start_date, _activeFilter.end_date).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeFinanceGajiDriverTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataGajiDriver(gudang = null, start = '', end = '') {
    return $.ajax({
        url: base_url + '/finance/gaji-driver/data',
        method: 'GET',
        data: {
            gudang_id: gudang,
            start_date: start,
            end_date: end
        },
        dataType: 'json'
    });
}

function initializeFinanceGajiDriverTable(data) {
    const $dgp = $(".dt-gajiDriverTable").first();
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
            { data: 'nama_driver', defaultContent: "-" },
            { data: 'upah_perjalanan', defaultContent: "-" },
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
                    var namaDriver = data ? data : "-";
                    var gudang = row.nama_gudang ? row.nama_gudang : "-";

                    return `
                        <div class="d-flex flex-column align-items-start">
                            <span>${namaDriver}</span>
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
                        '<div class="d-flex flex-column align-items-center"><input type="checkbox" class="form-check-input proses-gaji-driver" data-id="' +
                        row.kd_driver + '#' + row.gudang_id + '"/></div>'
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