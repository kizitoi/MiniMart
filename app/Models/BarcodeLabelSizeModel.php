<?php
namespace App\Models;

use CodeIgniter\Model;

class BarcodeLabelSizeModel extends Model
{
    protected $table = 'barcode_label_sizes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'width_mm', 'height_mm', 'user_id'];
}
