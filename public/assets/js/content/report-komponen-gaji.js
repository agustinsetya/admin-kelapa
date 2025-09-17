"use strict";

$((function () {
    if (roleScope === 'all') {
        applyFilter();
    } else if (roleScope === 'gudang') {
        getDetailKomponenGaji();
    } else {
        console.error('Role scope tidak valid');
    }
}));

function applyFilter() {
    getDataKomponenGaji().done(function(response) {
        initializeKomponenGajiTable(response.data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataKomponenGaji() {
    return $.ajax({
        url:  base_url + 'report/komponen-gaji/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeKomponenGajiTable(data) {
    var dkt = $(".dt-komponenGajiTable");

    var isDataInvalid = !Array.isArray(data) || data.length === 0 || data.status === false;
    var safeData = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable(dkt)) {
        dkt.DataTable().clear();

        if (!isDataInvalid) dkt.DataTable().rows.add(safeData);

        dkt.DataTable().draw();
    } else {
        dkt.DataTable({
            data: isDataInvalid ? [] : safeData,
            columns: [
                { data: null, defaultContent: "" },
                { data: 'created_at', defaultContent: "-" },          
                { data: 'nama_gudang', defaultContent: "-" },
                { data: 'takaran_daging', defaultContent: "-" },          
                { data: 'upah_takaran_daging', defaultContent: "-" },          
                { data: 'takaran_kopra', defaultContent: "-" },          
                { data: 'upah_takaran_kopra', defaultContent: "-" },          
                { data: 'takaran_kulit', defaultContent: "-" },          
                { data: 'upah_takaran_kulit', defaultContent: "-" },          
                { data: null, defaultContent: "-" },          
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
                    render: function(data, type, row, meta) {
                        return formatTanggal(data);
                    }
                },
                { targets: [3,5,7], render: (d) => d ? formatAngkaDecimal(d) : "-" },
                {
                    targets: [4,6,8],
                    render: function(data, type, row, meta) {
                        return formatRupiah(data);
                    }
                },
                {
                    targets: 9,
                    render: (data, type, row) => {
                        const distribusi = `${formatRupiah(row.gaji_driver_distribusi)}`;
                        const truk  = `${formatRupiah(row.gaji_driver_ngepok_truk)}`;
                        const pickup  = `${formatRupiah(row.gaji_driver_ngepok_pickup)}`;
                
                        return `
                            Distribusi : ${distribusi}<br>
                            Ngepok Truk  : ${truk}<br>
                            Ngepok Pickup  : ${pickup}
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
}