<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemUnitsOfMeasureModel extends Model
{
    protected $table = 'items_units_of_measure';
    protected $primaryKey = 'id';
    protected $allowedFields = ['item_id', 'unit_id', 'unit_value'];

    public function getItemsUnits($itemId = null)
    {
        if ($itemId) {
            return $this->where('item_id', $itemId)->findAll();
        }
        return $this->findAll();
    }
}
