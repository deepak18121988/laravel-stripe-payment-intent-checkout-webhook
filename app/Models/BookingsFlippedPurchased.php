<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BookingsFlippedPurchased
 * 
 * @property int $Id
 * @property int $Booking_Details_ID
 * @property string $User_ID
 * @property int|null $Seller_ID
 * @property string $Purchase_Ref_Number
 * @property float $Sale_Price
 * @property float|null $App_Fee
 * @property Carbon|null $Transaction_Date
 * @property Carbon|null $Created_At
 * @property Carbon|null $Updated_At
 * @property int $bookings_Status_Code
 * 
 * @property BookingDetail $booking_detail
 * @property BookingStatus $booking_status
 *
 * @package App\Models
 */
class BookingsFlippedPurchased extends Model
{
	protected $table = 'bookings_flipped_purchased';
	protected $primaryKey = 'Id';
	public $timestamps = false;

	protected $casts = [
		'Booking_Details_ID' => 'int',
		'User_ID' => 'binary',
		'Seller_ID' => 'int',
		'Sale_Price' => 'float',
		'App_Fee' => 'float',
		'Transaction_Date' => 'datetime',
		'Created_At' => 'datetime',
		'Updated_At' => 'datetime',
		'bookings_Status_Code' => 'int'
	];

	protected $fillable = [
		'Booking_Details_ID',
		'User_ID',
		'Seller_ID',
		'Purchase_Ref_Number',
		'Sale_Price',
		'App_Fee',
		'Transaction_Date',
		'Created_At',
		'Updated_At',
		'bookings_Status_Code'
	];

	public function booking_detail()
	{
		return $this->belongsTo(BookingDetail::class, 'Booking_Details_ID');
	}

	public function booking_status()
	{
		return $this->belongsTo(BookingStatus::class, 'bookings_Status_Code');
	}
}
