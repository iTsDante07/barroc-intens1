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

        $totalCount = Maintenance::count();
        $completedCount = Maintenance::where('status', 'voltooid')->count();
        $upcomingCount = Maintenance::where('status', 'gepland')
            ->where('scheduled_date', '>', now())->count();
        $overdueCount = Maintenance::where('status', 'gepland')
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

    public function complete(Maintenance $maintenance)
    {
        $maintenance->update([
            'status' => 'voltooid',
            'completed_date' => now()
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Onderhoudstaak gemarkeerd als voltooid!');
    }

    public function calendar()
    {
        $user = auth()->user();

        if (!$user->isMaintenance() && !$user->isAdmin()) {
            abort(403, 'Alleen monteurs en admins hebben toegang tot de planning.');
        }

        $maintenances = Maintenance::with(['assignedTechnician', 'customer'])
            ->whereNotNull('scheduled_date')
            ->get();

        $events = $maintenances->map(fn($maintenance) => [
            'id' => $maintenance->id,
            'title' => $maintenance->title,
            'start' => optional($maintenance->scheduled_date)->toDateString(),
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
