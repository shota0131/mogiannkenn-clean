<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => '良好',
            ],
            [
                
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
            [ 
                
                'name' => '玉ねぎ３束', 
                'price' => 300, 
                'brand_name' => null, 
                'description' => '新鮮な玉ねぎ３束のセット', 
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg', 
                'condition' => 'やや傷や汚れあり', 
            ],
            [ 
                
                'name' => '革靴', 
                'price' => 4000, 
                'brand_name' => null, 
                'description' => 'クラシックなデザインの革靴', 
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg', 
                'condition' => '状態が悪い',
            ],
            [ 
                
                'name' => 'ノートPC', 
                'price' => 45000, 
                'brand_name' => null, 
                'description' => '高性能なノートパソコン', 
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg', 
                'condition' => '良好',
            ],
            [ 
                
                'name' => 'マイク', 
                'price' => 8000, 
                'brand_name' => null, 
                'description' => '高音質のレコーディング用マイク', 
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg', 
                'condition' => '目立った傷や汚れなし',
            ],
            [ 
                
                'name' => 'ショルダーバッグ', 
                'price' =>3500, 
                'brand_name' => null, 
                'description' => 'おしゃれなショルダーバッグ', 
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg', 
                'condition' => 'やや傷や汚れあり',
            ],
            [ 
                
                'name' => 'コーヒーミル', 
                'price' => 4000, 
                'brand_name' => 'Starbacks', 
                'description' => '手動のコーヒーミル', 
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg', 
                'condition' => '良好',
            ],
            [ 
                
                'name' => 'メイクセット', 
                'price' => 2500, 
                'brand_name' => null, 
                'description' => '便利なメイクアップセット', 
                'img_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg', 
                'condition' => '目立った傷や汚れなし',
            ],
        ];

        $users = User::all();

        foreach ($items as $item) {

            $randomUser = $users->random();
            
            $imageContents = file_get_contents($item['img_path']);
            
            
            $filename = 'items/' . Str::uuid() . '.jpg';
            
            
            Storage::disk('public')->put($filename, $imageContents);

            $itemId = DB::table('items')->insertGetId([
                'user_id' => $randomUser->id,
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'],
                'condition_id' => $this->mapCondition($item['condition']),
                'brand'   => $item['brand'] ?? null,
                'img_path' => $filename,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        
            
            $category = Category::inRandomOrder()->first();
        
            
            DB::table('category_items')->insert([
                'item_id' => $itemId,
                'category_id' => $category->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
    private function mapCondition($conditionText)
    {
        switch ($conditionText){
            case '良好': return 1;
            case '目立った傷や汚れなし': return 2;
            case 'やや傷や汚れあり': return 3;
            case '状態が悪い': return 4;
            default: return null;
        }
    }
}