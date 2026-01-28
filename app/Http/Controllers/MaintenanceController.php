<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status');
        $typeFilter = $request->get('type');
        $priorityFilter = $request->get('priority');
        $dateFilter = $request->get('date');

        $query = Maintenance::query();

        // Als de gebruiker een monteur is, laat alleen zijn/haar eigen taken zien
        if (auth()->user()->isMaintenance() && !auth()->user()->isAdmin()) {
            $query->where('assigned_to', auth()->id());
        }

        if ($statusFilter === 'gepland') {
            $query->where('status', 'gepland')
                ->where('scheduled_date', '>', now());
        } elseif ($statusFilter === 'overdue') {
            $query->where('status', 'gepland')
                ->where('scheduled_date', '<', now());
        } elseif ($statusFilter === 'voltooid') {
            $query->where('status', 'voltooid');
        } elseif ($statusFilter === 'in_uitvoering') {
            $query->where('status', 'in_uitvoering');
        } elseif ($statusFilter === 'geannuleerd') {
            $query->where('status', 'geannuleerd');
        }

        if ($typeFilter && in_array($typeFilter, ['periodiek', 'reparatie', 'installatie'])) {
            $query->where('type', $typeFilter);
        }

        if ($priorityFilter && in_array($priorityFilter, ['laag', 'normaal', 'hoog', 'urgent'])) {
            $query->where('priority', $priorityFilter);
        }

        // Date filter
        if ($dateFilter === 'vandaag') {
            $query->whereDate('scheduled_date', today());
        } elseif ($dateFilter === 'deze_week') {
            $query->whereBetween('scheduled_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($dateFilter === 'deze_maand') {
            $query->whereBetween('scheduled_date', [now()->startOfMonth(), now()->endOfMonth()]);
        } elseif ($dateFilter === 'aankomende_week') {
            $query->whereBetween('scheduled_date', [now()->addWeek()->startOfWeek(), now()->addWeek()->endOfWeek()]);
        }

        $maintenances = $query->orderBy('scheduled_date', 'desc')->get();

        // Statistieken ook filteren op basis van gebruiker
        $statsQuery = Maintenance::query();
        if (auth()->user()->isMaintenance() && !auth()->user()->isAdmin()) {
            $statsQuery->where('assigned_to', auth()->id());
        }

        $totalCount = (clone $statsQuery)->count();
        $completedCount = (clone $statsQuery)->where('status', 'voltooid')->count();
        $upcomingCount = (clone $statsQuery)->where('status', 'gepland')
            ->where('scheduled_date', '>', now())->count();
        $overdueCount = (clone $statsQuery)->where('status', 'gepland')
            ->where('scheduled_date', '<', now())->count();

        return view('maintenance.index', compact(
            'maintenances',
            'statusFilter',
            'typeFilter',
            'priorityFilter',
            'dateFilter',
            'totalCount',
            'completedCount',
            'upcomingCount',
            'overdueCount'
        ));
    }

    public function create()
    {
        $customers = Customer::all();
        $technicians = User::where(
            'department_id',
            \App\Models\Department::where('name', 'Maintenance')->first()->id
        )->get();

        return view('maintenance.create', compact('customers', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:periodiek,reparatie,installatie',
            'priority' => 'required|in:laag,normaal,hoog,urgent',
            'scheduled_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        Maintenance::create($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Onderhoudstaak succesvol aangemaakt!');
    }

    public function show(Maintenance $maintenance)
    {
        $maintenance->load(['customer', 'assignedTechnician']);
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        $customers = Customer::all();
        $technicians = User::where(
            'department_id',
            \App\Models\Department::where('name', 'Maintenance')->first()->id
        )->get();

        return view('maintenance.edit', compact('maintenance', 'customers', 'technicians'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:periodiek,reparatie,installatie',
            'priority' => 'required|in:laag,normaal,hoog,urgent',
            'status' => 'required|in:gepland,in_uitvoering,voltooid,geannuleerd',
            'scheduled_date' => 'required|date',
            'completed_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $maintenance->update($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Onderhoudstaak succesvol bijgewerkt!');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Onderhoudstaak succesvol verwijderd!');
    }

    public function start(Maintenance $maintenance)
    {
        $maintenance->update([
            'status' => 'in_uitvoering'
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Onderhoudstaak gestart!');
    }

    public function complete(Maintenance $maintenance)
    {
        $maintenance->update([
            'status' => 'voltooid',
            'completed_date' => now()
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Onderhoudstaak gemarkeerd als voltooid!');
    }

    public function cancel(Maintenance $maintenance)
    {
        $maintenance->update([
            'status' => 'geannuleerd'
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Onderhoudstaak geannuleerd!');
    }

    public function addTechnicianNotes(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'technician_notes' => 'required|string'
        ]);

        $maintenance->update([
            'technician_notes' => $validated['technician_notes']
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Monteur notities toegevoegd!');
    }

    public function calendar()
    {
        $user = auth()->user();

        if (!$user->isMaintenance() && !$user->isAdmin()) {
            abort(403, 'Alleen monteurs en admins hebben toegang tot de planning.');
        }

        $query = Maintenance::with(['assignedTechnician', 'customer'])
            ->whereNotNull('scheduled_date');

        // Als de gebruiker een monteur is (en geen admin), laat alleen zijn/haar eigen taken zien
        if ($user->isMaintenance() && !$user->isAdmin()) {
            $query->where('assigned_to', $user->id);
        }

        $maintenances = $query->get();

        $events = $maintenances->map(fn($maintenance) => [
            'id' => $maintenance->id,
            'title' => $maintenance->title,
            'start' => optional($maintenance->scheduled_date)->toIso8601String(),
            'backgroundColor' => $this->colorByPriority($maintenance->priority),
            'borderColor' => $this->colorByPriority($maintenance->priority),
            'extendedProps' => [
                'technician' => optional($maintenance->assignedTechnician)->name ?? 'Onbekend',
                'customer' => optional($maintenance->customer)->company_name ?? 'Onbekend',
                'status' => $maintenance->status,
            ],
        ])->filter(fn($event) => $event['start'])->values();

        return view('maintenance.calendar', [
            'events' => $events,
        ]);
    }

    private function colorByPriority(?string $priority): string
    {
        return match ($priority) {
            'hoog' => '#f97316',
            'urgent' => '#dc2626',
            'laag' => '#16a34a',
            default => '#eab308',
        };
    }

    public function createForCustomer(Customer $customer)
    {
        $technicians = User::where(
            'department_id',
            \App\Models\Department::where('name', 'Maintenance')->first()->id
        )->get();

        return view('maintenance.create', compact('customer', 'technicians'));
    }
}
