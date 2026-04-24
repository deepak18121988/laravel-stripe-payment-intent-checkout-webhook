<?php
namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING   = 1;
    case COMPLETED = 2;
    case FAILED    = 3;
    case REFUNDED  = 4;
    case DISPUTED  = 5;
}