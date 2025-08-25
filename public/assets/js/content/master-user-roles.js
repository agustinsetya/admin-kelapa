"use strict";

$((function () {
    applyFilter();
}));

function applyFilter() {
    getDataUserRoles().done(function(response) {
        initializeUserRolesTable(response.data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataUserRoles() {
    return $.ajax({
        url: base_url + '/master/user-roles/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeUserRolesTable(data) {
    var dkt = $(".dt-userRolesTable");

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
                { data: 'role_scope', defaultContent: "-" },
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
                        return data === 'all' ? 'All' : (data === 'gudang' ? 'Gudang' : '-');
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
                        var actionUserRolesButton = '<div class="d-flex align-items-center gap-1">';

                        actionUserRolesButton += '<button type="button" class="btn btn-icon btn-edit-user-roles" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Detail User Roles" ' +
                            'data-bs-target="#detailUserRolesModal" ' +
                            'data-id="' + row.m_role_id + '"> ' +
                            '<i class="text-primary bx bx-pencil fs-5"></i>' +
                        '</button>';
                        
                        actionUserRolesButton += '<button type="button" class="btn btn-icon btn-hapus-user-roles" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Hapus User Roles" ' +
                            'data-bs-target="#hapusUserRolesModal" ' +
                            'data-id="' + row.m_role_id + '"> ' +
                            '<i class="text-danger bx bx-trash fs-5"></i>' +
                        '</button>';

                        actionUserRolesButton += '</div>';
                    
                        return actionUserRolesButton;
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