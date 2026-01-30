<?php

namespace Database\Seeders;

use App\Models\ReliefPackItem;
use Illuminate\Database\Seeder;

class ReliefPackItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Mother Formula based on DSWD Relief Pack standards
     */
    public function run(): void
    {
        $items = [
            // Food Pack
            ['pack_type' => 'food', 'item_name' => 'Rice', 'quantity_per_family' => 6, 'unit' => 'kg'],
            ['pack_type' => 'food', 'item_name' => 'Coffee Sachets', 'quantity_per_family' => 5, 'unit' => 'sachets'],
            ['pack_type' => 'food', 'item_name' => 'Powdered Cereal Drink Sachets', 'quantity_per_family' => 5, 'unit' => 'sachets'],
            ['pack_type' => 'food', 'item_name' => 'Corned Beef', 'quantity_per_family' => 4, 'unit' => 'tins'],
            ['pack_type' => 'food', 'item_name' => 'Tuna', 'quantity_per_family' => 4, 'unit' => 'tins'],
            ['pack_type' => 'food', 'item_name' => 'Sardines', 'quantity_per_family' => 2, 'unit' => 'tins'],

            // Kitchen Pack
            ['pack_type' => 'kitchen', 'item_name' => 'Spoon', 'quantity_per_family' => 5, 'unit' => 'pcs'],
            ['pack_type' => 'kitchen', 'item_name' => 'Fork', 'quantity_per_family' => 5, 'unit' => 'pcs'],
            ['pack_type' => 'kitchen', 'item_name' => 'Drinking Glass', 'quantity_per_family' => 5, 'unit' => 'pcs'],
            ['pack_type' => 'kitchen', 'item_name' => 'Plate', 'quantity_per_family' => 5, 'unit' => 'pcs'],
            ['pack_type' => 'kitchen', 'item_name' => 'Frying Pan', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'kitchen', 'item_name' => 'Cooking Pan', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'kitchen', 'item_name' => 'Ladle', 'quantity_per_family' => 1, 'unit' => 'pcs'],

            // Hygiene Pack
            ['pack_type' => 'hygiene', 'item_name' => 'Toothbrush', 'quantity_per_family' => 5, 'unit' => 'pcs'],
            ['pack_type' => 'hygiene', 'item_name' => 'Toothpaste', 'quantity_per_family' => 2, 'unit' => 'pcs'],
            ['pack_type' => 'hygiene', 'item_name' => 'Shampoo Bottle', 'quantity_per_family' => 1, 'unit' => 'bottle'],
            ['pack_type' => 'hygiene', 'item_name' => 'Bath Bar Soap', 'quantity_per_family' => 4, 'unit' => 'pcs'],
            ['pack_type' => 'hygiene', 'item_name' => 'Laundry Bar Soap', 'quantity_per_family' => 2000, 'unit' => 'grams'],
            ['pack_type' => 'hygiene', 'item_name' => 'Sanitary Napkin', 'quantity_per_family' => 4, 'unit' => 'packs'],
            ['pack_type' => 'hygiene', 'item_name' => 'Comb', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'hygiene', 'item_name' => 'Disposable Shaving Razor', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'hygiene', 'item_name' => 'Nail Cutter', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'hygiene', 'item_name' => 'Bathroom Dipper', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'hygiene', 'item_name' => '20L Plastic Bucket with Cover', 'quantity_per_family' => 1, 'unit' => 'pcs'],

            // Sleeping Pack
            ['pack_type' => 'sleeping', 'item_name' => 'Blanket', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'sleeping', 'item_name' => 'Plastic Mat', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'sleeping', 'item_name' => 'Mosquito Net', 'quantity_per_family' => 1, 'unit' => 'pcs'],
            ['pack_type' => 'sleeping', 'item_name' => 'Malong (Wrap Cloth)', 'quantity_per_family' => 1, 'unit' => 'pcs'],

            // Clothing Pack
            ['pack_type' => 'clothing', 'item_name' => 'Bath Towel', 'quantity_per_family' => 5, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Ladies Panty', 'quantity_per_family' => 2, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Girls Panty', 'quantity_per_family' => 3, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Mens Brief', 'quantity_per_family' => 2, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Boys Brief', 'quantity_per_family' => 3, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Sando Bra Adult', 'quantity_per_family' => 2, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Sando Bra Girls', 'quantity_per_family' => 3, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Adults T-Shirt', 'quantity_per_family' => 4, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Childrens T-Shirt', 'quantity_per_family' => 6, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Adults Short Pants', 'quantity_per_family' => 4, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Childrens Short', 'quantity_per_family' => 6, 'unit' => 'pcs'],
            ['pack_type' => 'clothing', 'item_name' => 'Adults Slippers', 'quantity_per_family' => 2, 'unit' => 'pairs'],
            ['pack_type' => 'clothing', 'item_name' => 'Childrens Slippers', 'quantity_per_family' => 3, 'unit' => 'pairs'],
        ];

        foreach ($items as $item) {
            ReliefPackItem::updateOrCreate(
                ['pack_type' => $item['pack_type'], 'item_name' => $item['item_name']],
                $item
            );
        }
    }
}
