<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PspTransaction extends Model
{
    protected $table = 'psp_transactions';

    protected $fillable = [
        'transaction_id',
        'psp_vendor_id',
        'psp_intent_id',
        'psp_charge_id',
        'amount',
        'currency',
        'status',
        'payload', 
    ];

    protected $casts = [
        'payload' => 'array', // ✅ AUTO json_encode / decode
    ];
}