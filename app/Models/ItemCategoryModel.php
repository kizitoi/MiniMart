<?php namespace App\Models;

use CodeIgniter\Model;

class ItemCategoryModel extends Model
{
    protected $table = 'item_categories';
    protected $primaryKey = 'id';
     // Include 'category_image' if you're saving it to the DB
    protected $allowedFields = ['category_name', 'shop_id', 'category_image','description'];

    protected $useTimestamps = true;

    public function getItemCategories()
    {
        return $this->select('item_categories.*, shops.name as shop_name')
                    ->join('shops', 'shops.id = item_categories.shop_id', 'left')
                    ->findAll();
    }

    public function getShops()
    {
        return $this->db->table('shops')->get()->getResult();
    }


    public function getItemCategoriesWithItemCount($shopId = null)
{
    $builder = $this->db->table('item_categories')
        ->select('item_categories.*, shops.name as shop_name, COUNT(items.id) as item_count')
        ->join('shops', 'shops.id = item_categories.shop_id', 'left')
        ->join('items', 'items.category_id = item_categories.id', 'left')
        ->groupBy('item_categories.id');

    if ($shopId) {
        $builder->where('item_categories.shop_id', $shopId);
    }

    return $builder->get()->getResultArray();
}

}
