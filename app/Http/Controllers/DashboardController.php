<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->department) {
            return $this->generalDashboard();
        }

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

    private function generalDashboard()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $recentProducts = Product::latest()->take(5)->get();

        return view('dashboard.general', compact('totalProducts', 'totalCustomers', 'recentProducts'));
    }

    private function salesDashboard()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $recentQuotes = Quote::with('customer')->latest()->take(5)->get();
        $pendingQuotes = Quote::where('status', 'pending')->count();
        $approvedQuotes = Quote::where('status', 'approved')->count();

        return view('dashboard.sales', compact(
            'totalProducts',
            'totalCustomers',
            'recentQuotes',
            'pendingQuotes',
            'approvedQuotes'
        ));
    }

    private function financeDashboard()
    {
        $totalCustomers = Customer::where('bkr_approved', true)->count();
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');
        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();

        return view('dashboard.finance', compact(
            'totalCustomers',
            'pendingInvoices',
            'paidInvoices',
            'totalRevenue',
            'recentInvoices'
        ));
    }

    private function maintenanceDashboard()
    {
        $totalTasks = Maintenance::count();
        $completedTasks = Maintenance::where('status', 'voltooid')->count();
        $plannedTasks = Maintenance::where('status', 'gepland')->count();
        $overdueTasks = Maintenance::where('status', 'gepland')
            ->where('scheduled_date', '<', now())->count();
        $recentTasks = Maintenance::with(['customer', 'assignedTechnician'])->latest()->take(5)->get();

        return view('dashboard.maintenance', compact(
            'totalTasks',
            'completedTasks',
            'plannedTasks',
            'overdueTasks',
            'recentTasks'
        ));
    }

    private function purchaseDashboard()
    {
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<', 5)->get();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $totalStockValue = Product::sum(\DB::raw('price * stock'));

        return view('dashboard.purchase', compact(
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalStockValue'
        ));
    }

    private function managementDashboard()
    {
        $totalUsers = User::count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');
        $recentActivities = []; // Hier kun je later activity logs toevoegen

        return view('dashboard.management', compact(
            'totalUsers',
            'totalCustomers',
            'totalProducts',
            'totalRevenue',
            'recentActivities'
        ));
    }

    private function customerServiceDashboard()
    {
        $totalCustomers = Customer::count();
        $recentCustomers = Customer::latest()->take(5)->get();
        $bkrApproved = Customer::where('bkr_approved', true)->count();
        $bkrPending = Customer::where('bkr_approved', false)->count();

        return view('dashboard.customer-service', compact(
            'totalCustomers',
            'recentCustomers',
            'bkrApproved',
            'bkrPending'
        ));
    }
}
