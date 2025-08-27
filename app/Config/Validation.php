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
}
