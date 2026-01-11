<?php

namespace App\Models;

use CodeIgniter\Model;

class PosListModel extends Model
{
    protected $table = 'pos_list';
    protected $primaryKey = 'id';
    protected $allowedFields = ['shop_id', 'item_id', 'selling_price'];

    public function getPOSData()
    {
        return $this->select('
                pos_list.*,
                shops.name as shop_name,
                items.item_no,
                items.name as item_name,
                items.description,
                items.quantity,
                items.photo,
                items.reorder_level_quantity,
                items.vatable,
                item_categories.category_name,
                vat_settings.vat_code
            ')
            ->join('shops', 'shops.id = pos_list.shop_id')
            ->join('items', 'items.id = pos_list.item_id')
            ->join('item_categories', 'item_categories.id = items.category_id')
            ->join('vat_settings', 'vat_settings.id = items.vat_id', 'left')
            ->findAll();
    }
}
