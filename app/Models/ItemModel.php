<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'item_no', 'category_id', 'name', 'description', 'quantity',
        'unit_price', 'reorder_level_quantity', 'photo', 'vatable', 'vat_id', 'scanned_code'
    ];

    public function getItemsWithCategory($perPage = 10)
{
    return $this->select('items.*, item_categories.category_name, vat_settings.vat_name')
                ->join('item_categories', 'item_categories.id = items.category_id', 'left')
                ->join('vat_settings', 'vat_settings.id = items.vat_id', 'left')
                ->paginate($perPage);
}


public function getFilteredItems($filter = null)
{
    $builder = $this->select('items.*, item_categories.category_name, vat_settings.vat_name')
                    ->join('item_categories', 'item_categories.id = items.category_id', 'left')
                    ->join('vat_settings', 'vat_settings.id = items.vat_id', 'left');

    if ($filter) {
        $builder->like('items.name', $filter);
    }

    return $builder->findAll();
}


}
