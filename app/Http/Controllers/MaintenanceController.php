<?php
namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index()
    {
        try {
            // Gebruik simplePaginate of get zonder relationships eerst
            $maintenances = Maintenance::with([
                'customer:id,company_name', // Specificeer alleen nodig kolommen
                'assignedTechnician:id,name' // Specificeer alleen nodig kolommen
            ])->get();


            $upcomingCount = Maintenance::where('status', 'gepland')
                                    ->where('scheduled_date', '>=', now())
                                    ->count();

            $overdueCount = Maintenance::where('status', 'gepland')
                                    ->where('scheduled_date', '<', now())
                                    ->count();

            $completedCount = Maintenance::where('status', 'voltooid')->count();

            return view('maintenance.index', [
                'maintenances' => $maintenances,
                'upcomingCount' => $upcomingCount,
                'overdueCount' => $overdueCount,
                'completedCount' => $completedCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading maintenances: ' . $e->getMessage());

            // Fallback: laad zonder relationships
            $maintenances = Maintenance::all();

            return view('maintenance.index', [
                'maintenances' => $maintenances,
                'upcomingCount' => 0,
                'overdueCount' => 0,
                'completedCount' => 0
            ]);
        }
    }
    public function create()
    {
        $customers = Customer::all();
        $technicians = User::whereHas('department', function($query) {
            $query->where('name', 'Maintenance');
        })->get();

        return view('maintenance.create', compact('customers', 'technicians'));
    }

    public function createForCustomer(Customer $customer)
    {
        $technicians = User::whereHas('department', function($query) {
            $query->where('name', 'Maintenance');
        })->get();

        return view('maintenance.create', compact('customer', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:periodiek,reparatie,installatie', // verwijder 'onderhoud'
            'priority' => 'required|in:laag,normaal,hoog,urgent',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
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
        $technicians = User::whereHas('department', function($query) {
            $query->where('name', 'Maintenance');
        })->get();

        return view('maintenance.edit', compact('maintenance', 'customers', 'technicians'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:periodiek,reparatie,installatie', // verwijder 'onderhoud'
            'priority' => 'required|in:laag,normaal,hoog,urgent',
            'status' => 'required|in:gepland,in_uitvoering,voltooid,geannuleerd',
            'scheduled_date' => 'required|date',
            'completed_date' => 'nullable|date|required_if:status,voltooid',
            'notes' => 'nullable|string',
            'technician_notes' => 'nullable|string',
            'costs' => 'nullable|numeric|min:0',
        ]);

        // Als status wordt gewijzigd naar voltooid, zet completed_date
        if ($validated['status'] === 'voltooid' && empty($validated['completed_date'])) {
            $validated['completed_date'] = now();
        }

        $maintenance->update($validated);

        return redirect()->route('maintenance.show', $maintenance)
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
            ->with('success', 'Onderhoudstaak voltooid!');
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
            'technician_notes' => 'required|string',
            'costs' => 'nullable|numeric|min:0'
        ]);

        $maintenance->update($validated);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Technician notities toegevoegd!');
    }
}
