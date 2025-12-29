<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case CANCELLED = 'CANCELLED';
    case PAID = 'PAID';
    case PARTIALLY_PAID = 'PARTIALLY_PAID';
    case PAYMENT_PLAN_PENDING = 'PAYMENT_PLAN_PENDING';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::CANCELLED => 'Cancelled',
            self::PAID => 'Paid',
            self::PARTIALLY_PAID => 'Partially Paid',
            self::PAYMENT_PLAN_PENDING => 'Payment Plan Pending',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::CANCELLED => 'red',
            self::PAID => 'green',
            self::PARTIALLY_PAID => 'orange',
            self::PAYMENT_PLAN_PENDING => 'blue',
        };
    }
}
