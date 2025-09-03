"use strict";

function formatTanggal(tanggal) {
    const date = new Date(tanggal);
    const day = date.getDate();
    const month = date.toLocaleString("id-ID", { month: "long" });
    const year = date.getFullYear();
    return `${day} ${month} ${year}`;
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