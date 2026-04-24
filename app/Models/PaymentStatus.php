<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentStatus
 * 
 * @property int $id
 * @property string $code
 * @property string $label
 * @property string|null $description
 * @property bool|null $is_terminal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TransactionBookingResale[] $transaction_booking_resales
 *
 * @package App\Models
 */
class PaymentStatus extends Model
{
	protected $table = 'payment_statuses';

	protected $casts = [
		'is_terminal' => 'bool'
	];

	protected $fillable = [
		'code',
		'label',
		'description',
		'is_terminal'
	];

	public function transaction_booking_resales()
	{
		return $this->hasMany(TransactionBookingResale::class);
	}
}
