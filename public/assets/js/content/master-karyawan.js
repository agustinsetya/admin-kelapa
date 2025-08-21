"use strict";

$((function () {
    getDataKaryawan().done(function(response) {
        initializeKaryawanTable(response.data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });

    $('#applyKaryawanFilter').click(function() {
        var gudang = $('#gudang_id').val();
        var role = $('#role_id').val();

        getDataKaryawan(gudang, role).done(function(response) {
            initializeKaryawanTable(response.data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Request failed: " + textStatus + ", " + errorThrown);
        });
    });
}));

function getDataKaryawan(gudang = null, role = null) {
    return $.ajax({
        url: '/master/karyawan/data',
        method: 'GET',
        data: {
            gudang: gudang,
            role: role
        },
        dataType: 'json'
    });
}

function initializeKaryawanTable(data) {
    var dkt = $(".dt-karyawanTable");

    var isDataInvalid = !Array.isArray(data) || data.length === 0 || data.status === false;

    if ($.fn.dataTable.isDataTable(dkt)) {
        dkt.DataTable().clear();

        if (!isDataInvalid) {
            dkt.DataTable().rows.add(data);
        }

        dkt.DataTable().draw();
    } else {
        dkt.DataTable({
            data: data,
            columns: [
                { data: " " },
                { data: 'kd_pegawai' },
                { data: 'nama_pegawai' },
                { data: 'role_name' },
                { data: 'gudang_name' },
                { data: 'email' },
                { data: 'jenis_kelamin' },
                { data: " " }
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
                    targets: 6,
                    render: function (data, type, row) {
                        return $data === 'L' ? 'Laki-laki' : ($data === 'P' ? 'Perempuan' : '-');
                    }
                },
                {
                    targets: 8,
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        var actionGelombangButton = '<div class="d-flex">';

                        actionGelombangButton += '<button class="btn btn-sm btn-icon update-status ' +
                            (row.aktif == '1' ? 'active' : '') + '" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'data-id="' + row.id + '" ' +
                            'title="' + (row.aktif == '0' ? 'Aktifkan Gelombang' : 'Nonaktifkan Gelombang') + '">' +
                            (row.aktif == '0' ? 
                                '<i class="text-secondary fa fa-lock"></i>' : 
                                '<i class="text-info fa fa-unlock"></i>') +
                        '</button>';

                        actionGelombangButton += '<button class="btn btn-sm btn-icon detail-gelombang" id="btn-edit-gelombang" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Detail Gelombang" ' +
                            'data-bs-target="#detailGelombangModal" ' +
                            'data-id="' + row.id + '"> ' +
                            '<i class="text-primary ti ti-pencil"></i>' +
                        '</button>';

                        actionGelombangButton += '</div>';
                    
                        return actionGelombangButton;
                    }
                }
            ],
            scrollY: "300px",
            scrollX: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
        });
    }
}