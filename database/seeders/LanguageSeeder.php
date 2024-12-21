<?php

namespace Database\Seeders;

use App\Models\TxLang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $langs = [
            ['name' => 'Қазақша', 'code' => 'kk-KZ'],
            ['name' => 'Русский', 'code' => 'ru_RU']
        ];

        foreach ($langs as $lang) {
            TxLang::updateOrCreate(['code' => $lang['code']], $lang);
        }
    }
}
