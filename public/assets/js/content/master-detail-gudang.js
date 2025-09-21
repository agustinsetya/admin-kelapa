"use strict";

$(() => {
    getDetailMasterGudang();

    $("body").on("click", "#btn-save-detail-gudang", function (e) {
        e.preventDefault();

        var form = $("#detail-master-gudang-form")[0];
        var action = $("#detail-master-gudang-form").data("action");
        var id = $("#detail-master-gudang-form").data("id") ?? '';

        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        [
            "#upah_takaran_daging",
            "#upah_takaran_kopra",
            "#upah_takaran_kulit",
            "#gaji_driver_distribusi",
            "#gaji_driver_ngepok_truk",
            "#gaji_driver_ngepok_pickup"
        ].forEach(selector => {
            const $el = $(selector);
            $el.val(unmaskRupiah($el.val()));
        });

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

                getDetailMasterGudang();

                form.classList.remove("was-validated");

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
});

function getDetailMasterGudang() {
    var id = penempatan;

    $.ajax({
        url: base_url + '/master/gudang/detail',
        method: "GET",
        data: { id: id },
        success: function (response) {
            $("#detail-master-gudang-form")[0].reset();
            $("#detail-master-gudang-form").removeClass("was-validated");
            
            $("#detail-master-gudang-form input[name='_method']").remove();

            if (response && response.data) {
                var gudangData = response.data[0];

                $("#nama_gudang").val(gudangData.nama);
                $("#takaran_daging_kelapa").val(gudangData.takaran_daging ?? 0);
                $("#upah_takaran_daging").val(formatRupiah(gudangData.upah_takaran_daging));
                $("#takaran_kopra_kelapa").val(gudangData.takaran_kopra ?? 0);
                $("#upah_takaran_kopra").val(formatRupiah(gudangData.upah_takaran_kopra));
                $("#takaran_kulit_kelapa").val(gudangData.takaran_kulit ?? 0);
                $("#upah_takaran_kulit").val(formatRupiah(gudangData.upah_takaran_kulit));
                $("#gaji_driver_distribusi").val(formatRupiah(gudangData.gaji_driver_distribusi));
                $("#gaji_driver_ngepok_truk").val(formatRupiah(gudangData.gaji_driver_ngepok_truk));
                $("#gaji_driver_ngepok_pickup").val(formatRupiah(gudangData.gaji_driver_ngepok_pickup));

                $("#detail-master-gudang-form").data("id", gudangData.m_gudang_id );
                $("#detail-master-gudang-form").data("action", "edit");

                $("#detail-master-gudang-form").append(
                    '<input type="hidden" name="_method" value="PATCH">'
                );
            } else {
                $("#detail-master-gudang-form").data("action", "add");
                $("#detail-master-gudang-form").removeData("id");
            }
        },
        error: function (jqXHR, textStatus) {
            errorAlert('', "Terjadi kesalahan: " + textStatus);
        },
    });
}