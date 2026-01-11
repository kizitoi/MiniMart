<?php

namespace App\Models;

use App\Config\FirebaseConfig;

class FirebaseModel
{
    protected $db;

    public function __construct()
    {
        $firebaseConfig = new FirebaseConfig();
        $this->db = $firebaseConfig->getDatabase();
    }

    public function getBusinesses()
    {
        $reference = $this->db->getReference('spacesurembohygieneforschoolsnandi');
        return $reference->getValue();
    }
}
