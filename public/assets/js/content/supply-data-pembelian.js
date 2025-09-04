"use strict";

$((function () {
    applyFilterPembelian();

    $("#btn-tambah-pembelian").on("click", function () {
        openModalPembelian("add");
    });

    $(document).on('click', '.btn-edit-pembelian', function () {
        getDetailSupplyPembelian(this);
    });

    $("body").on("click", "#btn-save-pembelian", function (e) {
        e.preventDefault();
    
        var form = $("#supply-pembelian-form")[0];
        var action = $("#supply-pembelian-form").data("action");
        var id = $("#supply-pembelian-form").data("id") ?? '';
    
        const gudangValue = $("#pem_gudang_id").val();
        if (!gudangValue) {
            $("#pem_gudang_id").addClass("is-invalid");
        } else {
            $("#pem_gudang_id").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = '/supply-chain/pembelian/add';
        if (action === 'edit') url = '/supply-chain/pembelian/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-pembelian", { text: "Menyimpan Data..." });

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
                successAlert('Simpan Data Pembelian Berhasil!');
                $("#supplyPembelianModal").modal("hide");

                applyFilterPembelian();
            } else {
                const message = response?.errors ?? response?.message ?? 'Simpan Data Gagal!';
                errorAlert(message, 'Simpan Data Gagal!');
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
            resetButton("btn-save-pembelian","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilterPembelian() {
    getDataSupplyPembelian().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPembelianTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPembelian() {
    return $.ajax({
        url: base_url + '/supply-chain/pembelian/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPembelianTable(data) {
    const $dpm = $(".dt-pembelianTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dpm)) {
        const dt = $dpm.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dpm.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'tg_pembelian', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: 'berat_kelapa', defaultContent: "-" },
            { data: null, defaultContent: "-" },
        ],
        columnDefs: [
            { targets: 0, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1 },
            { targets: 1, render: (d) => d ? formatTanggal(d) : "-" },
            {
                targets: 4,
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: (data, type, row) => `
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-icon btn-edit-pembelian"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Detail Pembelian" data-id="${row.mt_pembelian_id}">
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


function getDetailSupplyPembelian(button) {
    var id = $(button).data("id");
  
    $.ajax({
        url: base_url + '/supply-chain/pembelian/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPembelian("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPembelian(mode, data = null) {
    $("#supply-pembelian-form")[0].reset();
    $("#supply-pembelian-form").removeClass("was-validated");
    $("#pem_gudang_id").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#supply-pembelian-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPembelianModal .modal-title").text("Edit Data Pembelian");
    
        $("#tg_pembelian").val(data.tg_pembelian.split("T")[0]);
        $("#pem_gudang_id").val(data.gudang_id).trigger("change");
        $("#berat_kelapa").val(data.berat_kelapa);
    
        $("#supply-pembelian-form").data("action", "edit");
        $("#supply-pembelian-form").data("id", data.mt_pembelian_id);

        $("#supply-pembelian-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#supplyPembelianModal .modal-title").text("Tambah Data Pembelian");
    
        $("#supply-pembelian-form").data("action", "add");
        $("#supply-pembelian-form").removeData("id");
    }

    $("#supplyPembelianModal").modal("show");
}