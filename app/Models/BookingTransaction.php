<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BookingTransaction
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
class BookingTransaction extends Model
{
	protected $table = 'booking_transactions';
	protected $primaryKey = 'Transaction_ID';
	public $timestamps = false;

	protected $casts = [
		'Listing_ID' => 'int',
		'Buyer_ID' => 'string',
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

}
