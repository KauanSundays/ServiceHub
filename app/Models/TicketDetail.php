<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketDetail extends Model
{
    protected $fillable = ['ticket_id', 'content'];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
