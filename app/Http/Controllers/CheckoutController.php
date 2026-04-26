<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\BookingTransaction;

class CheckoutController extends BaseController
{

    // Show flip hotel checkout 
    public function checkout($uuid)
    {
        $uuid = filter_var($uuid, FILTER_SANITIZE_SPECIAL_CHARS);
        DB::beginTransaction();

        try {

            /* -------------------------
               Resolve Buyer (User / Guest)
            -------------------------- */
            if (Auth::check()) {
                $buyerUuid = Auth::user()->uuid;

            } elseif (Session::has('guest_user_uuid')) {

                $buyerUuid = Session::get('guest_user_uuid');

            } else {

                // create guest only ONCE
                $uuidBinary = $this->generateUUIDbinary();

                $guestUser = User::create([
                    'User_Internal_ID' => $uuidBinary,
                    'uuid'             => $uuidBinary,
                    'First_Name'       => 'Guest_' . Str::upper(Str::random(4)),
                    'Last_Name'        => 'Guest_' . Str::upper(Str::random(4)),
                    'Email_Address'    => 'guest_' . Str::lower(Str::random(6)) . '@guest.local',
                    'password'         => Hash::make(Str::random(16)),
                    'is_guest'         => 1,
                ]);

                // 🔒 Persist guest identity
                Session::put('guest_user_uuid', $guestUser->uuid);

                $buyerUuid = $guestUser->uuid;
            }


            $transaction = BookingTransaction::firstOrCreate(
                [
                    'Listing_ID'        => 1,
                    'Buyer_ID'          => $buyerUuid,
                    'payment_status_id' => PaymentStatus::PENDING->value,
                ],
                [
                    'Purchase_Ref_Number' => 1,
                    'Sale_Price'          => 100,
                    'App_Fee'             => 20,
                    'Transaction_Date'    => now(),
                ]
            );

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        /* -------------------------
           View Data
        -------------------------- */
        $data['listingId'] = $uuid;
        $data['transaction'] = $transaction;

        return view('checkout.index', compact('data'));
    }


    public function  generateUUIDbinary()
    {

      $uuid = \Illuminate\Support\Str::uuid()->toString();

      // Convert UUID to hexadecimal (remove dashes to create a clean hex string)
      $uuidHex = str_replace('-', '', $uuid); // Remove dashes from UUID

      // Convert the hexadecimal UUID to binary
      $uuidBinary = hex2bin($uuidHex); // Convert the hex string to binary

      // Generate a unique user ID
      return $uuidBinary;
    }
}
