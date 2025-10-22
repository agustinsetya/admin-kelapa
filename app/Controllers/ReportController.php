<?php

namespace App\Controllers;

use App\Models\LogPengolahanModel;
use App\Models\PengolahanModel;
use App\Models\GajiPegawaiModel;
use App\Models\GajiDriverModel;
use App\Models\GajiPacakMesinModel;
use App\Models\KomponenGajiModel;
use App\Models\GudangModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\Concerns\ApiResponse;

class ReportController extends AuthRequiredController
{
    use ApiResponse;

    protected $logPengolahanModel;
    protected $pengolahanModel;
    protected $gajiPegawaiModel;
    protected $gajiDriverModel;
    protected $gajiPacakMesinModel;
    protected $komponenGajiModel;
    protected $gudangModel;

    public function __construct()
    {
        $this->logPengolahanModel = new LogPengolahanModel();
        $this->pengolahanModel = new PengolahanModel();
        $this->gajiPegawaiModel = new GajiPegawaiModel();
        $this->gajiDriverModel = new GajiDriverModel();
        $this->gajiPacakMesinModel = new GajiPacakMesinModel();
        $this->komponenGajiModel = new KomponenGajiModel();
        $this->gudangModel = new GudangModel();
    }

    public function showReportPengolahan()
    {
        $gudang = $this->gudangModel->getDataGudang();

        $data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Report_Pengolahan'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Report_Pengolahan',
				'li_1'  => lang('Files.Report'),
				'li_2'  => lang('Files.Report_Pengolahan')
            ]),
            'gudang' => $gudang,
		];

        return view('report-pengolahan', $data);
    }
    
    public function showReportKomponenGaji()
    {
        $roleScope = session()->get('role_scope');

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Komponen_Gaji']),
            'page_title' => view('partials/page-title', [
                'title' => 'Komponen_Gaji',
                'li_1'  => lang('Files.Report'),
                'li_2'  => lang('Files.Komponen_Gaji'),
            ]),
            'roleScope' => $roleScope,
        ];

        return view('report-komponen-gaji', $data);
    }

    public function showReportGajiPegawai()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Report_Gaji_Pegawai'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Report_Gaji_Pegawai',
				'li_1'  => lang('Files.Report'),
				'li_2'  => lang('Files.Report_Gaji_Pegawai')
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
		];
		
		return view('report-gaji-pegawai', $data);
	}
    
    public function showReportGajiDriver()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Report_Gaji_Driver'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Report_Gaji_Driver',
				'li_1'  => lang('Files.Report'),
				'li_2'  => lang('Files.Report_Gaji_Driver')
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
		];
		
		return view('report-gaji-driver', $data);
	}
    
    public function showReportGajiPacakMesin()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Report_Gaji_Pacak_Mesin'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Report_Gaji_Pacak_Mesin',
				'li_1'  => lang('Files.Report'),
				'li_2'  => lang('Files.Report_Gaji_Pacak_Mesin')
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
		];
		
		return view('report-gaji-pacak-mesin', $data);
	}

    public function getReportPengolahan(): ResponseInterface
    {
        $gudangId  = $this->request->getGet('gudang_id');
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        // Ambil raw rows dari model dengan filter
        $rows = $this->logPengolahanModel->getDataLogPengolahan([
            'gudang_id'  => is_numeric($gudangId) ? (int)$gudangId : null,
            'start_date' => $startDate ?: null,
            'end_date'   => $endDate ?: null,
        ]);

        // Agregasi total per gudang
        $agg = []; // ['nama_gudang' => ['daging'=>..., 'kopra'=>...]]
        foreach ($rows as $r) {
            $g = $r->nama_gudang ?? 'Tanpa Nama';
            if (!isset($agg[$g])) $agg[$g] = ['daging' => 0.0, 'kopra' => 0.0, 'kulit' => 0.0];
            $agg[$g]['daging'] += (float) ($r->berat_daging ?? 0);
            $agg[$g]['kopra']  += (float) ($r->berat_kopra  ?? 0);
            $agg[$g]['kulit']  += (float) ($r->berat_kulit  ?? 0);
        }

        // Susun payload ApexCharts
        $categories = array_keys($agg);
        $daging     = array_map(fn($g) => $agg[$g]['daging'], $categories);
        $kopra      = array_map(fn($g) => $agg[$g]['kopra'],  $categories);
        $kulit      = array_map(fn($g) => $agg[$g]['kulit'],  $categories);

        return $this->response->setJSON([
            'ok'         => true,
            'categories' => $categories,
            'series'     => [
                ['name' => 'Daging (kg)', 'data' => $daging],
                ['name' => 'Kopra (kg)',  'data' => $kopra],
                ['name' => 'Kulit (kg)',  'data' => $kulit],
            ],
        ]);
    }

    public function getReportRendumenPengolahan()
    {
        $gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$filters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$filters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $filters['tg_pengeluaran_start'] = $start;
		if (!empty($end))   $filters['tg_pengeluaran_end']   = $end;
        
        $pengolahan = $this->pengolahanModel->getDataPengolahan($filters);

        return $this->jsonSuccess(['data' => $pengolahan]);
    }

    public function getReportGajiPegawai()
    {
        $roleFilters	= $this->filtersFromUser();

		$gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$queryFilters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$queryFilters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $queryFilters['start_date'] = $start;
		if (!empty($end))   $queryFilters['end_date']   = $end;

		$filters = array_merge($queryFilters, $roleFilters);

        $gajiPegawai = $this->gajiPegawaiModel->getDataGajiPegawai($filters);

        return $this->jsonSuccess(['data' => $gajiPegawai]);
    }
    
    public function getReportGajiDriver()
    {
        $roleFilters	= $this->filtersFromUser();

		$gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$queryFilters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$queryFilters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $queryFilters['start_date'] = $start;
		if (!empty($end))   $queryFilters['end_date']   = $end;

		$filters = array_merge($queryFilters, $roleFilters);

        $gajiDriver = $this->gajiDriverModel->getDataGajiDriver($filters);

        return $this->jsonSuccess(['data' => $gajiDriver]);
    }
    
    public function getReportGajiPacakMesin()
    {
        $roleFilters	= $this->filtersFromUser();

		$gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$queryFilters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$queryFilters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $queryFilters['start_date'] = $start;
		if (!empty($end))   $queryFilters['end_date']   = $end;

		$filters = array_merge($queryFilters, $roleFilters);

        $gajiPacakMesin = $this->gajiPacakMesinModel->getDataGajiPacakMesin($filters);

        return $this->jsonSuccess(['data' => $gajiPacakMesin]);
    }

    public function getReportKomponenGaji()
    {
		$komponenGaji = $this->komponenGajiModel->getDataKomponenGaji();

        return $this->response->setJSON([
            'data' => $komponenGaji
        ]);
    }

    private function filtersFromUser(): array
    {
        $user = session()->get('user');
        if (!$user) {
            return [];
        }

        if (($user->role_scope ?? null) === 'gudang') {
            return ['gudang_id' => $user->penempatan_id ?? ''];
        }

        return [];
    }
}
