<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        \Log::info('Dashboard access attempt', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'department_id' => $user->department_id,
            'department_name' => $user->department ? $user->department->name : 'No department'
        ]);

        if (!$user->department) {
            \Log::warning('User has no department assigned', ['user_id' => $user->id]);
            return $this->generalDashboard();
        }

        // Gebruik zowel naam als ID voor betrouwbaarheid
        $deptName = strtolower(trim($user->department->name));
        $deptId = $user->department_id;

        \Log::info('Department check', [
            'dept_id' => $deptId,
            'dept_name' => $deptName,
            'original_name' => $user->department->name
        ]);

        // Check op zowel naam als ID
        switch(true) {
            case $deptName === 'sales' || $deptId === 1:
                \Log::info('Routing to Sales Dashboard');
                return $this->salesDashboard();

            case $deptName === 'finance' || $deptId === 2:
                return $this->financeDashboard();

            case $deptName === 'maintenance' || $deptId === 3:
                return $this->maintenanceDashboard();

            case $deptName === 'purchase' || $deptId === 4:
                return $this->purchaseDashboard();

            case $deptName === 'management' || $deptId === 5:
                return $this->managementDashboard();

            case $deptName === 'customer service' || str_contains($deptName, 'customer'):
                return $this->customerServiceDashboard();

            default:
                \Log::warning('No specific dashboard found, using general', [
                    'dept_id' => $deptId,
                    'dept_name' => $deptName
                ]);
                return $this->generalDashboard();
        }
    }

    private function generalDashboard()
    {
        return view('dashboard.general', [
            'totalProducts' => Product::count(),
            'totalCustomers' => Customer::count(),
            'totalUsers' => User::count(),
            'recentProducts' => Product::with('category')->latest()->take(5)->get(),
            'recentCustomers' => Customer::latest()->take(5)->get(),
            // Voeg eventueel andere variabelen toe die in de view worden gebruikt
        ]);
    }
    private function salesDashboard()
    {
        $stats = [
            'totalProducts' => Product::count(),
            'totalCustomers' => Customer::count(),
            'recentQuotes' => Quote::with(['customer', 'products'])->latest()->take(5)->get(),
            'pendingQuotes' => Quote::where('status', 'pending')->count(),
            'approvedQuotes' => Quote::where('status', 'approved')->count(),
            'rejectedQuotes' => Quote::where('status', 'rejected')->count(),
            'totalQuotes' => Quote::count(),
            'recentCustomers' => Customer::latest()->take(5)->get(),
        ];

        // Bereken totale waarde van goedgekeurde offertes
        $stats['totalQuoteValue'] = Quote::where('status', 'approved')->sum('total_amount');

        return view('dashboard.sales', $stats);
    }

    private function financeDashboard()
    {
        $stats = [
            'totalCustomers' => Customer::where('bkr_approved', true)->count(),
            'pendingInvoices' => Invoice::where('status', 'pending')->count(),
            'paidInvoices' => Invoice::where('status', 'paid')->count(),
            'overdueInvoices' => Invoice::where('status', 'pending')
                                        ->where('due_date', '<', now())
                                        ->count(),
            'totalRevenue' => Invoice::where('status', 'paid')->sum('amount'),
            'recentInvoices' => Invoice::with('customer')->latest()->take(5)->get(),
            'bkrApproved' => Customer::where('bkr_approved', true)->count(),
            'bkrPending' => Customer::where('bkr_approved', false)->count(),
        ];

        return view('dashboard.finance', $stats);
    }

    private function maintenanceDashboard()
    {
        $stats = [
            'totalTasks' => Maintenance::count(),
            'completedTasks' => Maintenance::where('status', 'voltooid')->count(),
            'plannedTasks' => Maintenance::where('status', 'gepland')->count(),
            'inProgressTasks' => Maintenance::where('status', 'in_uitvoering')->count(),
            'overdueTasks' => Maintenance::where('status', 'gepland')
                                       ->where('scheduled_date', '<', now())
                                       ->count(),
            'recentTasks' => Maintenance::with(['customer', 'assignedTechnician'])
                                      ->latest()
                                      ->take(5)
                                      ->get(),
        ];

        return view('dashboard.maintenance', $stats);
    }

    private function purchaseDashboard()
    {
        $lowStockThreshold = 10;
        $minimumStockThreshold = 5;

        // Basis statistieken
        $totalProducts = Product::count();

        // Laag voorraad producten (onder 10 maar boven 0)
        $lowStockProducts = Product::where('stock', '<', $lowStockThreshold)
                                ->where('stock', '>', 0)
                                ->orderBy('stock', 'asc')
                                ->get();

        // Kritieke voorraad (onder 5 maar boven 0)
        $criticalStockProducts = Product::where('stock', '<', $minimumStockThreshold)
                                    ->where('stock', '>', 0)
                                    ->orderBy('stock', 'asc')
                                    ->get();

        // Uitverkocht producten
        $outOfStockProducts = Product::where('stock', 0)
                                    ->orderBy('name', 'asc')
                                    ->get();

        // Totaal aantal laag voorraad
        $lowStockCount = $lowStockProducts->count();
        $criticalStockCount = $criticalStockProducts->count();
        $outOfStockCount = $outOfStockProducts->count();

        // Bereken totale voorraad waarde
        $totalStockValue = Product::sum(DB::raw('price * stock'));

        // Recente producten
        $recentProducts = Product::latest()->take(5)->get();

        // Voorbeeld van aankomende leveringen
        $upcomingDeliveries = [];

        // Categorie verdeling
        $categoryStats = $this->getCategoryStats();

        // Meest verkochte producten (neem recente producten als placeholder)
        $topSellingProducts = $recentProducts;

        // Bestellingen in afwachting (placeholder)
        $pendingOrdersCount = 0;

        // Bereken het gemiddelde aantal producten per categorie
        $totalCategories = count(Product::CATEGORIES);
        $avgProductsPerCategory = $totalProducts > 0 ? $totalProducts / $totalCategories : 0;

        $stats = [
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'criticalStockProducts' => $criticalStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'lowStockCount' => $lowStockCount,
            'criticalStockCount' => $criticalStockCount,
            'outOfStockCount' => $outOfStockCount,
            'totalStockValue' => $totalStockValue,
            'recentProducts' => $recentProducts,
            'upcomingDeliveries' => $upcomingDeliveries,
            'categoryStats' => $categoryStats,
            'topSellingProducts' => $topSellingProducts,
            'pendingOrdersCount' => $pendingOrdersCount,
            'avgProductsPerCategory' => $avgProductsPerCategory,
            'lowStockThreshold' => $lowStockThreshold,
            'minimumStockThreshold' => $minimumStockThreshold,
            'categories' => Product::CATEGORIES,
        ];

        return view('dashboard.purchase', $stats);
    }

    // Helper method voor categorie statistieken
    private function getCategoryStats()
    {
        $stats = [];
        foreach (Product::CATEGORIES as $key => $name) {
            $count = Product::where('category', $key)->count();
            $lowStockCount = Product::where('category', $key)
                                ->where('stock', '<', 10)
                                ->where('stock', '>', 0)
                                ->count();
            $outOfStockCount = Product::where('category', $key)
                                    ->where('stock', 0)
                                    ->count();
            $criticalStockCount = Product::where('category', $key)
                                        ->where('stock', '<', 5)
                                        ->where('stock', '>', 0)
                                        ->count();

            $stats[] = [
                'key' => $key,
                'name' => $name,
                'count' => $count,
                'low_stock_count' => $lowStockCount,
                'critical_stock_count' => $criticalStockCount,
                'out_of_stock_count' => $outOfStockCount,
            ];
        }

        // Sorteer op aantal producten (hoog naar laag)
        usort($stats, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return $stats;
    }

    private function managementDashboard()
    {
        // Haal de benodigde data op
        $users = User::with('department')->get();
        $products = Product::all();
        $customers = Customer::all();

        $stats = [
            'users' => $users,
            'products' => $products,
            'customers' => $customers,
            'totalUsers' => $users->count(),
            'totalProducts' => $products->count(),
            'totalCustomers' => $customers->count(),
            'totalRevenue' => Invoice::where('status', 'paid')->sum('amount'),
            'totalQuotes' => Quote::count(),
            'quoteConversionRate' => Quote::count() > 0 ?
                (Quote::where('status', 'approved')->count() / Quote::count()) * 100 : 0,
            'recentUsers' => User::with('department')->latest()->take(5)->get(),
            'departmentStats' => $this->getDepartmentStats(),
        ];

        return view('dashboard.management', $stats);
    }

    private function customerServiceDashboard()
    {
        $stats = [
            'totalCustomers' => Customer::count(),
            'recentCustomers' => Customer::latest()->take(5)->get(),
            'bkrApproved' => Customer::where('bkr_approved', true)->count(),
            'bkrPending' => Customer::where('bkr_approved', false)->count(),
            'customersWithMaintenance' => Customer::has('maintenances')->count(),
            'recentMaintenanceRequests' => Maintenance::with('customer')
                                                    ->latest()
                                                    ->take(5)
                                                    ->get(),
        ];

        return view('dashboard.customer-service', $stats);
    }

    private function getDepartmentStats()
    {
        return [
            'sales' => [
                'users' => User::where('department_id', 1)->count(),
                'quotes' => Quote::count(),
            ],
            'finance' => [
                'users' => User::where('department_id', 2)->count(),
                'invoices' => Invoice::count(),
            ],
            'maintenance' => [
                'users' => User::where('department_id', 3)->count(),
                'tasks' => Maintenance::count(),
            ],
            'purchase' => [
                'users' => User::where('department_id', 4)->count(),
                'products' => Product::count(),
            ],
        ];
    }

    // API endpoint voor dashboard data (voor toekomstige AJAX calls)
    public function getDashboardData()
    {
        $user = auth()->user();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'department' => $user->department ? $user->department->name : 'Geen',
                'role' => $user->role,
            ],
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
