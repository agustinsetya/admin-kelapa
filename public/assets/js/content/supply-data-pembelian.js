"use strict";

$((function () {
    applyFilter();

    $("body").on("click", "#btn-save-pembelian", function (e) {
        e.preventDefault();
    
        var form = $("#detail-pembelian-form")[0];
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }
    
        const formData = $("#detail-pembelian-form").serialize();
    
        $.ajax({
            url: base_url + '/supply-chain/pembelian/data',
            method: "POST",
            data: formData,
            beforeSend: () => {
                showBtnLoading("btn-save-pembelian", { text: "Menyimpan Data..." });
            },
            success: function (response) {
                resetButton(
                    "btn-save-pembelian",
                    "Simpan",
                    "btn btn-primary waves-effect waves-light"
                );
        
                if (response.success) {
                    alert("Simpan Komponen Gaji Berhasil!");
                } else {
                    alert(response?.message || "Simpan Data Gagal!");
                }
            },
            error: function (jqXHR) {
                const payload = jqXHR.responseJSON || {};
                const message =
                    payload.code === 422
                    ? "Data tidak valid"
                    : payload.message || "Terjadi kesalahan saat menyimpan";
                alert(message);

                resetButton(
                    "btn-save-pembelian",
                    "Simpan",
                    "btn btn-primary waves-effect waves-light"
                );
                alert("error", payload);
            },
        });
    });
}));

function applyFilter() {
    getDataKomponenGaji().done(function(response) {
        initializeKomponenGajiTable(response.data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed: " + textStatus + ", " + errorThrown);
    });
}

function getDataKomponenGaji() {
    return $.ajax({
        url: base_url + '/supply-chain/pembelian/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeKomponenGajiTable(data) {
    var dpt = $(".dt-pembelianTable");

    var isDataInvalid = !Array.isArray(data) || data.length === 0 || data.status === false;
    var safeData = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable(dpt)) {
        dpt.DataTable().clear();

        if (!isDataInvalid) dpt.DataTable().rows.add(safeData);

        dpt.DataTable().draw();
    } else {
        dpt.DataTable({
            data: isDataInvalid ? [] : safeData,
            columns: [
                { data: null, defaultContent: "" },
                { data: 'tg_pembelian', defaultContent: "-" },          
                { data: 'nama_gudang', defaultContent: "-" },
                { data: 'berat_kelapa', defaultContent: "-" },         
                { data: null, defaultContent: "-" },          
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
                    targets: 1,
                    render: function(data, type, row, meta) {
                        return formatTanggal(data);
                    }
                },
                {
                    targets: 4,
                    className: 'no-export',
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle dt-actions text-nowrap',
                    width: '72px',
                    render: function (data, type, row, meta) {
                        var actionPembelianButton = '<div class="d-flex align-items-center gap-1">';

                        actionPembelianButton += '<button type="button" class="btn btn-icon btn-edit-pembelian" ' +
                            'data-bs-toggle="tooltip" ' +
                            'data-bs-placement="top" ' +
                            'title="Detail Pembelian" ' +
                            'data-bs-target="#detailPembelianModal" ' +
                            'data-id="' + row.mt_pembelian_id + '"> ' +
                            '<i class="text-primary bx bx-pencil fs-5"></i>' +
                        '</button>';

                        actionPembelianButton += '</div>';
                    
                        return actionPembelianButton;
                    }
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
}

function getDetailKomponenGaji() {
    $.ajax({
        url: base_url + "/master/komponen-gaji/detail",
        method: "GET",
        success: function (response) {
            if (response && response['data'] && response['data'].length > 0) {
                var komponen = response['data'][0];

                $('#takaran-daging-kelapa').val(komponen.takaran_daging);
                $('#upah-takaran-daging-kelapa').val(formatRupiah(komponen.upah_takaran_daging));
                $('#takaran-kopra-kelapa').val(komponen.takaran_kopra);
                $('#upah-takaran-kopra-kelapa').val(formatRupiah(komponen.upah_takaran_kopra));
            } else {
            alert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Terjadi kesalahan: " + textStatus);
        },
    });
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