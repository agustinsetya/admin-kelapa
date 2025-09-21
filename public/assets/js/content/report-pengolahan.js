(function () {
  'use strict';
  
  const STACKED = false;       
  const PX_PER_CAT = 80;      
  const MIN_WIDTH  = 720;     
  const MANY_CATS_THRESHOLD = 15;

  let chart = null;
  
  const $ = window.jQuery;
  const hasDRP = () => !!($ && $.fn && $.fn.daterangepicker);
  const byId = (id) => document.getElementById(id);

  function ensureDeps() {
    if (!window.ApexCharts) {
      console.error('[report] ApexCharts tidak ditemukan.');
      return false;
    }
    if (typeof base_url === 'undefined') {
      console.error('[report] base_url tidak tersedia.');
      return false;
    }
    return true;
  }

  initRangePicker('tanggal_pengolahan');
  getIsoRange('tanggal_pengolahan');

  async function fetchChartData(gudang = null, start = '', end = '') {
    const params = new URLSearchParams();
    if (gudang) params.append('gudang_id', gudang);
    if (start)  params.append('start_date', start);
    if (end)    params.append('end_date', end);

    const url = `${base_url}/report/pengolahan/data${params.toString() ? '?' + params.toString() : ''}`;
    const resp = await fetch(url, { headers: { Accept: 'application/json' } });
    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
    const json = await resp.json();
    if (!json.ok) throw new Error('Payload not ok');
    return json;
  }

  function setChartWidth(categories) {
    const el = byId('column_chart');
    const wrap = byId('chart-scroll');
    const count = Array.isArray(categories) ? categories.length : 0;
    const width = Math.max(MIN_WIDTH, count * PX_PER_CAT);
    if (el) el.style.width = width + 'px';
    if (wrap) wrap.scrollLeft = 0; 
  }

  function renderChart({ categories, series }) {
    const el = byId('column_chart');
    if (!el) { console.error('[report] #column_chart tidak ditemukan.'); return; }

    if (chart) { chart.destroy(); chart = null; }

    setChartWidth(categories);
    const manyCats = (categories?.length || 0) > MANY_CATS_THRESHOLD;

    const options = {
      chart: { type: 'bar', height: 420, stacked: STACKED, toolbar: { show: true } },
      plotOptions: { bar: { horizontal: false, columnWidth: '45%' } },
      series: series,
      xaxis: {
        categories: categories,
        title: { text: 'Gudang' },
        labels: { rotate: (categories?.length || 0) > 12 ? -45 : -15, trim: true }
      },
      yaxis: { title: { text: 'Jumlah Hasil (kg)' }, forceNiceScale: true },
      dataLabels: { enabled: !manyCats },
      legend: { position: 'top' },
      noData: { text: 'Tidak ada data' }
    };

    chart = new ApexCharts(el, options);
    chart.render();
  }

  async function loadChart(gudang = null, start = '', end = '') {
    try {
        const data = await fetchChartData(gudang, start, end);
        renderChart({
            categories: data.categories || [],
            series: data.series || [
                { name: 'Daging (kg)', data: [] },
                { name: 'Kopra (kg)', data: [] },
                { name: 'Kulit (kg)', data: [] },
            ]
        });
    } catch (err) {
        console.error('[report] Gagal memuat data chart:', err);
        renderChart({ categories: [], series: [] });
    }
  }

  function applyFilterReportRendumenPengolahan(gudang = null, start = '', end = '') {
    loadChart(gudang, start, end);

    getDataReportRendumenPengolahan(gudang, start, end).done(function(response) {
        const rows = Array.isArray(response?.data) ? response.data : [];
        initializeReportRendumenPengolahanTable(rows);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Request failed:", textStatus, errorThrown, jqXHR.responseText);
    });
  }

  function getDataReportRendumenPengolahan(gudang = null, start = '', end = '') {
    return $.ajax({
        url: base_url + '/report/rendumen-pengolahan/data',
        method: 'GET',
        data: {
          gudang_id: gudang,
          start_date: start,
          end_date: end
        },
        dataType: 'json'
    });
  }

  function initializeReportRendumenPengolahanTable(data) {
    const $dpm = $(".dt-pengolahanTable").first();
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
            { data: 'tg_pengolahan', defaultContent: "-" },
            { data: 'nama_gudang', defaultContent: "-" },
            { data: null, defaultContent: "-" },
            { data: null, defaultContent: "-" },
        ],
        columnDefs: [
            { targets: 0, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1 },
            { targets: 1, render: (d) => d ? formatTanggal(d) : "-" },
            {
                targets: 3,
                render: (data, type, row) => {
                    const daging = row.hasil_olahan_daging ? `${formatAngkaDecimal(row.hasil_olahan_daging)} kg` : "-";
                    const kopra  = row.hasil_olahan_kopra ? `${formatAngkaDecimal(row.hasil_olahan_kopra)} kg` : "-";
                    const kulit  = row.hasil_olahan_kulit ? `${formatAngkaDecimal(row.hasil_olahan_kulit)} kg` : "-";
            
                    return `
                        Daging Kelapa : ${daging}<br>
                        Kopra Kelapa  : ${kopra}<br>
                        Kulit Kelapa  : ${kulit}
                    `;
                }
            },
            {
              targets: 4,
              render: function (data, type, row) {
                  const rendemen = row.rendemen ? Number(row.rendemen) : 0;

                  const statusRendemen = {
                      normal: { title: "Normal", class: "badge-soft-success" },
                      tinggi: { title: "Tinggi", class: "badge-soft-warning" },
                      warning: { title: "⚠ Warning", class: "badge-soft-danger" },
                  };
          
                  let metaRendemen;
                  if (rendemen <= 10) metaRendemen = statusRendemen.normal;
                  else if (rendemen <= 20) metaRendemen = statusRendemen.tinggi;
                  else metaRendemen = statusRendemen.warning;

                  return `
                      <div class="d-flex flex-column align-items-start">
                        <span class="badge ${metaRendemen.class} font-size-12">${metaRendemen.title}</span>
                        <small class="fst-italic text-muted">Rendumen : ${rendemen.toFixed(2)}%</small>
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

  $(document).ready(function() {
      $('#applyReportPengolahanFilter').click(function() {
          const { start, end } = getIsoRange('tanggal_pengolahan');
          const gudang = $('#gudang_id').val() || null;
          applyFilterReportRendumenPengolahan(gudang, start, end);
      });

      $('#resetReportPengolahanFilter').click(function() {
          $('#gudang_id').val('').trigger('change');
          $('#tanggal_pengolahan').val('');
          if (hasDRP()) {
              const drp = $('#tanggal_pengolahan').data('daterangepicker');
              if (drp) {
                  drp.setStartDate(moment());
                  drp.setEndDate(moment());
              }
          }
          applyFilterReportRendumenPengolahan();
      });

      loadChart();
      applyFilterReportRendumenPengolahan();
  });
})();
