<?php namespace App\Models;

use CodeIgniter\Model;

class NumberingSettingsModel extends Model
{
    protected $table = 'numbering';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'apply_to', 'prefix', 'start', 'last_used',
        'auto_increment', 'allow_manual_entry'
    ];
    protected $useTimestamps = true;
}
