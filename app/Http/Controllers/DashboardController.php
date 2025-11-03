<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Als gebruiker geen department heeft, toon algemeen dashboard
        if (!$user->department) {
            return $this->generalDashboard();
        }

        // Toon dashboard op basis van afdeling
        switch($user->department->name) {
            case 'Sales':
                return $this->salesDashboard();
            case 'Finance':
                return $this->financeDashboard();
            case 'Maintenance':
                return $this->maintenanceDashboard();
            case 'Purchase':
                return $this->purchaseDashboard();
            case 'Management':
                return $this->managementDashboard();
            case 'Customer Service':
                return $this->customerServiceDashboard();
            default:
                return $this->generalDashboard();
        }
    }


    private function salesDashboard()
    {
        $products = \App\Models\Product::all();
        $customers = \App\Models\Customer::all();
        $recentQuotes = \App\Models\Quote::with('customer')->latest()->take(5)->get();

        return view('dashboard.sales', compact('products', 'customers', 'recentQuotes'));
    }

    private function financeDashboard()
    {
        $customers = \App\Models\Customer::where('bkr_approved', true)->get();
        $pendingInvoices = \App\Models\Invoice::where('status', 'pending')->get();

        return view('dashboard.finance', compact('customers', 'pendingInvoices'));
    }

    private function maintenanceDashboard()
    {
        $maintenanceTasks = []; // Later vullen met echte data
        return view('dashboard.maintenance', compact('maintenanceTasks'));
    }

    private function purchaseDashboard()
    {
        $lowStockProducts = \App\Models\Product::where('stock', '<', 5)->get();
        return view('dashboard.purchase', compact('lowStockProducts'));
    }
    }
