<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $komponenGajiUpdate = [
        'takaran_daging_kelapa' => 'required|numeric',
        'upah_takaran_daging'   => 'required|numeric',
        'takaran_kopra_kelapa'  => 'required|numeric',
        'upah_takaran_kopra'    => 'required|numeric',
    ];

    public $komponenGajiUpdate_errors = [
        'takaran_daging_kelapa' => [
            'required' => 'Takaran daging kelapa wajib diisi.',
            'numeric'  => 'Takaran daging kelapa harus angka.',
        ],
        'upah_takaran_daging' => [
            'required' => 'Upah takaran daging wajib diisi.',
            'numeric'  => 'Upah takaran daging harus angka.',
        ],
        'takaran_kopra_kelapa' => [
            'required' => 'Takaran kopra kelapa wajib diisi.',
            'numeric'  => 'Takaran kopra kelapa harus angka.',
        ],
        'upah_takaran_kopra' => [
            'required' => 'Upah takaran kopra wajib diisi.',
            'numeric'  => 'Upah takaran kopra harus angka.',
        ],
    ];

    public $supplyChainPembelian = [
        'tg_pembelian'          => 'required',
        'pem_gudang_id'             => 'required|numeric',
        'berat_kelapa'          => 'required|numeric',
    ];

    public $supplyChainPembelian_errors = [
        'tg_pembelian' => [
            'required' => 'Tanggal Pembelian wajib diisi.',
        ],
        'pem_gudang_id' => [
            'required' => 'Gudang wajib diisi.',
            'numeric'  => 'Gudang harus angka.',
        ],
        'berat_kelapa' => [
            'required' => 'Berat Kelapa wajib diisi.',
            'numeric'  => 'Berat Kelapa harus angka.',
        ],
    ];
    
    public $supplyChainPengolahan = [
        'tg_pengolahan'   => 'required',
        'peng_gudang_id'  => 'required|numeric',
        'peng_pegawai_id' => 'required|numeric',
        'berat_daging'    => 'required|numeric',
        'berat_kopra'     => 'required|numeric',
    ];

    public $supplyChainPengolahan_errors = [
        'tg_pengolahan' => [
            'required' => 'Tanggal Pengolahan wajib diisi.',
        ],
        'peng_gudang_id' => [
            'required' => 'Gudang wajib diisi.',
            'numeric'  => 'Gudang harus angka.',
        ],
        'peng_pegawai_id' => [
            'required' => 'Pegawai wajib diisi.',
            'numeric'  => 'Pegawai harus angka.',
        ],
        'berat_daging' => [
            'required' => 'Berat Daging wajib diisi.',
            'numeric'  => 'Berat Daging harus angka.',
        ],
        'berat_kopra' => [
            'required' => 'Berat Kopra wajib diisi.',
            'numeric'  => 'Berat Kopra harus angka.',
        ],
    ];
    
    public $masterUserRoles = [
        'nama_peran'      => 'required',
        'lingkup_peran'   => 'required',
    ];

    public $masterUserRoles_errors = [
        'nama_peran' => [
            'required' => 'Nama Peran wajib diisi.',
        ],
        'lingkup_peran' => [
            'required'  => 'Lingkup Peran tidak boleh kosong.',
        ],
    ];
    
    public $masterUser = [
        'us_pegawai_id' => 'required|numeric',
        'email'         => 'required|valid_email|is_unique[mt_user.email]',
    ];

    public $masterUser_errors = [
        'us_pegawai_id' => [
            'required' => 'Kode Pegawai wajib diisi.',
            'numeric'  => 'Kode Pegawai harus angka.',
        ],
        'email' => [
            'required'  => 'Email tidak boleh kosong.',
            'email'     => 'Alamat email yang dimasukkan tidak valid.',
        ],
    ];

    public $financePengeluaran = [
        'tg_pengeluaran'            => 'required',
        'peng_ktg_pengeluaran_id'   => 'required|numeric',
        'peng_gudang_id'            => 'required|numeric',
        'peng_pegawai_id'           => 'required|numeric',
        'peng_status'               => 'required',
        'jumlah'                    => 'required|numeric',
        'biaya'                     => 'required|numeric',
    ];

    public $financePengeluaran_errors = [
        'tg_pengeluaran' => [
            'required' => 'Tanggal Pengeluaran wajib diisi.',
        ],
        'peng_ktg_pengeluaran_id' => [
            'required' => 'Kategori Pengeluaran wajib diisi.',
            'numeric'  => 'Kategori Pengeluaran harus angka.',
        ],
        'peng_gudang_id' => [
            'required' => 'Gudang wajib diisi.',
            'numeric'  => 'Gudang harus angka.',
        ],
        'peng_pegawai_id' => [
            'required' => 'Pegawai wajib diisi.',
            'numeric'  => 'Pegawai harus angka.',
        ],
        'peng_status' => [
            'required' => 'Status wajib diisi.',
        ],
        'jumlah' => [
            'required' => 'Jumlah wajib diisi.',
            'numeric'  => 'Jumlah harus angka.',
        ],
        'biaya' => [
            'required' => 'Biaya wajib diisi.',
            'numeric'  => 'Biaya harus angka.',
        ],
    ];

    public $financeGajiPegawai = [
        'data'             => 'required',
        'data.*.kdPegawai' => 'required|is_natural_no_zero',
        'data.*.gudangId'  => 'required|is_natural_no_zero',
    ];

    public $financeGajiPegawai_errors = [
        'data' => [
            'required' => 'Data pegawai harus dipilih.',
        ],
        'data.*.kdPegawai' => [
            'required'           => 'Kode Pegawai wajib diisi.',
            'is_natural_no_zero' => 'Kode Pegawai tidak valid.',
        ],
        'data.*.gudangId' => [
            'required'           => 'Gudang wajib diisi.',
            'is_natural_no_zero' => 'Gudang tidak valid.',
        ],
    ];
}