<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier_name',
        'courier_code',
        'service_type',
        'booking_id',
        'waybill_number',
        'shipping_cost',
        'insurance_cost',
        'total_weight',
        'origin_district_id',
        'destination_district_id',
        'status',
        'pickup_scheduled_at',
        'shipped_at',
        'delivered_at',
        'kiriminaja_response',
        'tracking_history',
    ];

    protected function casts(): array
    {
        return [
            'shipping_cost' => 'decimal:2',
            'insurance_cost' => 'decimal:2',
            'total_weight' => 'integer',
            'origin_district_id' => 'integer',
            'destination_district_id' => 'integer',
            'pickup_scheduled_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'kiriminaja_response' => 'array',
            'tracking_history' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function hasWaybill(): bool
    {
        return !empty($this->waybill_number);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function getFormattedShippingCostAttribute(): string
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getFormattedWeightAttribute(): string
    {
        if ($this->total_weight >= 1000) {
            return round($this->total_weight / 1000, 2) . ' kg';
        }

        return $this->total_weight . ' gram';
    }

    public function getCourierLabelAttribute(): string
    {
        return strtoupper($this->courier_code) . ' (' . $this->service_type . ')';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_pickup' => 'Menunggu Pickup Kurir',
            'picked_up' => 'Paket Telah Dipickup',
            'in_transit' => 'Sedang Dikirim',
            'delivered' => 'Terkirim / Sampai di Tujuan',
            'returned' => 'Paket Diretur',
            'cancelled' => 'Pengiriman Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
