"use strict";

let _activeFilter = {
    gudang: null,
    start_date: '',
    end_date: ''
};

$((function () {
    initRangePicker('tg_proses_gaji_filter');
    applyFilterGajiPegawai();

    $('#applyReportGajiPegawaiFilter').click(function() {
        const { start, end } = getIsoRange('tg_proses_gaji_filter');
        const gudang = $('#rp_gudang_id').val() || null;

        _activeFilter = { gudang, start_date: start, end_date: end };

        applyFilterGajiPegawai(gudang, start, end);
    });
    
    $('#resetReportGajiPegawaiFilter').click(function() {
        $('#rp_gudang_id').val('').trigger('change');

        const $el = $('#tg_proses_gaji_filter');
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
}));

function applyFilterGajiPegawai(gudang = null, start = '', end = '') {
    if (start || end || gudang !== null) {
        _activeFilter = { gudang, start_date: start, end_date: end };
    }
    getDataGajiPegawai(_activeFilter.gudang, _activeFilter.start_date, _activeFilter.end_date)
        .done(function(response) {
            const rows = Array.isArray(response?.data) ? response.data : [];
            initializeReportGajiPegawaiTable(rows);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Request failed: " + textStatus + ", " + errorThrown);
        });
}

function getDataGajiPegawai(gudang = null, start = '', end = '') {
    return $.ajax({
        url: base_url + 'report/gaji-pegawai/data',
        method: 'GET',
        data: {
            gudang_id: gudang,
            start_date: start,
            end_date: end
        },
        dataType: 'json'
    });
}

function initializeReportGajiPegawaiTable(data) {
    const $dgp = $(".dt-reportGajiPegawaiTable").first();
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
            { data: 'tg_proses_gaji', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'total_upah_daging', defaultContent: "-" },
            { data: 'total_upah_kopra', defaultContent: "-" },
            { data: 'total_bonus', defaultContent: "-" },
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
            { targets: 1, render: (d) => d ? formatTanggal(d) : "-" },
            {
                targets: [4,5,6,7],
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