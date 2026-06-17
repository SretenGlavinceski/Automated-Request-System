<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'service_item_id',
    'event',
    'recipient_type',
    'send_database',
    'send_email',
    'subject',
    'message',
    'is_active',
])]
class NotificationRule extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'send_database' => 'boolean',
            'send_email' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }
}
