"use strict";

$((function () {
    initRangePicker('tg_kasbon_filter');

    applyFilterDetailKasbon();

    $('#applyDetailKasbonFilter').click(function() {
        const { start, end } = getIsoRange('tg_kasbon_filter');

        applyFilterDetailKasbon(start, end);
    });
    
    $('#resetDetailKasbonFilter').click(function() {
        const $el = $('#tg_kasbon_filter');
        $el.val('');
        if (hasDRP()) {
            const drp = $el.data('daterangepicker');

            if (drp) {
                drp.setStartDate(moment());
                drp.setEndDate(moment());

                $el.trigger('cancel.daterangepicker', drp);
            }
        }

        applyFilterDetailKasbon();
    });
    
    $('#backKasbonFilter').click(function() {
        window.location.href = base_url + '/finance/kasbon';
    });    
}));

function applyFilterDetailKasbon(start = '', end = '') {
    getDataDetailKasbon(start, end).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeFinanceDetailKasbonTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataDetailKasbon(start = '', end = '') {
    return $.ajax({
        url: base_url + '/finance/kasbon/detail/data',
        method: 'GET',
        data: {
            start_date: start,
            end_date: end,
            detailPegawaiId: detailPegawaiId
        },
        dataType: 'json'
    });
}

function initializeFinanceDetailKasbonTable(data) {
    const $dlk = $(".dt-logKasbonTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dlk)) {
        const dt = $dlk.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dlk.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: null, defaultContent: "" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'jumlah', defaultContent: "-" },          
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
                    var tgKasbon = row.tg_kasbon ? formatTanggal(row.tg_kasbon) : "-";

                    var statusMap = {
                        PEMINJAMAN: { title: "Peminjaman", class: "badge-soft-warning" },
                        PEMBAYARAN: { title: "Pembayaran Cicilan", class: "badge-soft-success" },
                    };

                    var meta = statusMap[row.tipe] || { title: "Unknown", class: "badge-soft-secondary" };

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