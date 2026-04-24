<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PspVendor
 * 
 * @property int $id
 * @property int $vendor_code
 * @property string $name
 * @property string|null $description
 * @property bool|null $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TransactionBookingResale[] $transaction_booking_resales
 *
 * @package App\Models
 */
class PspVendor extends Model
{
	protected $table = 'psp_vendors';

	protected $casts = [
		'vendor_code' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'vendor_code',
		'name',
		'description',
		'is_active'
	];

	public function transaction_booking_resales()
	{
		return $this->hasMany(TransactionBookingResale::class);
	}
}
