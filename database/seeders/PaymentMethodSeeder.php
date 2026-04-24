<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'card', 'label' => 'Credit/Debit Card'],
            ['name' => 'klarna', 'label' => 'Klarna (Pay Later)'],
            ['name' => 'sepa_debit', 'label' => 'SEPA Direct Debit (IBAN)'],
            ['name' => 'afterpay_clearpay', 'label' => 'AfterPay / ClearPay'],
            ['name' => 'sofort', 'label' => 'Sofort Banking'],
            ['name' => 'ideal', 'label' => 'iDEAL (Netherlands)'],
            ['name' => 'bancontact', 'label' => 'Bancontact (Belgium)'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(['name' => $method['name']], $method);
        }
    }
}

