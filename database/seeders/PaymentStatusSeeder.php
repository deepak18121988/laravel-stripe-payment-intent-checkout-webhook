<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_statuses')->insert([
            ['id'=>1,'code'=>'pending','label'=>'Pending','description'=>'Payment initiated but not yet completed','is_terminal'=>0],
            ['id'=>2,'code'=>'completed','label'=>'Completed','description'=>'Payment successfully completed','is_terminal'=>1],
            ['id'=>3,'code'=>'failed','label'=>'Failed','description'=>'Payment failed or was declined','is_terminal'=>1],
            ['id'=>4,'code'=>'refunded','label'=>'Refunded','description'=>'Payment was refunded to the buyer','is_terminal'=>1],
            ['id'=>5,'code'=>'disputed','label'=>'Disputed','description'=>'Payment is under dispute or chargeback','is_terminal'=>0],
        ]);
    }
}
