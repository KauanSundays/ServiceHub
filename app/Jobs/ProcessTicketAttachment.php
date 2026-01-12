<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels; // ESTA TRAIT É A CHAVE
use Illuminate\Support\Facades\Storage;

class ProcessTicketAttachment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle(): void
{
    if (!$this->ticket->attachment_path || !Storage::exists($this->ticket->attachment_path)) {
        return;
    }

    $fileData = Storage::get($this->ticket->attachment_path);

    $utf8Data = mb_convert_encoding($fileData, 'UTF-8', 'UTF-8');

    $processedContent = "Processado em " . now() . ":\n" . $utf8Data;

    TicketDetail::updateOrCreate(
        ['ticket_id' => $this->ticket->id],
        ['content' => $processedContent]
    );

    if ($this->ticket->user) {
        $this->ticket->user->notify(new \App\Notifications\TicketProcessedNotification($this->ticket));
    }
}
}
