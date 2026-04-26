<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $ID
 * @property string $uuid
 * @property string $User_Internal_ID
 * @property string $Email_Address
 * @property string $First_Name
 * @property string $Last_Name
 * @property string $password
 * @property string|null $cpassword
 * @property string|null $password_token
 * @property Carbon|null $password_expiration_time
 * @property int|null $Contact_ID
 * @property string|null $Loyalty_Program_ID
 * @property string|null $Loyalty_Account_Number
 * @property int|null $Alert_ID
 * @property string|null $Referral_LIST_ID
 * @property int|null $Event_ID
 * @property string|null $Facebook
 * @property string|null $Twitter
 * @property string|null $Instagram
 * @property string|null $service
 * @property string|null $birthday
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $profile_pic
 * @property string|null $payment_method
 * @property string|null $phone
 * @property string|null $source
 * @property string|null $ip_address
 * @property string|null $campaign
 * @property Carbon $date_time
 * @property Carbon|null $email_verified_at
 * @property bool|null $account_locked
 * 
 * @property Collection|BookingsInternalGd[] $bookings_internal_gds
 * @property Collection|EmailVerification[] $email_verifications
 * @property Collection|Lead[] $leads
 * @property Collection|Listing[] $listings
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class User extends  \Illuminate\Foundation\Auth\User
{
	protected $table = 'user';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'password_expiration_time' => 'datetime',
		'Contact_ID' => 'int',
		'Alert_ID' => 'int',
		'Event_ID' => 'int',
		'date_time' => 'datetime',
		'email_verified_at' => 'datetime',
		'account_locked' => 'bool'
	];

	protected $hidden = [
		'password',
		'cpassword',
		'password_token'
	];

	protected $fillable = [
		'uuid',
		'User_Internal_ID',
		'Email_Address',
		'First_Name',
		'Last_Name',
		'password',
		'cpassword',
		'password_token',
		'password_expiration_time',
		'Contact_ID',
		'Loyalty_Program_ID',
		'Loyalty_Account_Number',
		'Alert_ID',
		'Referral_LIST_ID',
		'Event_ID',
		'Facebook',
		'Twitter',
		'Instagram',
		'service',
		'birthday',
		'gender',
		'address',
		'profile_pic',
		'payment_method',
		'phone',
		'source',
		'ip_address',
		'campaign',
		'date_time',
		'email_verified_at',
		'account_locked'
	];

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
