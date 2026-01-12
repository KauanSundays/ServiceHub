<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessTicketAttachment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Ticket $ticket) {}

    public function handle(): void
    {
        $content = "Anexo nao fornecido.";

        if ($this->ticket->attachment_path && Storage::exists($this->ticket->attachment_path)) {
            $fileContent = Storage::get($this->ticket->attachment_path);
            $content = "Conteudo: " . mb_strimwidth($fileContent, 0, 500, "...");
        }

        TicketDetail::updateOrCreate(
            ['ticket_id' => $this->ticket->id],
            ['content' => $content]
        );

        $this->ticket->update(['status' => 'processing']);
    }
}
