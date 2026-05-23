<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    public const SYSTEM_NAMES = [
        'Abierta',
        'Cerrada',
        'Rechazada',
        'Declinada',
        'Facturada',
    ];

    protected $table = 'order_status';

    protected $fillable = [
        'name',
        'code',
        'description',
        'state',
    ];

    protected $casts = [
        'state' => 'boolean',
    ];

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'status', 'name');
    }

    public function isSystemDefault(): bool
    {
        return in_array($this->name, self::SYSTEM_NAMES, true);
    }

    public function isLockedForEditing(?int $assignedOrdersCount = null): bool
    {
        if ($this->isSystemDefault()) {
            return true;
        }

        $count = $assignedOrdersCount ?? $this->serviceOrders()->count();

        return $count > 0;
    }
}
