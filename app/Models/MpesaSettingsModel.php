<?php namespace App\Models;
use CodeIgniter\Model;

class MpesaSettingsModel extends Model {
    protected $table = 'mpesa_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'paybill','till_number','consumer_key','consumer_secret','shortcode','passkey','active'
    ];
}
