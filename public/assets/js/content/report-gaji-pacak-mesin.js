"use strict";

let _activeFilter = {
    gudang: null,
    start_date: '',
    end_date: ''
};

$((function () {
    initRangePicker('tg_periode_filter');
    applyFilterGajiPacakMesin();

    $('#applyGajiPacakMesinFilter').click(function() {
        const { start, end } = getIsoRange('tg_periode_filter');
        const gudang = $('#fn_gudang_id').val() || null;

        _activeFilter = { gudang, start_date: start, end_date: end };

        applyFilterGajiPacakMesin(gudang, start, end);
    });
    
    $('#resetGajiPacakMesinFilter').click(function() {
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

        applyFilterGajiPacakMesin();
    });
}));

function applyFilterGajiPacakMesin(gudang = null, start = '', end = '') {
    if (start || end || gudang !== null) {
        _activeFilter = { gudang, start_date: start, end_date: end };
    }

    getDataGajiPacakMesin(_activeFilter.gudang, _activeFilter.start_date, _activeFilter.end_date).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeReportGajiPacakMesinTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataGajiPacakMesin(gudang = null, start = '', end = '') {
    return $.ajax({
        url: base_url + 'report/gaji-pacak-mesin/data',
        method: 'GET',
        data: {
            gudang_id: gudang,
            start_date: start,
            end_date: end
        },
        dataType: 'json'
    });
}

function initializeReportGajiPacakMesinTable(data) {
    const $drd = $(".dt-reportGajiPacakMesinTable").first();
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
            { data: 'tg_proses_gaji', defaultContent: "-" },
            { data: 'nama_pacak_mesin', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'total_upah_pacak_mesin', defaultContent: "-" },
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
                targets: [4,5,6],
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