<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = [
        'page' => 'Index',
        'block' => 'main_content',
        'content' => "<h2>Привет, друг! 🖐</h2><p>С помощью нашей сотовой связи ты можешь совершать звонки кому угодно абсолютно бесплатно!</p><p>Для этого нужно всего лишь авторизоваться 💡. А если нету аккаунта, то время создавать!</p><p>С нами ты можешь сэкономить, общаясь со своими друзьями, родственниками, товарищами или коллегами!</p><p>Если тебе кто-то не понравился, <strong>добавь его в черный список</strong>, нажав на плюсик возле IMEI</p>",
        'updated_at' => Carbon::now(),
        'created_at' => Carbon::now()
      ];
      DB::table('pages')->insert($data);
    }
}
