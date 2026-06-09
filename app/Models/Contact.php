<?php

namespace App\Models;

use App\Enums\ContactStatus;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'property_id',
        'status',
        'admin_notes',
        'replied_at',
    ];

    protected $casts = [
        'status'     => ContactStatus::class,
        'replied_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', ContactStatus::New);
    }

    public function markAsRead(): void
    {
        if ($this->status === ContactStatus::New) {
            $this->update(['status' => ContactStatus::Read]);
        }
    }

    public function markAsReplied(): void
    {
        $this->update([
            'status'     => ContactStatus::Replied,
            'replied_at' => now(),
        ]);
    }

    protected static function newFactory()
    {
        return ContactFactory::new();
    }
}
