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