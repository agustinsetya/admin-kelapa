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
}));

function applyFilterGajiDriver(gudang = null, start = '', end = '') {
    if (start || end || gudang !== null) {
        _activeFilter = { gudang, start_date: start, end_date: end };
    }

    getDataGajiDriver(_activeFilter.gudang, _activeFilter.start_date, _activeFilter.end_date).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeReportGajiDriverTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataGajiDriver(gudang = null, start = '', end = '') {
    return $.ajax({
        url: base_url + 'report/gaji-driver/data',
        method: 'GET',
        data: {
            gudang_id: gudang,
            start_date: start,
            end_date: end
        },
        dataType: 'json'
    });
}

function initializeReportGajiDriverTable(data) {
    const $drd = $(".dt-reportGajiDriverTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($drd)) {
        const dt = $drd.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $drd.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: null, defaultContent: "-" },
            { data: 'nama_driver', defaultContent: "-" },
            { data: 'upah_perjalanan', defaultContent: "-" },
            { data: 'bonus', defaultContent: "-" },
            { data: 'total_gaji_bersih', defaultContent: "-" },         
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
                targets: [3,4,5],
                render: function(data, type, row, meta) {
                    return formatRupiah(data);
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
            't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    });
}