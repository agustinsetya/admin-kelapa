"use strict";

$((function () {
    applyFilter();
}));

function applyFilter() {
    getDataGudang().done(function(response) {
        initializeGudangTable(response.data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataGudang() {
    return $.ajax({
        url: base_url + '/master/gudang/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeGudangTable(data) {
    var dkt = $(".dt-gudangTable");

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
                { data: 'nama', defaultContent: "-" },
                { data: 'status', defaultContent: "-" },
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
                    targets: 2,
                    render: function (data, type, row) {
                        return (
                            '<div class="d-flex flex-column align-items-center">' +
                                '<input type="checkbox" class="form-check-input status-keaktifan-gudang" data-gudangId="' +
                                row.m_gudang_id +
                                '" ' +
                                (row.status === "1" ? "checked" : "") +
                                " />" +
                            "</div>"
                        );
                    }
                },
                {
                    targets: 3,
                    className: 'no-export',
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle dt-actions text-nowrap',
                    width: '72px',
                    render: function (data, type, row, meta) {
                        var actionGudangButton = '<div class="d-flex align-items-center gap-1">';

                        actionGudangButton += '<button type="button" class="btn btn-icon btn-edit-gudang" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Detail Gudang" ' +
                            'data-bs-target="#detailGudangModal" ' +
                            'data-id="' + row.m_gudang_id + '"> ' +
                                '<i class="text-primary bx bx-pencil fs-5"></i>' +
                        '</button>';
                        
                        actionGudangButton += '<button type="button" class="btn btn-icon btn-hapus-gudang" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Hapus Gudang" ' +
                            'data-bs-target="#hapusGudangModal" ' +
                            'data-id="' + row.m_gudang_id + '"> ' +
                                '<i class="text-danger bx bx-trash fs-5"></i>' +
                        '</button>';

                        actionGudangButton += '</div>';
                    
                        return actionGudangButton;
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
}