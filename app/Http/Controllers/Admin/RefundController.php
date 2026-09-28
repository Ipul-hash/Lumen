<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $currentStatus = $request->get('status', 'all');
        $searchQuery = trim($request->get('q', ''));

        $query = Refund::with(['order', 'items'])->latest();

        if ($currentStatus !== 'all' && in_array($currentStatus, ['pending', 'approved', 'rejected', 'refunded'])) {
            $query->where('status', $currentStatus);
        }

        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('refund_number', 'like', "%{$searchQuery}%")
                    ->orWhere('customer_name', 'like', "%{$searchQuery}%")
                    ->orWhere('customer_email', 'like', "%{$searchQuery}%")
                    ->orWhere('customer_phone', 'like', "%{$searchQuery}%")
                    ->orWhereHas('order', function ($oq) use ($searchQuery) {
                        $oq->where('order_number', 'like', "%{$searchQuery}%");
                    });
            });
        }

        $refunds = $query->paginate(15)->withQueryString();

        $stats = [
            'all' => Refund::count(),
            'pending' => Refund::where('status', 'pending')->count(),
            'approved' => Refund::where('status', 'approved')->count(),
            'refunded' => Refund::where('status', 'refunded')->count(),
            'rejected' => Refund::where('status', 'rejected')->count(),
            'total_refunded_amount' => Refund::where('status', 'refunded')->sum('approved_amount'),
        ];

        return view('admin.refunds.index', compact('refunds', 'stats', 'currentStatus', 'searchQuery'));
    }

    public function show(Refund $refund)
    {
        $refund->load([
            'order.items',
            'order.shipment',
            'order.payments',
            'items',
            'approvedByUser',
            'rejectedByUser',
            'disbursedByUser'
        ]);

        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(Request $request, Refund $refund)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan berstatus Menunggu Persetujuan yang dapat disetujui.');
        }

        $validated = $request->validate([
            'approved_amount' => 'required|numeric|min:1000|max:' . $refund->order->total_amount,
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $refund->update([
            'status' => 'approved',
            'approved_amount' => $validated['approved_amount'],
            'admin_notes' => $validated['admin_notes'] ?? $refund->admin_notes,
            'approved_at' => now(),
            'approved_by' => Auth::id() ?? 1,
        ]);

        return back()->with('success', 'Pengajuan pengembalian dana ' . $refund->refund_number . ' berhasil disetujui senilai Rp ' . number_format($validated['approved_amount'], 0, ',', '.') . '. Silakan lanjutkan ke proses transfer pencairan dana.');
    }

    public function reject(Request $request, Refund $refund)
    {
        if (!in_array($refund->status, ['pending', 'approved'])) {
            return back()->with('error', 'Pengajuan dengan status ini tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|min:5|max:1000',
        ]);

        $refund->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'rejected_at' => now(),
            'rejected_by' => Auth::id() ?? 1,
        ]);

        return back()->with('warning', 'Pengajuan pengembalian dana ' . $refund->refund_number . ' telah ditolak dengan alasan: ' . $validated['admin_notes']);
    }

    public function disburse(Request $request, Refund $refund)
    {
        if ($refund->status !== 'approved') {
            return back()->with('error', 'Pengembalian dana hanya dapat dieksekusi untuk pengajuan yang telah disetujui.');
        }

        $validated = $request->validate([
            'disbursement_method' => 'required|string|max:100',
            'disbursement_reference' => 'required|string|max:100',
            'disbursement_proof' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $proofPath = null;
            if ($request->hasFile('disbursement_proof')) {
                $proofPath = $request->file('disbursement_proof')->store('refunds/disbursements', 'public');
            }

            $refund->update([
                'status' => 'refunded',
                'disbursement_method' => $validated['disbursement_method'],
                'disbursement_reference' => $validated['disbursement_reference'],
                'disbursement_proof' => $proofPath,
                'admin_notes' => $validated['admin_notes'] ?? $refund->admin_notes,
                'disbursed_at' => now(),
                'disbursed_by' => Auth::id() ?? 1,
            ]);

            $order = $refund->order;
            if ($order) {
                $refundLog = "\n[REFUND " . now()->format('d/m/Y H:i') . "] Dana sebesar Rp " . number_format($refund->approved_amount, 0, ',', '.') . " telah dikembalikan ke rekening " . $refund->bank_name . " " . $refund->bank_account_number . " a.n " . $refund->bank_account_name . " (Reff: " . $validated['disbursement_reference'] . ")";
                $order->notes = ($order->notes ? $order->notes . "\n" : '') . $refundLog;

                if ($refund->refund_type === 'full') {
                    $order->status = 'cancelled';
                    $order->cancelled_at = now();
                }

                $order->save();

                $latestPayment = $order->latestPayment;
                if ($latestPayment) {
                    $latestPayment->status = 'refunded';
                    $latestPayment->save();
                }
            }

            DB::commit();

            return back()->with('success', 'Pengembalian dana ' . $refund->refund_number . ' telah berhasil dieksekusi dan dicatat ke sistem.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses eksekusi pengembalian dana: ' . $e->getMessage());
        }
    }
}
