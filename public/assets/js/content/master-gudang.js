"use strict";

$((function () {
    applyFilterGudang();

    $("#btn-tambah-gudang").on("click", function () {
        openModalGudang("add");
    });

    $(document).on('click', '.btn-edit-gudang', function () {
        getDetailMasterGudang(this);
    });

    $("body").on("click", "#btn-save-gudang", function (e) {
        e.preventDefault();
    
        var form = $("#master-gudang-form")[0];
        var action = $("#master-gudang-form").data("action");
        var id = $("#master-gudang-form").data("id") ?? '';
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $upah_takaran_daging = $("#upah_takaran_daging");
        $upah_takaran_daging.val(unmaskRupiah($upah_takaran_daging.val()));
        
        const $upah_takaran_kopra = $("#upah_takaran_kopra");
        $upah_takaran_kopra.val(unmaskRupiah($upah_takaran_kopra.val()));
        
        const $gaji_driver = $("#gaji_driver");
        $gaji_driver.val(unmaskRupiah($gaji_driver.val()));

        let url = 'master/gudang/add';
        if (action === 'edit') url = 'master/gudang/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-gudang", { text: "Menyimpan Data..." });

        $.ajax({
            url: base_url + url,
            method: 'POST',
            data: payload,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .done(function (response) {
            if (response?.csrf?.name && response?.csrf?.hash) {
                $('meta[name="csrf-token-name"]').attr('content', response.csrf.name);
                $('meta[name="csrf-token"]').attr('content', response.csrf.hash);
            }

            if (response?.success) {
                successAlert("Simpan Data Gudang Berhasil!");
                $("#masterGudangModal").modal("hide");

                applyFilterGudang();
            } else {
                const message = response?.errors ?? response?.message ?? 'Simpan Data Gagal!';
                errorAlert('Simpan Data Gagal!', message);
            }
        })
        .fail(function (jqXHR) {
            try {
                const res = jqXHR.responseJSON;
        
                if (res?.csrf?.name && res?.csrf?.hash) {
                    $('meta[name="csrf-token-name"]').attr('content', res.csrf.name);
                    $('meta[name="csrf-token"]').attr('content', res.csrf.hash);
                }
        
                const msg = res?.errors ?? res?.message ?? res?.error ?? 'Terjadi kesalahan saat menyimpan';
                errorAlert('Gagal Menyimpan', msg);
            } catch (e) {
                errorAlert('Error!', 'Terjadi kesalahan. Cek konsol.');
                console.error('Save error:', jqXHR.status, jqXHR.responseText);
            }
        })
        .always(function () {
            resetButton("btn-save-gudang","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterGudang() {
    getDataGudang().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeMasterGudangTable(rows);
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

function initializeMasterGudangTable(data) {
    const $dmg = $(".dt-gudangTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dmg)) {
        const dt = $dmg.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }
    
    $dmg.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'nama', defaultContent: "-" },
            { data: 'takaran_daging', defaultContent: "-" },          
            { data: 'upah_takaran_daging', defaultContent: "-" },          
            { data: 'takaran_kopra', defaultContent: "-" },          
            { data: 'upah_takaran_kopra', defaultContent: "-" },     
            { data: 'gaji_driver', defaultContent: "-" },     
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
                targets: [3,5,6],
                render: function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            },
            {
                targets: 7,
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
                        'data-bs-target="#masterGudangModal" ' +
                        'data-id="' + row.m_gudang_id + '"> ' +
                            '<i class="text-primary bx bx-pencil fs-5"></i>' +
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

function getDetailMasterGudang(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/master/gudang/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalGudang("edit", response.data[0]);
            } else {
                errorAlert('', "Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert('', "Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalGudang(mode, data = null) {
    $("#master-gudang-form")[0].reset();
    $("#master-gudang-form").removeClass("was-validated");
    
    $("#master-gudang-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#masterGudangModal .modal-title").text("Edit Data Gudang");

        $("#nama_gudang").val(data.nama);
        $("#takaran_daging_kelapa").val(data.takaran_daging_kelapa ?? 0);
        $("#upah_takaran_daging").val(formatRupiah(data.upah_takaran_daging));
        $("#takaran_kopra_kelapa").val(data.takaran_kopra_kelapa ?? 0);
        $("#upah_takaran_kopra").val(formatRupiah(data.upah_takaran_kopra));
        $("#gaji_driver").val(formatRupiah(data.gaji_driver));
        
        $("#master-gudang-form").data("action", "edit");
        $("#master-gudang-form").data("id", data.m_gudang_id );

        $("#master-gudang-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#masterGudangModal .modal-title").text("Tambah Data Gudang");
    
        $("#master-gudang-form").data("action", "add");
        $("#master-gudang-form").removeData("id");
    }

    $("#masterGudangModal").modal("show");
}