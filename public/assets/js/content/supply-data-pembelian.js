"use strict";

$((function () {
    applyFilter();

    $("#btn-tambah-pembelian").on("click", function () {
        openModal("add");
    });

    $(document).on('click', '.btn-edit-pembelian', function () {
        getDetailSupplyPembelian(this);
    });

    $("body").on("click", "#btn-save-pembelian", function (e) {
        e.preventDefault();
    
        var form = $("#supply-pembelian-form")[0];
        var action = $("#supply-pembelian-form").data("action");
        var id = $("#supply-pembelian-form").data("id") ?? '';
    
        const gudangValue = $("#gudang_id").val();
        if (!gudangValue) {
            $("#gudang_id").addClass("is-invalid");
        } else {
            $("#gudang_id").removeClass("is-invalid").addClass("is-valid");
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
        })
        .done(function (response) {
            if (response?.csrf) {
                const name = response.csrf.name;
                const hash = response.csrf.hash;
                $('input[name="'+ name +'"]').val(hash);
            }

            if (response?.success) {
                alert('Simpan Data Pembelian Berhasil!');
                $("#supplyPembelianModal").modal("hide");

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
            resetButton("btn-save-pembelian","Simpan","btn btn-primary waves-effect waves-light");
        });
    });
}));

function applyFilter() {
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
    const $tbl = $(".dt-pembelianTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($tbl)) {
        const dt = $tbl.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $tbl.DataTable({
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
                openModal("edit", response.data[0]);
            } else {
                alert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModal(mode, data = null) {
    $("#supply-pembelian-form")[0].reset();
    $("#supply-pembelian-form").removeClass("was-validated");
    $("#gudang_id").val(null).trigger("change");
    $("#gudang_id").removeClass("is-invalid is-valid");

    $("#supply-pembelian-form input[name='_method']").remove();
  
    if (mode === "edit" && data) {
        $("#supplyPembelianModal .modal-title").text("Edit Data Pembelian");
    
        $("#tg_pembelian").val(data.tg_pembelian.split("T")[0]);
        $("#gudang_id").val(data.gudang_id).trigger("change");
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

function formatTanggal(tanggal) {
    const date = new Date(tanggal);
    const day = date.getDate();
    const month = date.toLocaleString("id-ID", { month: "long" });
    const year = date.getFullYear();
    return `${day} ${month} ${year}`;
}

function formatRupiah(angka) {
    if (angka == null || isNaN(angka)) return "";
    return "Rp. " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

function unmaskRupiah(str) {
    return (str || '').toString().replace(/[^\d]/g, '');
}

function showBtnLoading(btnId, options = {}) {
    const $btn = $("#" + btnId);

    // Simpan HTML & class asli kalau belum disimpan
    if (!$btn.data("original-html")) {
        $btn.data("original-html", $btn.html());
        $btn.data("original-class", $btn.attr("class"));
    }

    // Opsi default
    const defaults = {
        text: "Loading",
        icon: "bx bx-hourglass bx-spin font-size-16 align-middle me-2",
        class: "btn btn-light waves-effect",
        disabled: true,
    };

    const settings = { ...defaults, ...options };

    // Update tombol jadi loading
    $btn
        .removeClass()
        .addClass(settings.class)
        .prop("disabled", settings.disabled)
        .html(`<i class="${settings.icon}"></i> ${settings.text}`
    );
}

function resetButton(btnId) {
    const $btn = $("#" + btnId);

    const originalHtml = $btn.data("original-html");
    const originalClass = $btn.data("original-class");

    if (originalHtml !== undefined) {
        $btn.html(originalHtml);
    }
    if (originalClass !== undefined) {
        $btn.removeClass().addClass(originalClass);
    }

    $btn.prop("disabled", false);
}