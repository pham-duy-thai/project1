<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Contract;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['student.user', 'room.building', 'contract']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by month/year
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('billing_period_start', $request->month)
                  ->whereYear('billing_period_start', $request->year);
        } elseif ($request->filled('month')) {
            $query->whereMonth('billing_period_start', $request->month);
        } elseif ($request->filled('year')) {
            $query->whereYear('billing_period_start', $request->year);
        }
        
        // Filter by building
        if ($request->filled('building_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('building_id', $request->building_id);
            });
        }
        
        // Search by student code, name, or room number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('student_code', 'like', "%{$search}%")
                       ->orWhere('full_name', 'like', "%{$search}%");
                })
                ->orWhereHas('room', function($rq) use ($search) {
                    $rq->where('room_number', 'like', "%{$search}%");
                });
            });
        }
        
        $invoices = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get buildings for filter
        $buildings = \App\Models\Building::orderBy('name')->get();
        
        return view('invoices.index', compact('invoices', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $contracts = Contract::where('status', 'active')
            ->with(['student.user', 'room.building'])
            ->get();
        
        // Get active services for selection
        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Pre-select contract if provided
        $selectedContractId = $request->get('contract_id');
        
        return view('invoices.create', compact('contracts', 'services', 'selectedContractId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // First, validate the date format
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'invoice_type' => 'required|in:rent,electricity,water,service,other',
            'billing_period_start' => 'required|date_format:d/m/Y',
            'billing_period_end' => 'required|date_format:d/m/Y',
            'due_date' => 'required|date_format:d/m/Y',
            'room_price' => 'required|numeric|min:0',
            'selected_services' => 'nullable|array',
            'selected_services.*' => 'exists:services,id',
            'electricity_usage' => 'nullable|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'water_usage' => 'nullable|numeric|min:0',
            'water_cost' => 'nullable|numeric|min:0',
            'service_cost' => 'nullable|numeric|min:0',
            'other_costs' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
        
        // Convert date format from d/m/Y to Carbon objects
        $billingStart = \Carbon\Carbon::createFromFormat('d/m/Y', $request->billing_period_start)->startOfDay();
        $billingEnd = \Carbon\Carbon::createFromFormat('d/m/Y', $request->billing_period_end)->startOfDay();
        $dueDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->due_date)->startOfDay();
        
        // Validate date logic - only check billing period
        if ($billingEnd->lt($billingStart)) {
            return back()->withErrors(['billing_period_end' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.'])->withInput();
        }
        
        $validated = $request->all();
        
        DB::beginTransaction();
        try {
            $contract = Contract::findOrFail($validated['contract_id']);
            
            // Calculate room price based on actual days in billing period
            $daysInPeriod = $billingStart->diffInDays($billingEnd) + 1; // +1 to include both start and end days
            $daysInMonth = $billingStart->daysInMonth;
            $monthlyRent = $contract->monthly_rent;
            $calculatedRoomPrice = ($monthlyRent / $daysInMonth) * $daysInPeriod;
            
            // Use calculated price or manual override
            $roomPrice = $validated['room_price'] > 0 ? $validated['room_price'] : round($calculatedRoomPrice);
            
            // Calculate service cost from selected services if provided
            $serviceCost = $validated['service_cost'] ?? 0;
            if ($request->has('selected_services') && is_array($request->selected_services)) {
                $selectedServiceIds = $request->selected_services;
                $services = \App\Models\Service::whereIn('id', $selectedServiceIds)
                    ->where('is_active', true)
                    ->get();
                
                $calculatedServiceCost = 0;
                foreach ($services as $service) {
                    if ($service->type === 'monthly') {
                        // Monthly service: calculate based on days
                        $servicePrice = ($service->price / $daysInMonth) * $daysInPeriod;
                        $calculatedServiceCost += $servicePrice;
                    } else {
                        // One-time or per-use: use full price
                        $calculatedServiceCost += $service->price;
                    }
                }
                
                // Use calculated service cost if manual input is 0 or not provided
                if ($serviceCost == 0) {
                    $serviceCost = round($calculatedServiceCost);
                }
            }
            
            // Generate invoice number
            $invoiceNumber = 'INV' . date('Ymd') . str_pad(Invoice::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Calculate total
            $totalAmount = $roomPrice 
                + ($validated['electricity_cost'] ?? 0)
                + ($validated['water_cost'] ?? 0)
                + $serviceCost
                + ($validated['other_costs'] ?? 0);
            
            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'contract_id' => $validated['contract_id'],
                'student_id' => $contract->student_id,
                'room_id' => $contract->room_id,
                'invoice_type' => $validated['invoice_type'],
                'title' => 'Hóa đơn ' . ucfirst($validated['invoice_type']) . ' - ' . $invoiceNumber,
                'billing_period_start' => $billingStart->format('Y-m-d'),
                'billing_period_end' => $billingEnd->format('Y-m-d'),
                'issue_date' => now()->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'amount' => $roomPrice,
                'electricity_cost' => $validated['electricity_cost'] ?? 0,
                'water_cost' => $validated['water_cost'] ?? 0,
                'service_cost' => $serviceCost,
                'other_cost' => $validated['other_costs'] ?? 0,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null
            ]);
            
            DB::commit();
            
            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Hóa đơn đã được tạo thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'contract.student.user',
            'contract.room.building.dormitory',
            'payments'
        ]);
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->payment_status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Không thể chỉnh sửa hóa đơn đã thanh toán.');
        }
        
        $contracts = Contract::where('status', 'active')
            ->with(['student.user', 'room.building'])
            ->get();
        
        return view('invoices.edit', compact('invoice', 'contracts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->payment_status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Không thể chỉnh sửa hóa đơn đã thanh toán.');
        }
        
        $validated = $request->validate([
            'invoice_type' => 'required|in:rent,electricity,water,service,other',
            'billing_period_start' => 'required|date',
            'billing_period_end' => 'required|date|after_or_equal:billing_period_start',
            'due_date' => 'required|date|after_or_equal:billing_period_end',
            'room_price' => 'required|numeric|min:0',
            'electricity_usage' => 'nullable|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'water_usage' => 'nullable|numeric|min:0',
            'water_cost' => 'nullable|numeric|min:0',
            'service_cost' => 'nullable|numeric|min:0',
            'other_costs' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
        
        // Calculate new total
        $totalAmount = $validated['room_price'] 
            + ($validated['electricity_cost'] ?? 0)
            + ($validated['water_cost'] ?? 0)
            + ($validated['service_cost'] ?? 0)
            + ($validated['other_costs'] ?? 0);
        
        $validated['total_amount'] = $totalAmount;
        $validated['remaining_amount'] = $totalAmount - $invoice->paid_amount;
        
        // Update payment status
        if ($validated['remaining_amount'] <= 0) {
            $validated['payment_status'] = 'paid';
        } elseif ($invoice->paid_amount > 0) {
            $validated['payment_status'] = 'partially_paid';
        } else {
            $validated['payment_status'] = 'unpaid';
        }
        
        $invoice->update($validated);
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Hóa đơn đã được cập nhật.');
    }

    /**
     * Get invoices for current student
     */
    public function myInvoices(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Bạn chưa được đăng ký là sinh viên.');
        }
        
        $query = Invoice::where('student_id', $student->id)
            ->with(['contract.room.building']);
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        $invoices = $query->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Statistics
        $stats = [
            'total_unpaid' => Invoice::where('student_id', $student->id)
                ->whereIn('payment_status', ['unpaid', 'partially_paid'])
                ->sum('remaining_amount'),
            'overdue_count' => Invoice::where('student_id', $student->id)
                ->where('payment_status', '!=', 'paid')
                ->where('due_date', '<', now())
                ->count(),
            'this_month' => Invoice::where('student_id', $student->id)
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount')
        ];
        
        return view('invoices.my-invoices', compact('invoices', 'stats'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->payment_status !== 'unpaid') {
            return redirect()->route('invoices.index')
                ->with('error', 'Chỉ có thể xóa hóa đơn chưa thanh toán.');
        }
        
        // Check if has payments
        if ($invoice->payments()->exists()) {
            return redirect()->route('invoices.index')
                ->with('error', 'Không thể xóa hóa đơn đã có thanh toán.');
        }
        
        $invoice->delete();
        
        return redirect()->route('invoices.index')
            ->with('success', 'Hóa đơn đã được xóa.');
    }
}
