<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_id',
        'payment_type',
        'bank',
        'va_number',
        'qr_string',
        'amount',
        'fee',
        'status',
        'expiry_time',
        'paid_at',
        'payload_response',
        'webhook_payload',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'expiry_time' => 'datetime',
            'paid_at' => 'datetime',
            'payload_response' => 'array',
            'webhook_payload' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isSettled(): bool
    {
        return $this->status === 'settlement';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || ($this->expiry_time && $this->expiry_time->isPast());
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedFeeAttribute(): string
    {
        return 'Rp ' . number_format($this->fee, 0, ',', '.');
    }

    public function getMethodNameAttribute(): string
    {
        if ($this->payment_type === 'qris') {
            return 'QRIS (Gopay/OVO/Dana/ShopeePay/BCA/dll)';
        }

        if ($this->payment_type === 'virtual_account') {
            return 'Virtual Account ' . strtoupper($this->bank ?? 'Bank');
        }

        return ucfirst($this->payment_type);
    }
}
