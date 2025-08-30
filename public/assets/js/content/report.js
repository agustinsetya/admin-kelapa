// public/assets/js/content/report.js
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

  
  function initRangePicker() {
    const el = byId('tanggal_pengolahan');
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

  
  function getIsoRange() {
    const el = byId('tanggal_pengolahan');
    if (!el || !el.value || !hasDRP()) return { start: '', end: '' };
    const drp = $('#tanggal_pengolahan').data('daterangepicker');
    if (!drp) return { start: '', end: '' };
    return {
      start: drp.startDate ? drp.startDate.format('YYYY-MM-DD') : '',
      end:   drp.endDate   ? drp.endDate.format('YYYY-MM-DD')   : ''
    };
  }

  async function fetchChartData() {
    const gudangEl = byId('gudang_id');
    const gudang_id = gudangEl && gudangEl.value ? gudangEl.value : '';
    const { start, end } = getIsoRange();

    const params = new URLSearchParams();
    if (gudang_id) params.append('gudang_id', gudang_id); // kosong = semua gudang
    if (start)     params.append('start_date', start);
    if (end)       params.append('end_date', end);

    const url = `${base_url}/report/chart-data${params.toString() ? '?' + params.toString() : ''}`;
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
    if (wrap) wrap.scrollLeft = 0; // reset posisi scroll
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

  async function loadChart() {
    try {
      const data = await fetchChartData();
      if (!data.categories || data.categories.length === 0) {
        renderChart({
          categories: [],
          series: [
            { name: 'Daging (kg)', data: [] },
            { name: 'Kopra (kg)',  data: [] }
          ]
        });
        return;
      }
      renderChart({ categories: data.categories, series: data.series });
    } catch (err) {
      console.error('[report] Gagal memuat data chart:', err);
      renderChart({ categories: [], series: [] });
    }
  }

  function bindUI() {
    const applyBtn = byId('applyReportFilter');
    if (applyBtn) applyBtn.addEventListener('click', loadChart);

    const resetBtn = byId('resetReportFilter');
    if (resetBtn) {
      resetBtn.addEventListener('click', function () {
        const gudangEl = byId('gudang_id');
        const rangeEl  = byId('tanggal_pengolahan');
        if (gudangEl) gudangEl.selectedIndex = 0; // "Semua Gudang"
        if (rangeEl)  rangeEl.value = '';

        // reset internal state DRP ke hari ini (opsional)
        if (hasDRP()) {
          const drp = $('#tanggal_pengolahan').data('daterangepicker');
          if (drp) { drp.setStartDate(moment()); drp.setEndDate(moment()); }
        }

        loadChart();
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (!ensureDeps()) return;
    bindUI();
    initRangePicker(); 
    loadChart();       
  });
})();
