<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'description', 'priority', 'status', 'assigned_to'];

    protected function casts(): array
    {
        return [
            'priority' => TicketPriority::class,
            'status' => TicketStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
