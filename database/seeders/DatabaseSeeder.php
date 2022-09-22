<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            asset('assets/images/categories/menu-1.jpg'),
            asset('assets/images/categories/menu-2.jpg'),
            asset('assets/images/categories/menu-3.jpg')
        ];
        $images_product = [
            'menu-1.jpg',
            'menu-2.jpg',
            'menu-3.jpg',
        ];
        for ($i = 0; $i < 10; $i++) {
            Category::create([
                'name' => 'name_' . $i,
                'image' => $images[rand(0, 2)]
            ]);
        }
        for ($i = 0; $i < 50; $i++) {
            Product::create([
                'name' => 'E823985' . $i,
                'details' => 'details' . $i,
                'price' => rand(100, 5000),
                'category_id'=>1,
                'images' => $images_product[rand(0, 2)] . '|' .  $images_product[rand(0, 2)] .  '|' .  $images_product[rand(0, 2)]
            ]);
        }
        Country::create([
            'name' => 'فلسطين',
        ]);
        #region City
        City::create([
            'name' => 'يافا',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'الخليل',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'بيت لحم',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'الناصرة',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'نابلس',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'غزة',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'صفد',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'بئر السبع',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'أريحا',
            'country_id'=>1
        ]);
        Area::create([
            'name' => 'منطقه 1',
            'city_id'=>1
        ]);
        Area::create([
            'name' => 'منطقه 2',
            'city_id'=>1
        ]);
        #endregion
        Admin::create([
           'name'=>'admin',
            'email'=>'admin@test.com',
            'phone'=>'01068298958',
            'image'=>'',
            'password'=> Hash::make(123456)
        ]);

    }
}
