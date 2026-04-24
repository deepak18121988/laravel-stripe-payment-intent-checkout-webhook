<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionBookingResale
 * 
 * @property int $Transaction_ID
 * @property int $Listing_ID
 * @property string $Buyer_ID
 * @property int|null $Payment_ID
 * @property string $Purchase_Ref_Number
 * @property float $Sale_Price
 * @property float|null $App_Fee
 * @property Carbon|null $Transaction_Date
 * @property int $payment_status_id
 * @property int $psp_vendor_id
 * 
 * @property Listing $listing
 * @property User $user
 * @property PaymentDetail|null $payment_detail
 * @property ListingsIdsPool $listings_ids_pool
 * @property PaymentStatus $payment_status
 * @property PspVendor $psp_vendor
 *
 * @package App\Models
 */
class TransactionBookingResale extends Model
{
	protected $table = 'transaction_booking_resales';
	protected $primaryKey = 'Transaction_ID';
	public $timestamps = false;

	protected $casts = [
		'Listing_ID' => 'int',
		'Buyer_ID' => 'binary',
		'Payment_ID' => 'int',
		'Sale_Price' => 'float',
		'App_Fee' => 'float',
		'Transaction_Date' => 'datetime',
		'payment_status_id' => 'int',
		'psp_vendor_id' => 'int'
	];

	protected $fillable = [
		'Listing_ID',
		'Buyer_ID',
		'Payment_ID',
		'Purchase_Ref_Number',
		'Sale_Price',
		'App_Fee',
		'Transaction_Date',
		'payment_status_id',
		'psp_vendor_id'
	];

	public function listing()
	{
		return $this->belongsTo(Listing::class, 'Listing_ID');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'Buyer_ID', 'uuid');
	}

	public function payment_detail()
	{
		return $this->belongsTo(PaymentDetail::class, 'Payment_ID');
	}

	public function listings_ids_pool()
	{
		return $this->belongsTo(ListingsIdsPool::class, 'Purchase_Ref_Number');
	}

	public function payment_status()
	{
		return $this->belongsTo(PaymentStatus::class);
	}

	public function psp_vendor()
	{
		return $this->belongsTo(PspVendor::class);
	}
}
