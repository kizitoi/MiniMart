<?php

namespace App\Models;

use CodeIgniter\Model;

class OfficerDetailsModel extends Model
{
    protected $table = 'officer_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'admission_number','user_id', 'first_name', 'last_name', 'hostel_id', 'room_number', 'floor', 'start_date', 'end_date',
        'year_of_study', 'campus_name', 'course', 'gender', 'contact_mobile', 'contact_email', 'officer_age',
        'photo', 'laptop_make', 'laptop_model', 'laptop_serial_number', 'mobile_make', 'mobile_imei_number',
        'camera_model', 'camera_make', 'camera_serial_number', 'ironing_box', 'electric_kettle'
    ];


      public function getOfficersWithHostelName()
    {
        return $this->select('officer_details.*, hostels.name as hostel_name')
                    ->join('hostels', 'hostels.hostel_id = officer_details.hostel_id', 'left')
                    ->findAll();
    }



      public function getHostelOfficerCount($hostelId)
    {
        return $this->where('hostel_id', $hostelId)->countAllResults();
    }




    public function getOfficers($search = '', $limit = 10, $offset = 0)
    {
        $builder = $this->select('officer_details.*, hostels.name as hostel_name')
                        ->join('hostels', 'hostels.hostel_id = officer_details.hostel_id', 'left');

        if ($search) {
            $builder->like('first_name', $search)
                    ->orLike('last_name', $search)
                    ->orLike('course', $search)
                    ->orLike('campus_name', $search)
                    ->orLike('hostels.name', $search);
        }

        return $builder->orderBy('id', 'DESC')
                       ->limit($limit, $offset)
                       ->get()
                       ->getResultArray();
    }

    public function countAllOfficers($search = '')
    {
        $builder = $this->select('officer_details.*, hostels.name as hostel_name')
                        ->join('hostels', 'hostels.hostel_id = officer_details.hostel_id', 'left');

        if ($search) {
            $builder->like('first_name', $search)
                    ->orLike('last_name', $search)
                    ->orLike('course', $search)
                    ->orLike('campus_name', $search)
                    ->orLike('hostels.name', $search);
        }

        return $builder->countAllResults();
    }


  public function getOfficersWithHostelNameAndPhoto()
{
    return $this->select('officer_details.*, hostels.name as hostel_name')
                ->join('hostels', 'hostels.hostel_id = officer_details.hostel_id', 'left')
                ->findAll();
}



}
