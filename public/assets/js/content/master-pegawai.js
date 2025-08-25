"use strict";

$((function () {
    applyFilter();

    $('#applyPegawaiFilter').click(function() {
        var gudang = $('#gudang_id').val();
        var role = $('#role_id').val();

        applyFilter(gudang, role);
    });
    
    $('#resetPegawaiFilter').click(function() {
        $('#gudang_id, #role_id').val('').trigger('change');

        applyFilter();
    });
}));

function applyFilter(gudang = null, role = null) {
    getDataPegawai(gudang, role).done(function(response) {
        initializePegawaiTable(response.data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataPegawai(gudang = null, role = null) {
    return $.ajax({
        url: base_url + '/master/pegawai/data',
        method: 'GET',
        data: {
            gudang: gudang,
            role: role
        },
        dataType: 'json'
    });
}

function initializePegawaiTable(data) {
    var dkt = $(".dt-pegawaiTable");

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
                { data: 'kd_pegawai', defaultContent: "-" },
                { data: 'nama_pegawai', defaultContent: "-" },
                { data: 'role_name', defaultContent: "-" },
                { data: 'gudang_name', defaultContent: "-" },
                { data: 'jenis_kelamin', defaultContent: "-" },
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
                    targets: [3, 4],
                    render: function (data) {
                        return (data === null || data === undefined || data === "") ? '-' : data;
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row) {
                        return data === 'L' ? 'Laki-laki' : (data === 'P' ? 'Perempuan' : '-');
                    }
                },
                {
                    targets: 6,
                    className: 'no-export',
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle dt-actions text-nowrap',
                    width: '72px',
                    render: function (data, type, row, meta) {
                        var actionPegawaiButton = '<div class="d-flex align-items-center gap-1">';

                        actionPegawaiButton += '<button type="button" class="btn btn-icon btn-edit-pegawai" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Detail Pegawai" ' +
                            'data-bs-target="#detailPegawaiModal" ' +
                            'data-id="' + row.kd_pegawai + '"> ' +
                            '<i class="text-primary bx bx-pencil fs-5"></i>' +
                        '</button>';
                        
                        actionPegawaiButton += '<button type="button" class="btn btn-icon btn-hapus-pegawai" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Hapus Pegawai" ' +
                            'data-bs-target="#hapusPegawaiModal" ' +
                            'data-id="' + row.kd_pegawai + '"> ' +
                            '<i class="text-danger bx bx-trash fs-5"></i>' +
                        '</button>';

                        actionPegawaiButton += '</div>';
                    
                        return actionPegawaiButton;
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