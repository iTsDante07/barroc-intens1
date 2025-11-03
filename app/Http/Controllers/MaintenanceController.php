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
<<<<<<< Updated upstream
        $maintenances = Maintenance::with(['customer', 'assignedTechnician'])->get();
        return view('maintenance.index', compact('maintenances'));
=======
        try {
            // Begin met de base query
            $query = Maintenance::query();

            // Filter toepassen als er een filter parameter is
            if ($request->has('filter')) {
                switch ($request->filter) {
                    case 'gepland':
                        $query->where('status', 'gepland')
                            ->where('scheduled_date', '>=', now());
                        break;

                    case 'in_uitvoering':
                        $query->where('status', 'in_uitvoering');
                        break;

                    case 'voltooid':
                        $query->where('status', 'voltooid');
                        break;

                    case 'overdue':
                        $query->where('status', 'gepland')
                            ->where('scheduled_date', '<', now());
                        break;

                    // 'alle' geval heeft geen extra where clause nodig
                }
            }

            // Haal de gefilterde maintenances op met relationships
            $maintenances = $query->with(['customer', 'assignedTechnician'])->get();

            // Tel de statistieken (altijd van ALLE records, niet gefilterd)
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
            \Log::error('Maintenance index error: ' . $e->getMessage());

            return view('maintenance.index', [
                'maintenances' => collect(),
                'upcomingCount' => 0,
                'overdueCount' => 0,
                'completedCount' => 0
            ]);
        }
>>>>>>> Stashed changes
    }

    public function create()
    {
        $customers = Customer::all();
        $technicians = User::where('department_id',
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
        $technicians = User::where('department_id',
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
    public function createForCustomer(Customer $customer)
    {
        $technicians = User::where('department_id',
            \App\Models\Department::where('name', 'Maintenance')->first()->id
        )->get();

        return view('maintenance.create', compact('customer', 'technicians'));
    }
}
