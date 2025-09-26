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

    public $supplyChainPembelian = [
        'tg_pembelian'          => 'required',
        'pem_gudang_id'         => 'required|numeric',
        'kode_container'        => 'required',
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
        'kode_container' => [
            'required' => 'Kode Container wajib diisi.',
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
    
    public $supplyChainPengiriman = [
        'tg_pengiriman'   => 'required',
        'peng_gudang_id'  => 'required|numeric',
        'peng_pegawai_id' => 'required|numeric',
        'berat_daging'    => 'required|numeric',
    ];

    public $supplyChainPengiriman_errors = [
        'tg_pengiriman' => [
            'required' => 'Tanggal Pengiriman wajib diisi.',
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
    ];
    
    public $supplyChainPenjualan = [
        'tg_penjualan'          => 'required',
        'log_pengiriman_id'     => 'required|numeric',
        'daging_kelapa_terima'  => 'required|numeric',
        'daging_kelapa_reject'  => 'required|numeric',
    ];

    public $supplyChainPenjualan_errors = [
        'tg_penjualan' => [
            'required' => 'Tanggal Penjualan wajib diisi.',
        ],
        'log_pengiriman_id' => [
            'required' => 'ID Pengiriman wajib diisi.',
            'numeric'  => 'ID Pengiriman harus angka.',
        ],
        'daging_kelapa_terima' => [
            'required' => 'Berat Daging Kelapa yang Diterima wajib diisi.',
            'numeric'  => 'Berat Daging Kelapa yang Diterima harus angka.',
        ],
        'daging_kelapa_reject' => [
            'required' => 'Berat Daging Kelapa yang Ditolak wajib diisi.',
            'numeric'  => 'Berat Daging Kelapa yang Ditolak harus angka.',
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
    
    public $masterUserAdd = [
        'us_pegawai_id' => 'required|numeric',
        'email'         => 'required|valid_email|is_unique[mt_user.email]',
    ];
    
    public $masterUserUpdate = [
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

    public $masterPegawai = [
        // 'kd_pegawai'      => 'required|numeric',
        'nama_pegawai'      => 'required',
        'jenis_kelamin' => 'required|in_list[P,L]',
        'peg_role_id'   => 'required|numeric',
        'pg_gudang_id'   => 'permit_empty|numeric',
    ];

    public $masterPegawai_errors = [
        // 'kd_pegawai' => [
        //     'required' => 'Kode Pegawai wajib diisi.',
        //     'numeric'  => 'Kode Pegawai harus angka.',
        // ],
        'nama_pegawai' => [
            'required' => 'Nama Pegawai wajib diisi.',
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis Kelamin wajib dipilih.',
            'in_list'  => 'Jenis Kelamin hanya boleh diisi dengan P atau L.',
        ],
        'peg_role_id' => [
            'required'  => 'Peran tidak boleh kosong.',
            'numeric'  => 'Peran harus angka.',
        ],
        'pg_gudang_id' => [
            'numeric'  => 'Penempatan harus angka.',
        ],
    ];

    public $masterGudang = [
        'nama_gudang'           => 'required',
        'takaran_daging_kelapa' => 'required|numeric',
        'upah_takaran_daging'   => 'required|numeric',
        'takaran_kopra_kelapa'  => 'required|numeric',
        'upah_takaran_kopra'    => 'required|numeric',
        'takaran_kulit_kelapa'  => 'required|numeric',
        'upah_takaran_kulit'    => 'required|numeric',
    ];

    public $masterGudang_errors = [
        'nama_gudang' => [
            'required' => 'Nama Gudang wajib diisi.',
        ],
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
        'takaran_kulit_kelapa' => [
            'required' => 'Takaran kulit kelapa wajib diisi.',
            'numeric'  => 'Takaran kulit kelapa harus angka.',
        ],
        'upah_takaran_kulit' => [
            'required' => 'Upah takaran kulit wajib diisi.',
            'numeric'  => 'Upah takaran kulit harus angka.',
        ],
    ];

    public $masterKategoriPengeluaran = [
        'nama_kategori'  => 'required',
        'ket_kategori'   => 'required',
    ];

    public $masterKategoriPengeluaran_errors = [
        'nama_kategori' => [
            'required' => 'Nama Kategori Pengeluaran wajib diisi.',
        ],
        'ket_kategori' => [
            'required'  => 'Keterangan Kategori Pengeluaran tidak boleh kosong.',
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
    
    public $financeKasbon = [
        'tg_kasbon'          => 'required',
        'kb_gudang_id'       => 'required|numeric',
        'kb_pegawai_id'      => 'required|numeric',
        'kb_status'          => 'required',
        'jumlah'             => 'required|numeric',
    ];

    public $financeKasbon_errors = [
        'tg_kasbon' => [
            'required' => 'Tanggal Kasbon wajib diisi.',
        ],
        'kb_gudang_id' => [
            'required' => 'Gudang wajib diisi.',
            'numeric'  => 'Gudang harus angka.',
        ],
        'kb_pegawai_id' => [
            'required' => 'Pegawai wajib diisi.',
            'numeric'  => 'Pegawai harus angka.',
        ],
        'kb_status' => [
            'required' => 'Status wajib diisi.',
        ],
        'jumlah' => [
            'required' => 'Jumlah wajib diisi.',
            'numeric'  => 'Jumlah harus angka.',
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
    
    public $financeGajiDriver = [
        'data'             => 'required',
        'data.*.kdPegawai' => 'required|is_natural_no_zero',
        'data.*.gudangId'  => 'required|is_natural_no_zero',
    ];

    public $financeGajiDriver_errors = [
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