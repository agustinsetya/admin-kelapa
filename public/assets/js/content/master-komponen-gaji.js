"use strict";

$((function () {
    if (roleScope === 'all') {
        applyFilter();
    } else if (roleScope === 'gudang') {
        getDetailKomponenGaji();
    } else {
        console.error('Role scope tidak valid');
    }

    $("body").on("click", "#btn-save", function (e) {
        e.preventDefault();
    
        var form = $("#detail-komponen-gaji-form")[0];
        var action = $("#detail-komponen-gaji-form").data("action");
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }
    
        var formData = $(form).serialize();
        var url, method;
    
        if (action === "edit") {
            var id = $("#detail-komponen-gaji-form").data("id");
        
            formData += "&id=" + id;
            url = "/manage/update-gelombang";
            method = "PUT";
        } else {
            url = "/manage/add-gelombang";
            method = "POST";
        }
    
        $.ajax({
            url: url,
            method: "PUT",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            beforeSend: () => {
                showBtnLoading("btn-save", "Menyimpan Data...", "btn-primary");
            },
            success: function (response) {
                resetButton(
                "btn-save",
                "Simpan",
                "btn btn-primary waves-effect waves-light"
                );
        
                if (response.success) {
                successAlert("Simpan Gelombang Berhasil!");
        
                $("#addGelombangModal").modal("hide");
        
                if ($.fn.dataTable.isDataTable(dg)) {
                    const dataTableInstance = dg.DataTable();
                    dataTableInstance.ajax.reload(null, false);
                } else {
                    console.error("Table is not initialized.");
                }
                } else {
                showToastError("Simpan Data Gagal!");
                return false;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const errors = jqXHR.responseJSON
                ? jqXHR.responseJSON
                : {
                    message: jqXHR.statusText || "Terjadi kesalahan saat menyimpan",
                    };
                const message =
                errors.code === 422
                    ? "Data tidak valid"
                    : errors.message ?? "Terjadi kesalahan saat menyimpan";
                showToastError(message ?? "Terjadi kesalahan saat menyimpan");
                resetButton(
                "btn-save",
                "Simpan",
                "btn btn-primary waves-effect waves-light"
                );
                console.log("error", errors);
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
        url:  ,
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