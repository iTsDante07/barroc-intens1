<?php
namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with(['customer', 'assignedTechnician'])->get();
        return view('maintenance.index', compact('maintenances'));
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
