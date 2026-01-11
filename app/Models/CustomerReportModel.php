<?php namespace App\Models;

use CodeIgniter\Model;

class CustomerReportModel extends Model
{
    protected $table = 'customer_reports';
    protected $primaryKey = 'report_id';
    protected $allowedFields = ['customer_id', 'incident_id'];
}
