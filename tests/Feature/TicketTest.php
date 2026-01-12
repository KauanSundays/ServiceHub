<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Jobs\ProcessTicketAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $company = Company::factory()->create();
        $this->project = Project::factory()->create(['company_id' => $company->id]);
    }

    public function test_usuarios_autenticados_podem_ver_tickets()
    {
        $response = $this->actingAs($this->user)->get('/tickets');
        $response->assertStatus(200);
    }

    public function test_um_novo_ticket_pode_ser_criado_e_dispara_job_de_anexo()
    {
        Storage::fake('attachments');
        Queue::fake();

        $file = UploadedFile::fake()->create('log.txt', 500);

        $response = $this->actingAs($this->user)->post('/tickets', [
            'title' => 'Erro no Servidor',
            'project_id' => $this->project->id,
            'attachment' => $file,
        ]);

        $response->assertRedirect('/tickets');

        $this->assertDatabaseHas('tickets', [
            'title' => 'Erro no Servidor'
        ]);

        Queue::assertPushed(ProcessTicketAttachment::class);
    }

    public function test_o_relacionamento_um_para_um_com_detalhes_funciona()
    {
        $ticket = Ticket::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id
        ]);

        TicketDetail::create([
            'ticket_id' => $ticket->id,
            'content' => 'Dados de teste'
        ]);

        $this->assertNotNull($ticket->fresh()->detail);
        $this->assertEquals('Dados de teste', $ticket->fresh()->detail->content);
    }
}
