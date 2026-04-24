<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PspSupportedPaymentMethod extends Model
{
    protected $table = 'psp_supported_payment_methods';
    protected $fillable = ['name', 'label', 'enabled'];
}
