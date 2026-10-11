<?php

namespace App\Models;

use CodeIgniter\Model;

class WeatherLogModel extends Model
{
    protected $table            = 'weather_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $allowedFields = ['city', 'temperature', 'fetched_at'];

    protected $validationRules = [
        'city'        => 'required|max_length[100]',
        'temperature' => 'required|numeric',
        'fetched_at'  => 'required',
    ];
}
