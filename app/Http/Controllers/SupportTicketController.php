<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignSupportTicketRequest;
use App\Http\Requests\StoreSupportTicketRequest;
use App\Http\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tickets = SupportTicket::with(['customer', 'agent'])
            ->when($user->role !== 'agent', fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return SupportTicketResource::collection($tickets);
    }

    public function store(StoreSupportTicketRequest $request)
    {
        $ticket = $request->user()->supportTickets()->create([
            ...$request->validated(),
            'status' => TicketStatus::Open,
        ]);

        return (new SupportTicketResource($ticket->load('customer', 'agent')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, SupportTicket $ticket)
    {
        abort_unless(
            $request->user()->role === 'agent' || $ticket->user_id === $request->user()->id,
            403
        );

        return new SupportTicketResource($ticket->load('customer', 'agent'));
    }

    public function assign(AssignSupportTicketRequest $request, SupportTicket $ticket)
    {
        $ticket->update([
            'assigned_to' => $request->validated('agent_id'),
            'status' => TicketStatus::InProgress,
        ]);

        return new SupportTicketResource($ticket->load('customer', 'agent'));
    }
}
