<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PspVendorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('psp_vendors')->insert([
            ['id'=>1,'vendor_code'=>1001,'name'=>'Stripe','description'=>'Global PSP for cards and digital wallets','is_active'=>1]
        ]);
    }
}
