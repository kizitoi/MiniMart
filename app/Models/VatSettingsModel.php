<?php

namespace App\Models;

use CodeIgniter\Model;

class VatSettingsModel extends Model
{
    protected $table = 'vat_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['vat_name', 'vat_perc', 'vat_code'];
    protected $useTimestamps = true;
}
  