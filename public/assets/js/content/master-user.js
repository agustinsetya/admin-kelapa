"use strict";

$((function () {
    getDataPegawai().done(function(response) {
        initializePegawaiTable(response.data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });

    $('#applyPegawaiFilter').click(function() {
        var gudang = $('#gudang_id').val();
        var role = $('#role_id').val();

        getDataPegawai(gudang, role).done(function(response) {
            initializePegawaiTable(response.data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Request failed: " + textStatus + ", " + errorThrown);
        });
    });
}));

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

    if ($.fn.dataTable.isDataTable(dkt)) {
        dkt.DataTable().clear();

        if (!isDataInvalid) {
            dkt.DataTable().rows.add(data);
        }

        console.log("gagal");

        dkt.DataTable().draw();
    } else {
        console.log("berhasil");
        dkt.DataTable({
            data: data,
            columns: [
                { data: null },
                { data: 'kd_pegawai' },
                { data: 'nama_pegawai' },
                { data: 'role_name',   defaultContent: '-' },
                { data: 'gudang_name', defaultContent: '-' },
                { data: 'jenis_kelamin' },
                { data: null }
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
                    targets: 5,
                    render: function (data, type, row) {
                        return data === 'L' ? 'Laki-laki' : (data === 'P' ? 'Perempuan' : '-');
                    }
                },
                {
                    targets: 6,
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        var actionPegawaiButton = '<div class="d-flex">';

                        actionPegawaiButton += '<button class="btn btn-sm btn-icon update-status ' +
                            (row.status == '1' ? 'active' : '') + '" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'data-id="' + row.mt_user_id + '" ' +
                            'title="' + (row.status == '0' ? 'Aktifkan User' : 'Nonaktifkan User') + '">' +
                            (row.status == '0' ? 
                                '<i class="text-secondary fa fa-lock"></i>' : 
                                '<i class="text-info fa fa-unlock"></i>') +
                        '</button>';

                        actionPegawaiButton += '<button class="btn btn-sm btn-icon detail-pegawai" id="btn-edit-gelombang" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Detail Gelombang" ' +
                            'data-bs-target="#detailGelombangModal" ' +
                            'data-id="' + row.id + '"> ' +
                            '<i class="text-primary ti ti-pencil"></i>' +
                        '</button>';

                        actionPegawaiButton += '</div>';
                    
                        return actionPegawaiButton;
                    }
                }
            ],
            scrollY: "300px",
            scrollX: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
        });
    }
}