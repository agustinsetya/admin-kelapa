<?php

namespace App\Controllers;

use App\Models\PengolahanModel;
use App\Models\GudangModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class ReportController extends AuthRequiredController
{
    protected $pengolahanModel;
    protected $gudangModel;

    public function __construct()
    {
        $this->pengolahanModel = new PengolahanModel();
        $this->gudangModel = new GudangModel();
    }

    public function showReportPengolahan()
    {
        $gudang = $this->gudangModel->findAll();

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

        return view('report', $data);
    }

    public function getReportPengolahan(): ResponseInterface
    {
        $gudangId  = $this->request->getGet('gudang_id');
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        // Ambil raw rows dari model dengan filter
        $rows = $this->pengolahanModel->getDataPengolahan([
            'gudang_id'  => is_numeric($gudangId) ? (int)$gudangId : null,
            'start_date' => $startDate ?: null,
            'end_date'   => $endDate ?: null,
        ]);

        // Agregasi total per gudang
        $agg = []; // ['nama_gudang' => ['daging'=>..., 'kopra'=>...]]
        foreach ($rows as $r) {
            $g = $r->nama_gudang ?? 'Tanpa Nama';
            if (!isset($agg[$g])) $agg[$g] = ['daging' => 0.0, 'kopra' => 0.0];
            $agg[$g]['daging'] += (float) ($r->berat_daging ?? 0);
            $agg[$g]['kopra']  += (float) ($r->berat_kopra  ?? 0);
        }

        // Susun payload ApexCharts
        $categories = array_keys($agg);
        $daging     = array_map(fn($g) => $agg[$g]['daging'], $categories);
        $kopra      = array_map(fn($g) => $agg[$g]['kopra'],  $categories);

        return $this->response->setJSON([
            'ok'         => true,
            'categories' => $categories,
            'series'     => [
                ['name' => 'Daging (kg)', 'data' => $daging],
                ['name' => 'Kopra (kg)',  'data' => $kopra],
            ],
        ]);
    }
}
