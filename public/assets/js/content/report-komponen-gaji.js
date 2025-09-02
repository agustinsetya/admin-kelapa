"use strict";

$((function () {
    if (roleScope === 'all') {
        applyFilter();
    } else if (roleScope === 'gudang') {
        getDetailKomponenGaji();
    } else {
        console.error('Role scope tidak valid');
    }

    $("body").on("click", "#btn-save-komponen-gaji", function (e) {
        e.preventDefault();
    
        var form = $("#detail-komponen-gaji-form")[0];
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const $upahDaging = $("#upah-takaran-daging-kelapa");
        const $upahKopra  = $("#upah-takaran-kopra-kelapa");

        $upahDaging.val(unmaskRupiah($upahDaging.val()));
        $upahKopra.val(unmaskRupiah($upahKopra.val()));
    
        const formData = $("#detail-komponen-gaji-form").serialize();
    
        $.ajax({
            url: base_url + '/master/komponen-gaji',
            method: "POST",
            data: formData,
            beforeSend: () => {
                showBtnLoading("btn-save-komponen-gaji", { text: "Menyimpan Data..." });
            },
            success: function (response) {
                resetButton(
                    "btn-save-komponen-gaji",
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
                    "btn-save-komponen-gaji",
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
        url:  base_url + '/master/komponen-gaji/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeKomponenGajiTable(data) {
    var dkt = $(".dt-komponenGajiTable");

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
                { data: 'nama_gudang', defaultContent: "-" },
                { data: 'takaran_daging', defaultContent: "-" },          
                { data: 'upah_takaran_daging', defaultContent: "-" },          
                { data: 'takaran_kopra', defaultContent: "-" },          
                { data: 'upah_takaran_kopra', defaultContent: "-" },          
                { data: 'updated_at', defaultContent: "-" },          
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
                    targets: [3,5],
                    render: function(data, type, row, meta) {
                        return formatRupiah(data);
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row, meta) {
                        return formatTanggal(data);
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