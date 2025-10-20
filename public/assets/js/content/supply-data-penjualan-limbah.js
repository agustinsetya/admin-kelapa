"use strict";

$((function () {
    applyFilterPenjualanLimbah();

    $("#btn-tambah-penjualan-limbah").on("click", function () {
        openModalPenjualanLimbah("add");
    });

    $(document).on('click', '.btn-edit-penjualan-limbah', function () {
        getDetailSupplyPenjualanLimbah(this);
    });

    $("body").on("click", "#btn-save-penjualan-limbah", function (e) {
        e.preventDefault();
    
        var form = $("#supply-penjualan-limbah-form")[0];
        var action = $("#supply-penjualan-limbah-form").data("action");
        var id = $("#supply-penjualan-limbah-form").data("id") ?? '';
    
        const gudangValue = $("#penj_gudang_id").val();
		if (!gudangValue) {
    		$("#penj_gudang_id").addClass("is-invalid");
		} else {
    		$("#penj_gudang_id").removeClass("is-invalid").addClass("is-valid");
		}
        
        const jenisLimbahValue = $("#jenis_limbah").val();
        if (!jenisLimbahValue) {
            $("#jenis_limbah").addClass("is-invalid");
        } else {
            $("#jenis_limbah").removeClass("is-invalid").addClass("is-valid");
        }

        const $pendapatan_limbah = $("#pendapatan_limbah");
        $pendapatan_limbah.val(unmaskRupiah($pendapatan_limbah.val()));
        
        const statusValue = $("#penj_status").val();
        if (!statusValue) {
            $("#penj_status").addClass("is-invalid");
        } else {
            $("#penj_status").removeClass("is-invalid").addClass("is-valid");
        }
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        let url = '/supply-chain/penjualan-limbah/add';
        if (action === 'edit') url = '/supply-chain/penjualan-limbah/update';

        let payload = $(form).serialize();
        if (action === 'edit' && id) payload += '&id=' + encodeURIComponent(id);

        showBtnLoading("btn-save-penjualan-limbah", { text: "Menyimpan Data..." });

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
                successAlert('Simpan Data Penjualan Limbah Berhasil!');
                $("#supplyPenjualanLimbahModal").modal("hide");

                applyFilterPenjualanLimbah();
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
                errorAlert(msg, 'Gagal Menyimpan');
            } catch (e) {
                errorAlert('Error!', 'Terjadi kesalahan. Cek konsol.');
                console.error('Save error:', jqXHR.status, jqXHR.responseText);
            }
        })
        .always(function () {
            resetButton("btn-save-penjualan-limbah","Simpan","btn btn-primary waves-effect waves-light");
        });
    });

    $(document).on("click", ".btn-hapus-penjualan-limbah", function (e) {
        e.preventDefault();
    
        let id = $(this).data('id');

        if (!id) {
            errorAlert('ID penjualan limbah tidak ditemukan.');
            return;
        }

        confirmDelete(() => deletePenjualanLimbahData(encodeURIComponent(id)));
    });
}));

