"use strict";

$((function () {
    applyFilter();

    $("#btn-tambah-pengolahan").on("click", function () {
        openModalPengolahan("add");
    });

    $(document).on('click', '.btn-edit-pengolahan', function () {
        getDetailSupplyPengolahan(this);
    });

    $("body").on("click", "#btn-save-pengolahan", function (e) {
        e.preventDefault();
    
        var form = $("#supply-pengolahan-form")[0];
        var action = $("#supply-pengolahan-form").data("action");
        var id = $("#supply-pengolahan-form").data("id") ?? '';
    
        const gudangValue = $("#peng_gudang_id").val();
        if (!gudangValue) {
            $("#peng_gudang_id").addClass("is-invalid");
        } else {
            $("#peng_gudang_id").removeClass("is-invalid").addClass("is-valid");
        }
        
        const pegawaiValue = $("#peng_pegawai_id").val();
        if (!pegawaiValue) {
            $("#peng_pegawai_id").addClass("is-invalid");
        } else {
            $("#peng_pegawai_id").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = '/supply-chain/pengolahan/add';
        if (action === 'edit') url = '/supply-chain/pengolahan/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-pengolahan", { text: "Menyimpan Data..." });

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
                alert('Simpan Data Pengolahan Berhasil!');
                $("#supplyPengolahanModal").modal("hide");

                applyFilter();
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
            resetButton("btn-save-pengolahan","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilter() {
    getDataSupplyPengolahan().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPengolahanTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPengolahan() {
    return $.ajax({
        url: base_url + '/supply-chain/pengolahan/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPengolahanTable(data) {
    const $dpg = $(".dt-pengolahanTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dpg)) {
        const dt = $dpg.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dpg.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'tg_pengolahan', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'nama_pegawai', defaultContent: "-" },
            { data: 'berat_daging', defaultContent: "-" },
            { data: 'berat_kopra', defaultContent: "-" },
            { data: null, defaultContent: "-" },
        ],
        columnDefs: [
            { targets: 0, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1 },
            { targets: 1, render: (d) => d ? formatTanggal(d) : "-" },
            {
                targets: 6,
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: (data, type, row) => `
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-icon btn-edit-pengolahan"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Detail Pengolahan" data-id="${row.mt_pengolahan_id}">
                    <i class="text-primary bx bx-pencil fs-5"></i>
                    </button>
                </div>`
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


function getDetailSupplyPengolahan(button) {
    var id = $(button).data("id");
  
    $.ajax({
        url: base_url + '/supply-chain/pengolahan/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPengolahan("edit", response.data[0]);
            } else {
                alert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPengolahan(mode, data = null) {
    $("#supply-pengolahan-form")[0].reset();
    $("#supply-pengolahan-form").removeClass("was-validated");
    $("#peng_gudang_id, #peng_pegawai_id").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#supply-pengolahan-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPengolahanModal .modal-title").text("Edit Data Pengolahan");
    
        $("#tg_pengolahan").val(data.tg_pengolahan.split("T")[0]);
        $("#peng_gudang_id").val(data.gudang_id).trigger("change");
        $("#peng_pegawai_id").val(data.kd_pegawai).trigger("change");
        $("#berat_daging").val(data.berat_daging);
        $("#berat_kopra").val(data.berat_kopra);
    
        $("#supply-pengolahan-form").data("action", "edit");
        $("#supply-pengolahan-form").data("id", data.mt_pengolahan_id);

        $("#supply-pengolahan-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#supplyPengolahanModal .modal-title").text("Tambah Data Pengolahan");
    
        $("#supply-pengolahan-form").data("action", "add");
        $("#supply-pengolahan-form").removeData("id");
    }

    $("#supplyPengolahanModal").modal("show");
}