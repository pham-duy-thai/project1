<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.contract.student.user', 'student.user']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        // Search by payment code
        if ($request->filled('search')) {
            $query->where('payment_code', 'like', '%' . $request->search . '%');
        }
        
        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Initiate payment for an invoice
     */
    public function pay(Request $request, Invoice $invoice)
    {
        if ($invoice->payment_status === 'paid') {
            return back()->with('error', 'Hóa đơn đã được thanh toán.');
        }
        
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,vnpay,momo,zalopay',
            'amount' => 'required|numeric|min:1000|max:' . $invoice->remaining_amount
        ]);
        
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return back()->with('error', 'Bạn chưa được đăng ký là sinh viên.');
        }
        
        // Check if student owns this invoice
        if ($invoice->student_id !== $student->id && !$user->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền thanh toán hóa đơn này.');
        }
        
        DB::beginTransaction();
        try {
            // Generate payment code
            $paymentCode = 'PAY' . date('Ymd') . str_pad(Payment::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Create payment record
            $payment = Payment::create([
                'payment_code' => $paymentCode,
                'invoice_id' => $invoice->id,
                'student_id' => $student->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => now(),
                'status' => $validated['payment_method'] === 'cash' ? 'pending' : 'pending',
                'notes' => 'Payment initiated'
            ]);
            
            DB::commit();
            
            // If VNPay, redirect to payment gateway
            if ($validated['payment_method'] === 'vnpay') {
                $vnpayData = $this->vnpayService->createPaymentUrl([
                    'txn_ref' => $paymentCode,
                    'order_info' => 'Thanh toan hoa don ' . $invoice->invoice_number,
                    'order_type' => 'dormitory',
                    'amount' => $validated['amount'],
                    'locale' => 'vn',
                    'ip_address' => $request->ip(),
                ]);
                
                return redirect($vnpayData);
            }
            
            // For other methods, redirect to show page
            return redirect()->route('payments.show', $payment)
                ->with('success', 'Yêu cầu thanh toán đã được tạo. Vui lòng hoàn tất thanh toán.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load([
            'invoice.contract.student.user',
            'invoice.contract.room.building',
            'student.user'
        ]);
        
        return view('payments.show', compact('payment'));
    }

    /**
     * Confirm cash payment (admin only)
     */
    public function confirmCash(Request $request, Payment $payment)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền xác nhận thanh toán.');
        }
        
        if ($payment->payment_method !== 'cash') {
            return back()->with('error', 'Chỉ có thể xác nhận thanh toán bằng tiền mặt.');
        }
        
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Thanh toán đã được xử lý.');
        }
        
        DB::beginTransaction();
        try {
            $payment->update([
                'status' => 'completed',
                'transaction_id' => 'CASH' . time(),
                'received_by' => $user->id,
                'payment_date' => now(),
                'notes' => ($payment->notes ?? '') . "\nXác nhận bởi: " . $user->name . ' vào ' . now()->format('d/m/Y H:i')
            ]);
            
            // Update invoice
            $invoice = $payment->invoice;
            $invoice->paid_amount = ($invoice->paid_amount ?? 0) + $payment->amount;
            $invoice->remaining_amount = $invoice->total_amount - $invoice->paid_amount;
            
            if ($invoice->remaining_amount <= 0) {
                $invoice->status = 'paid';
            } elseif ($invoice->paid_amount > 0) {
                $invoice->status = 'partial';
            }
            
            $invoice->save();
            
            DB::commit();
            
            return back()->with('success', 'Thanh toán đã được xác nhận.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * VNPay return URL handler (user redirected here after payment)
     */
    public function vnpayReturn(Request $request)
    {
        $data = $request->all();
        
        Log::info('VNPay Return received', ['data' => $data]);
        
        // Validate signature
        if (!$this->vnpayService->validateSignature($data)) {
            Log::warning('Invalid VNPay signature', ['data' => $data]);
            return redirect()->route('dashboard')
                ->with('error', 'Chữ ký không hợp lệ. Vui lòng liên hệ quản lý.');
        }
        
        // Process response
        $result = $this->vnpayService->processResponse($data);
        $paymentCode = $result['txn_ref'];
        
        // Find payment
        $payment = Payment::where('payment_code', $paymentCode)->first();
        
        if (!$payment) {
            Log::warning('Payment not found', ['payment_code' => $paymentCode]);
            return redirect()->route('dashboard')
                ->with('error', 'Không tìm thấy giao dịch.');
        }
        
        // If already processed
        if ($payment->status === 'completed') {
            return redirect()->route('payments.show', $payment)
                ->with('info', 'Giao dịch đã được xử lý trước đó.');
        }
        
        DB::beginTransaction();
        try {
            // Update payment
            $payment->status = $result['status'] === 'completed' ? 'completed' : 'failed';
            $payment->transaction_id = $data['vnp_TransactionNo'] ?? null;
            $payment->payment_details = [
                'vnp_response_code' => $data['vnp_ResponseCode'] ?? null,
                'vnp_transaction_status' => $data['vnp_TransactionStatus'] ?? null,
                'vnp_bank_code' => $data['vnp_BankCode'] ?? null,
                'vnp_card_type' => $data['vnp_CardType'] ?? null,
                'vnp_pay_date' => $data['vnp_PayDate'] ?? null,
            ];
            $payment->notes = ($payment->notes ?? '') . "\nVNPay: " . $result['message'];
            
            if ($result['status'] === 'completed') {
                $payment->payment_date = now();
                
                // Update invoice
                $invoice = $payment->invoice;
                $invoice->paid_amount = ($invoice->paid_amount ?? 0) + $payment->amount;
                $invoice->remaining_amount = $invoice->total_amount - $invoice->paid_amount;
                
                if ($invoice->remaining_amount <= 0) {
                    $invoice->status = 'paid';
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->status = 'partial';
                }
                
                $invoice->save();
            }
            
            $payment->save();
            
            DB::commit();
            
            if ($result['status'] === 'completed') {
                return redirect()->route('payments.show', $payment)
                    ->with('success', 'Thanh toán thành công!');
            } else {
                return redirect()->route('payments.show', $payment)
                    ->with('error', 'Thanh toán thất bại: ' . ($result['message'] ?? 'Không xác định'));
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment return processing failed: ' . $e->getMessage());
            
            return redirect()->route('dashboard')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán.');
        }
    }

    /**
     * VNPay IPN (Instant Payment Notification) handler
     * Called by VNPay server to confirm payment
     */
    public function vnpayIPN(Request $request)
    {
        $data = $request->all();
        
        Log::info('VNPay IPN received', ['data' => $data]);
        
        // Validate signature
        if (!$this->vnpayService->validateSignature($data)) {
            Log::warning('Invalid VNPay IPN signature', ['data' => $data]);
            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid signature'
            ]);
        }
        
        // Process response
        $result = $this->vnpayService->processResponse($data);
        $paymentCode = $result['txn_ref'];
        
        // Find payment
        $payment = Payment::where('payment_code', $paymentCode)->first();
        
        if (!$payment) {
            Log::warning('Payment not found in IPN', ['payment_code' => $paymentCode]);
            return response()->json([
                'RspCode' => '01',
                'Message' => 'Order not found'
            ]);
        }
        
        // If already processed
        if ($payment->status === 'completed') {
            return response()->json([
                'RspCode' => '00',
                'Message' => 'Order already confirmed'
            ]);
        }
        
        DB::beginTransaction();
        try {
            // Update payment
            $payment->status = $result['status'] === 'completed' ? 'completed' : 'failed';
            $payment->transaction_id = $data['vnp_TransactionNo'] ?? null;
            $payment->payment_details = [
                'vnp_response_code' => $data['vnp_ResponseCode'] ?? null,
                'vnp_transaction_status' => $data['vnp_TransactionStatus'] ?? null,
                'vnp_bank_code' => $data['vnp_BankCode'] ?? null,
                'vnp_card_type' => $data['vnp_CardType'] ?? null,
                'vnp_pay_date' => $data['vnp_PayDate'] ?? null,
            ];
            
            if ($result['status'] === 'completed') {
                $payment->payment_date = now();
                
                // Update invoice
                $invoice = $payment->invoice;
                $invoice->paid_amount = ($invoice->paid_amount ?? 0) + $payment->amount;
                $invoice->remaining_amount = $invoice->total_amount - $invoice->paid_amount;
                
                if ($invoice->remaining_amount <= 0) {
                    $invoice->status = 'paid';
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->status = 'partial';
                }
                
                $invoice->save();
            }
            
            $payment->save();
            
            DB::commit();
            
            return response()->json([
                'RspCode' => '00',
                'Message' => 'Confirm Success'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment IPN processing failed: ' . $e->getMessage());
            
            return response()->json([
                'RspCode' => '99',
                'Message' => 'Unknown error'
            ]);
        }
    }

    /**
     * VNPay callback handler (Legacy - kept for backward compatibility)
     */
    public function vnpayCallback(Request $request)
    {
        return $this->vnpayReturn($request);
    }

    /**
     * Cancel payment
     */
    public function cancel(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Không thể hủy thanh toán đã hoàn tất.');
        }
        
        $user = Auth::user();
        
        // Check permission
        if (!$user->isAdmin()) {
            $student = $user->student;
            if (!$student || $payment->student_id !== $student->id) {
                return back()->with('error', 'Bạn không có quyền hủy thanh toán này.');
            }
        }
        
        $payment->update([
            'status' => 'cancelled',
            'notes' => ($payment->notes ?? '') . "\nHủy bởi: " . $user->name . ' vào ' . now()->format('d/m/Y H:i')
        ]);
        
        return redirect()->route('payments.index')
            ->with('success', 'Thanh toán đã được hủy.');
    }
}