function applyFilterPenjualanLimbah() {
    getDataSupplyPenjualanLimbah().done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeSupplyPenjualanLimbahTable(rows);
        reloadDropdownGudang("#penj_gudang_id", roleScope, penempatan);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function getDataSupplyPenjualanLimbah() {
    return $.ajax({
        url: base_url + '/supply-chain/penjualan-limbah/data',
        method: 'GET',
        dataType: 'json'
    });
}

function initializeSupplyPenjualanLimbahTable(data) {
    const $dpl = $(".dt-penjualanLimbahTable").first();
    const list = Array.isArray(data) ? data : [];

    if ($.fn.dataTable.isDataTable($dpl)) {
        const dt = $dpl.DataTable();
        dt.clear();
        if (list.length) dt.rows.add(list);
        dt.draw(false);
        return;
    }

    $dpl.DataTable({
        data: list,
        columns: [
            { data: null, defaultContent: "" },
            { data: 'tg_penjualan', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: null, defaultContent: "-" },
            { data: null, defaultContent: "-" },
        ],
        columnDefs: [
            { targets: 0, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1 },
            { 
                targets: 1, 
                render: function (data, type, row) {
                    var tgPenjualan = row.tg_penjualan ? formatTanggal(row.tg_penjualan) : "-";

                    var statusMap = {
                        BELUM_LUNAS: { title: "Belum Lunas", class: "badge-soft-warning" },
                        LUNAS: { title: "Lunas", class: "badge-soft-success" },
                    };

                    var meta = statusMap[row.status] || { title: "Unknown", class: "badge-soft-secondary" };

                    return `
                        <div class="d-flex flex-column align-items-start">
                            <span>${tgPenjualan}</span>
                            <span class="badge ${meta.class} font-size-12">${meta.title}</span>
                        </div>
                    `;
                }
            },
            {
                targets: 3,
                render: (data, type, row) => {
                    var jenisLimbahMap = {
                        AIR_KELAPA: { title: "Air Kelapa", class: "badge-soft-primary" },
                        BATOK_KELAPA: { title: "Batok Kelapa", class: "badge-soft-secondary" },
                    };

                    var meta = jenisLimbahMap[row.jenis_limbah] || { title: "Unknown", class: "badge-soft-secondary" };

                    const satuan_limbah = row.jenis_limbah == 'AIR_KELAPA' ? 'lt' : 'kg';
                    const berat_limbah = row.berat_limbah ? `${formatAngkaDecimal(row.berat_limbah)}` : "-";
                    const pendapatan_limbah  = row.pendapatan_limbah ? `${formatRupiah(row.pendapatan_limbah)}` : "-";
            
                    return `
                        <div>
                            <span class="badge ${meta.class} font-size-12">${meta.title}</span><br>
                            <small class="fst-italic text-muted">Berat: ${berat_limbah} ${satuan_limbah}</small><br>
                            <small class="fst-italic text-muted">Pendapatan: ${pendapatan_limbah}</small>
                        </div>
                    `;
                }
            },
            {
                targets: 4,
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'align-middle dt-actions text-nowrap',
                width: '72px',
                render: (data, type, row) => {
                    const isDisabled = row.status == 'LUNAS' ? 'disabled' : '';
                    const tooltipEdit = row.status == 'LUNAS' ? 'Sudah Lunas' : 'Detail Penjualan';
                    const tooltipDelete = row.is_stat_gaji == 1 ? 'Sudah diproses' : 'Hapus Penjualan Limbah';
            
                    return `
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-icon btn-edit-penjualan-limbah"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltipEdit}" data-id="${row.mt_penjualan_limbah_id}" ${isDisabled}>
                                <i class="text-primary bx bx-pencil fs-5"></i>
                            </button>
                            <button type="button" class="btn btn-icon btn-hapus-penjualan-limbah"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="${tooltipDelete}" data-id="${row.mt_penjualan_limbah_id}" ${isDisabled}>
                                <i class="text-danger bx bx-trash fs-5"></i>
                            </button>
                        </div>
                    `;
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


function getDetailSupplyPenjualanLimbah(button) {
    var id = $(button).data("id");

    $.ajax({
        url: base_url + '/supply-chain/penjualan-limbah/detail',
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            if (response && response.data) {
                openModalPenjualanLimbah("edit", response.data[0]);
            } else {
                errorAlert("Data tidak ditemukan!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            errorAlert("Terjadi kesalahan: " + textStatus);
        },
    });
}

function openModalPenjualanLimbah(mode, data = null) {
    $("#supply-penjualan-limbah-form")[0].reset();
    $("#supply-penjualan-limbah-form").removeClass("was-validated");
    $("#penj_gudang_id, #penj_status").val(null).trigger("change").removeClass("is-invalid is-valid");

    $("#supply-penjualan-limbah-form input[name='_method']").remove();

    if (mode === "edit" && data) {
        $("#supplyPenjualanLimbahModal .modal-title").text("Edit Data Penjualan Limbah");
    
        $("#tg_penjualan")
            .val(data.tg_penjualan.split("T")[0])
            .prop("readonly", true);
        $("#penj_gudang_id")
            .val(data.gudang_id)
            .trigger("change");
        $("#jenis_limbah")
            .val(data.jenis_limbah)
            .trigger("change");
        $("#berat_limbah").val(data.berat_limbah);
        $("#pendapatan_limbah").val(formatRupiah(data.pendapatan_limbah));
        $("#penj_status").val(data.status).trigger("change");
    
        $("#supply-penjualan-limbah-form").data("action", "edit");
        $("#supply-penjualan-limbah-form").data("id", data.mt_penjualan_limbah_id);

        $("#supply-penjualan-limbah-form").append(
            '<input type="hidden" name="_method" value="PATCH">'
        );
    } else {
        $("#supplyPenjualanLimbahModal .modal-title").text("Tambah Data Penjualan Limbah");

        $("#tg_penjualan").prop("readonly", false);
        $("#penj_gudang_id, #penj_status")
            .val("")
            .trigger("change");

        reloadDropdownGudang("#penj_gudang_id", roleScope, penempatan);
    
        $("#supply-penjualan-limbah-form").data("action", "add");
        $("#supply-penjualan-limbah-form").removeData("id");
    }

    $("#supplyPenjualanLimbahModal").modal("show");
}

function deletePenjualanLimbahData(penjualanLimbahId) {
    $.ajax({
        url: base_url + `supply-chain/penjualan-limbah/delete/${penjualanLimbahId}`,
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    })
    .done(function (response) {
        if (response?.csrf?.name && response?.csrf?.hash) {
            $('meta[name="csrf-token-name"]').attr('content', response.csrf.name);
            $('meta[name="csrf-token"]').attr('content', response.csrf.hash);
        }

        if (response?.success) {
            successAlert('Hapus Data Penjualan Limbah Berhasil!');

            applyFilterPenjualanLimbah();
        } else {
            const message = response?.errors ?? response?.message ?? 'Hapus Data Gagal!';
            errorAlert(message, 'Hapus Data Gagal!');
        }
    })
    .fail(function (jqXHR) {
        try {
            const res = jqXHR.responseJSON;
    
            if (res?.csrf?.name && res?.csrf?.hash) {
                $('meta[name="csrf-token-name"]').attr('content', res.csrf.name);
                $('meta[name="csrf-token"]').attr('content', res.csrf.hash);
            }
    
            const msg = res?.errors ?? res?.message ?? res?.error ?? 'Terjadi kesalahan saat Menghapus';
            errorAlert(msg, 'Gagal Menghapus');
        } catch (e) {
            errorAlert('Error!', 'Terjadi kesalahan. Cek konsol.');
            console.error('Save error:', jqXHR.status, jqXHR.responseText);
        }
    })
}