<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PspSupportedPaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('psp_supported_payment_methods')->insert([
            ['psp_vendor_id'=>1,'name'=>'card','label'=>'Credit / Debit Card','enabled'=>1]
        ]);
    }
}
