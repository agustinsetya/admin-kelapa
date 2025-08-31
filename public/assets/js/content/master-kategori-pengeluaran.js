"use strict";

$((function () {
    applyFilterKategoriPengeluaran();

    $("#btn-tambah-kategori-pengeluaran").on("click", function () {
        openModalKategoriPengeluaran("add");
    });

    $(document).on('click', '.btn-edit-kategori-pengeluaran', function () {
        getDetailMasterKategoriPengeluaran(this);
    });

    $("body").on("click", "#btn-save-kategori-pengeluaran", function (e) {
        e.preventDefault();
    
        var form = $("#master-kategori-pengeluaran-form")[0];
        var action = $("#master-kategori-pengeluaran-form").data("action");
        var id = $("#master-kategori-pengeluaran-form").data("id") ?? '';
    
        const pegawaiValue = $("#us_pegawai_id").val();
        if (!pegawaiValue) {
            $("#us_pegawai_id").addClass("is-invalid");
        } else {
            $("#us_pegawai_id").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = 'master/kategori-pengeluaran/add';
        if (action === 'edit') url = 'master/kategori-pengeluaran/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-kategori-pengeluaran", { text: "Menyimpan Data..." });

        $.ajax({
            url: base_url + url,
            method: 'POST',
            data: payload,
            dataType: 'json',
        })
        .done(function (response) {
            if (response?.csrf) {
                const name = response.csrf.name;
                const hash = response.csrf.hash;
                $('input[name="'+ name +'"]').val(hash);
            }

            if (response?.success) {
                alert('Simpan Data Kategori Pengeluaran Berhasil!');
                $("#masterKategoriPengeluaranModal").modal("hide");

                applyFilterKategoriPengeluaran();
            } else {
                alert(response?.message || 'Simpan Data Gagal!');
            }
        })
        .fail(function (jqXHR) {
            try {
                const res = jqXHR.responseJSON;
                if (res?.csrf) {
                    $('input[name="'+ res.csrf.name +'"]').val(res.csrf.hash);
                }
                const msg = res?.message || res?.error || 'Terjadi kesalahan saat menyimpan';
                alert(msg);
            } catch (e) {
                alert('Terjadi kesalahan. Cek konsol.');
                console.error('Save error:', jqXHR.status, jqXHR.responseText);
            }
        })
        .always(function () {
            resetButton("btn-save-kategori-pengeluaran","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterKategoriPengeluaran() {
    getDataMasterKategoriPengeluaran().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeMasterKategoriPengeluaranTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataMasterKategoriPengeluaran() {
    return $.ajax({
        url: base_url + '/master/kategori-pengeluaran/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeMasterKategoriPengeluaranTable(data) {
    const $dmu = $(".dt-kategoriPengeluaranTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dmu)) {
        const dt = $dmu.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }
    
    $dmu.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'nama', defaultContent: "-" },
            { data: 'keterangan', defaultContent: "-" },
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
                targets: 3,
                className: 'no-export',
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: function (data, type, row, meta) {
                    var actionKategoriPengeluaranButton = '<div class="d-flex align-items-center gap-1">';

                    actionKategoriPengeluaranButton += '<button type="button" class="btn btn-icon btn-edit-kategori-pengeluaran" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Detail Kategori Pengeluaran" ' +
                        'data-id="' + row.m_ktg_pengeluaran_id + '"> ' +
                        '<i class="text-primary bx bx-pencil fs-5"></i>' +
                    '</button>';
                    
                    actionKategoriPengeluaranButton += '<button type="button" class="btn btn-icon btn-hapus-kategori-pengeluaran" ' +
                        'data-bs-toggle="tooltip" ' +
                        'data-bs-placement="top" ' +
                        'title="Hapus Kategori Pengeluaran" ' +
                        'data-bs-target="#kategoriPengeluaranModal" ' +
                        'data-id="' + row.m_ktg_pengeluaran_id + '"> ' +
                        '<i class="text-danger bx bx-trash fs-5"></i>' +
                    '</button>';

                    actionKategoriPengeluaranButton += '</div>';
                
                    return actionKategoriPengeluaranButton;
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

function getDetailMasterKategoriPengeluaran(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/master/kategori-pengeluaran/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalKategori-pengeluaran("edit", response.data[0]);
            } else {
                alert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalKategoriPengeluaran(mode, data = null) {
    $("#master-kategori-pengeluaran-form")[0].reset();
    $("#master-kategori-pengeluaran-form").removeClass("was-validated");
    $("#us_pegawai_id").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#master-kategori-pengeluaran-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#masterKategoriPengeluaranModal .modal-title").text("Edit Data Kategori Pengeluaran");

        $("#us_pegawai_id").val(data.kd_pegawai).trigger("change");
        $("#email").val(data.email);
    
        $("#master-kategori-pengeluaran-form").data("action", "edit");
        $("#master-kategori-pengeluaran-form").data("id", data.m_ktg_pengeluaran_id);

        $("#master-kategori-pengeluaran-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#masterKategoriPengeluaranModal .modal-title").text("Tambah Data Kategori Pengeluaran");
    
        $("#master-kategori-pengeluaran-form").data("action", "add");
        $("#master-kategori-pengeluaran-form").removeData("id");
    }

    $("#masterKategoriPengeluaranModal").modal("show");
}