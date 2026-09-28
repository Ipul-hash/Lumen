<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_number',
        'order_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'refund_type',
        'reason',
        'reason_detail',
        'refund_amount',
        'approved_amount',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'proof_image',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'disbursed_at',
        'disbursed_by',
        'disbursement_method',
        'disbursement_reference',
        'disbursement_proof',
    ];

    protected function casts(): array
    {
        return [
            'refund_amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'disbursed_at' => 'datetime',
        ];
    }

    public static function generateRefundNumber(): string
    {
        return 'RFD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RefundItem::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function disbursedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function getFormattedRefundAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->refund_amount, 0, ',', '.');
    }

    public function getFormattedApprovedAmountAttribute(): string
    {
        if ($this->approved_amount === null) {
            return '-';
        }
        return 'Rp ' . number_format($this->approved_amount, 0, ',', '.');
    }

    public function getRefundTypeLabelAttribute(): string
    {
        return match ($this->refund_type) {
            'full' => 'Pengembalian Penuh (Full Refund)',
            'partial' => 'Pengembalian Sebagian (Partial)',
            'return_and_refund' => 'Retur Barang & Pengembalian Dana',
            default => ucfirst($this->refund_type),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'refunded' => 'Dana Dikembalikan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-light-warning text-warning',
            'approved' => 'badge-light-primary text-primary',
            'rejected' => 'badge-light-danger text-danger',
            'refunded' => 'badge-light-success text-success',
            default => 'badge-light-secondary text-secondary',
        };
    }
}
