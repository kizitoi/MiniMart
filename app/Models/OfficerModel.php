<?php

namespace App\Models;

use CodeIgniter\Model;

class OfficerModel extends Model
{
    protected $table = 'incidents'; // Base table for reference
    protected $primaryKey = 'id';


    public function getIncidentStats()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT noi.name, COUNT(i.id) AS total_incidents
            FROM nature_of_incidents noi
            JOIN incidents i ON noi.id = i.nature_of_occurrence_id
            GROUP BY noi.id
            HAVING total_incidents > 0
            ORDER BY total_incidents DESC
        ");

        return $query->getResultArray();
    }
}
