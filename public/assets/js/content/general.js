"use strict";

function formatTanggal(tanggal) {
    const date = new Date(tanggal);
    const day = date.getDate();
    const month = date.toLocaleString("id-ID", { month: "long" });
    const year = date.getFullYear();
    return `${day} ${month} ${year}`;
}

function formatAngkaDecimal(value) {
    if (value == null || value === "") return "-";
    const num = parseFloat(value);
    if (isNaN(num)) return "-";
    return num.toLocaleString("en-US"); 
}

function formatRupiah(angka) {
    if (angka == null || isNaN(angka)) return "";
    let n = Number(angka) || 0;
    // dibulatkan ke integer
    n = Math.round(n);
    return "Rp. " + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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

    const defaults = {
        text: "Loading",
        icon: "bx bx-hourglass bx-spin font-size-16 align-middle me-2",
        class: "btn btn-light waves-effect",
        disabled: true,
    };

    const settings = { ...defaults, ...options };

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

function successAlert(alertText, url = null) {
    Swal.fire({
        title: "Good job!",
        text: alertText,
        icon: "success",
        customClass: {
            confirmButton: "btn btn-primary waves-effect waves-light",
        },
        buttonsStyling: false,
    }).then(function (result) {
        if (url !== null) {
            location.href = `${siteUrl}/` + url;
        }
    });
}

function successAlert(alertText, url = null) {
    Swal.fire({
        title: "Good job!",
        text: alertText,
        icon: "success",
        customClass: {
            confirmButton: "btn btn-primary waves-effect waves-light",
        },
        buttonsStyling: false,
    }).then(function (result) {
        if (url !== null) {
            location.href = `${siteUrl}/` + url;
        }
    });
}

function errorAlert(alertContent = null, title = 'Gagal!') {
    let html = '';
    
    if (typeof alertContent === 'object') {
        html = Object.values(alertContent).join('<br>');
    } else {
        html = alertContent;
    }

    Swal.fire({
        title: title,
        html: html,
        icon: "error",
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn btn-danger",
        },
    });
}

function confirmDelete(url) {
    Swal.fire({
        title: 'Yakin mau hapus data ini?',
        text: "Tindakan ini tidak bisa dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            location.href = `${siteUrl}/` + url;
        }
    });
}

function confirmProcess(callback, options = {}) {
    Swal.fire({
        title: options.title || 'Yakin mau lanjut?',
        text: options.text || "Tindakan ini tidak bisa dibatalkan!",
        icon: options.icon || 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: options.confirmButtonText || 'Ya, lanjutkan!',
        cancelButtonText: options.cancelButtonText || 'Batal'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

// Date Range Picker
const hasDRP = () => !!($ && $.fn && $.fn.daterangepicker);
const byId = (id) => document.getElementById(id);
function initRangePicker(dateRangeId) {
    const el = byId(dateRangeId);
    if (!el || !hasDRP()) return;

    $(el).daterangepicker({
        autoUpdateInput: false,     
        autoApply: true,
        locale: {
            format: 'DD MMM YYYY',
            separator: ' – ',
            applyLabel: 'Terapkan',
            cancelLabel: 'Bersihkan'
        },
        opens: 'right',
        showDropdowns: true,
        alwaysShowCalendars: true
    });

    $(el).on('apply.daterangepicker', function (ev, picker) {
        this.value = picker.startDate.format('DD MMM YYYY') + ' – ' + picker.endDate.format('DD MMM YYYY');
    });

    $(el).on('cancel.daterangepicker', function () {
        this.value = '';
    });
}

function getIsoRange(id) {
    const $el = $('#' + id);
    const val = ($el.val() || '').trim();
    if (!val) return { start:'', end:'' };
    
    const drp = $el.data('daterangepicker');
    if (drp && drp.startDate && drp.endDate) {
        return {
            start: drp.startDate.format('YYYY-MM-DD'),
            end:   drp.endDate.format('YYYY-MM-DD')
        };
    }
}

function reloadDropdownGudang(targetId) {
    $.ajax({
        url: base_url + 'master/gudang/data',
        method: "GET",
        dataType: "json",
        success: function (response) {
            const $target = $(targetId);
            $target.empty();

            $target.append('<option value="" disabled selected>Pilih Gudang</option>');

            if (Array.isArray(response.data)) {
                response.data.forEach(function(gudang) {
                    $target.append(
                        $('<option>', {
                            value: gudang.m_gudang_id,
                            text: gudang.nama
                        })
                    );
                });
            }

            $target.trigger("change");
        },
        error: function () {
            console.error("Gagal memuat data gudang:", xhr.responseText);
        }
    });
}

function reloadDropdownPegawaiByGudang(targetId, gudangId) {
    const $target = $(targetId);

    $target
        .empty()
        .append('<option value="" disabled selected>Pilih Pegawai</option>')
        .prop("disabled", true);

    if (!gudangId) return;

    $.ajax({
        url: base_url + "master/pegawai/data",
        method: "GET",
        data: {
            gudang: gudangId,
        },
        dataType: "json",
        success: function (response) {
            if (Array.isArray(response.data)) {
                response.data.forEach(function(pegawai) {
                    $target.append(
                        $('<option>', {
                            value: pegawai.kd_pegawai,
                            text: pegawai.nama_pegawai
                        })
                    );
                });

                $target.prop("disabled", false);
            }

            $target.trigger("change");
        },
    });
}

function reloadDropdownContainerPembelianByGudang(targetId, gudangId) {
    const $target = $(targetId);

    $target
        .empty()
        .append('<option value="" disabled selected>Pilih Kode Container</option>')
        .prop("disabled", true);

    if (!gudangId) return;

    $.ajax({
        url: base_url + "supply-chain/pembelian/data",
        method: "GET",
        data: {
            gudang: gudangId,
        },
        dataType: "json",
        success: function (response) {
            if (Array.isArray(response.data)) {
                response.data.forEach(function(pembelian) {
                    $target.append(
                        $('<option>', {
                            value: pembelian.kode_container,
                            text: pembelian.kode_container
                        })
                    );
                });

                $target.prop("disabled", false);
            }

            $target.trigger("change");
        },
    });
}

function reloadDropdownResiPengirimanByGudang(targetId, gudangId) {
    const $target = $(targetId);

    $target
        .empty()
        .append('<option value="" disabled selected>Pilih Nomor Resi</option>')
        .prop("disabled", true);

    if (!gudangId) return;

    $.ajax({
        url: base_url + 'supply-chain/pengiriman/data',
        method: "GET",
        data: {
            gudang: gudangId,
        },
        dataType: "json",
        success: function (response) {
            if (Array.isArray(response.data)) {
                response.data.forEach(function(pengiriman) {
                    $target.append(
                        $('<option>', {
                            value: pengiriman.mt_log_pengiriman_id,
                            text: pengiriman.nomor_resi
                        })
                    );
                });

                $target.prop("disabled", false);
            }

            $target.trigger("change");
        },
        error: function () {
            console.error("Gagal memuat data pengiriman:", xhr.responseText);
        }
    });
}