<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ TAMBAHAN
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BorrowingExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class BorrowingController extends Controller
{
    /**
     * Calculate fine for late returns.
     */
    private function calculateFine($expectedReturnDate, $actualReturnDate)
    {
        $fineRatePerDay = 5000;

        if ($actualReturnDate > $expectedReturnDate) {
            $daysLate = $expectedReturnDate->diffInDays($actualReturnDate);
            return $daysLate * $fineRatePerDay;
        }

        return 0;
    }

    // ===============================
    // METHOD YG SUDAH ADA (TIDAK DIUBAH)
    // ===============================

    public function index(Request $request)
    {
        $search = $request->get('search');
        $borrowings = Borrowing::with(['user', 'item'])
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                })->orWhereHas('item', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            })->get();

        return view('admin.borrow.index', compact('borrowings'));
    }

    public function create()
    {
        return view('admin.borrow.create', [
            'users' => User::all(),
            'items' => Item::where('quantity', '>', 0)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->quantity > $item->quantity) {
            return back()->with('error', 'Stok barang tidak mencukupi');
        }

        Borrowing::create([
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'borrow_date' => now(),
            'status' => 'borrowed'
        ]);

        $item->decrement('quantity', $request->quantity);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Data peminjaman berhasil disimpan');
    }

    public function edit($id)
    {
        return view('admin.borrow.edit', [
            'borrowing' => Borrowing::findOrFail($id),
            'users' => User::all(),
            'items' => Item::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        $request->validate([
            'user_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date'
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->quantity != $borrowing->quantity) {
            $selisih = $request->quantity - $borrowing->quantity;

            if ($selisih > 0) {
                if ($selisih > $item->quantity) {
                    return back()->with('error', 'Stok barang tidak mencukupi');
                }
                $item->decrement('quantity', $selisih);
            } else {
                $item->increment('quantity', abs($selisih));
            }
        }

        $borrowing->update([
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'status' => strtolower($request->status),
        ]);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Data peminjaman berhasil diupdate');
    }

    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->item->increment('quantity', $borrowing->quantity);
        $borrowing->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    public function trash()
    {
        return view('admin.borrow.trash', [
            'borrowings' => Borrowing::onlyTrashed()->with(['user', 'item'])->get()
        ]);
    }

    public function restore($id)
    {
        Borrowing::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Data berhasil dipulihkan');
    }

    public function deletePermanent($id)
    {
        Borrowing::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Data berhasil dihapus permanen');
    }

    public function export()
    {
        return Excel::download(new BorrowingExport, 'data-peminjaman.xlsx');
    }

   //chart js
    public function chartData()
    {
        $data = Borrowing::select(
                'items.name',
                DB::raw('COUNT(borrowings.id) as total')
            )
            ->join('items', 'borrowings.item_id', '=', 'items.id')
            ->groupBy('items.name')
            ->get();

        return response()->json([
            'labels' => $data->pluck('name'),
            //ngambil nilai
            'data'   => $data->pluck('total'),
        ]);
    }

    // munculin di halaman dashboard
    public function chart()
    {
        return view('admin.borrowings.chart'); 
    }
    

    // JSON untuk chart pie (status)
public function chartPie()
{
    $returnedStatuses = ['returned', 'return', 'selesai', 'completed', 'kembali', 'dikembalikan'];
    $fineStatuses = ['denda', 'fine', 'late', 'overdue', 'penalty', 'terlambat'];

    $returned = Borrowing::where(function($q) use ($returnedStatuses) {
        foreach ($returnedStatuses as $status) {
            $q->orWhere('status', 'like', '%' . $status . '%');
        }
    })->count();

    $denda = Borrowing::where(function($q) use ($fineStatuses) {
        foreach ($fineStatuses as $status) {
            $q->orWhere('status', 'like', '%' . $status . '%');
        }
    })->count();

    $returned = (int) $returned;
    $denda = (int) $denda;

    return response()->json([
        'data' => [$returned, $denda],
        'labels' => ['Pengembalian', 'Denda']
    ]);
}

    // User-facing borrow method (from public/home page)
    public function storeUserBorrow(Request $request, $item_id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $item = Item::findOrFail($item_id);

        // Default quantity = 1 if not provided
        $quantity = $request->get('quantity', 1);

        if ($quantity > $item->quantity) {
            return back()->with('error', 'Stok barang tidak mencukupi');
        }

        Borrowing::create([
            'user_id' => $user->id,
            'item_id' => $item_id,
            'quantity' => $quantity,
            'borrow_date' => now(),
            'status' => 'borrowed'
        ]);

        $item->decrement('quantity', $quantity);

        return redirect()->route('profile')
            ->with('success', 'Peminjaman berhasil! Silakan tunggu persetujuan admin.');
    }

    // User return item method
    public function returnItem(Request $request, $borrowing_id)
    {
        $borrowing = Borrowing::with('item')->findOrFail($borrowing_id);

        // Verify user owns this borrowing
        if ($borrowing->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak berhak mengembalikan peminjaman ini');
        }

        $borrowing->update([
            'return_date' => now(),
            'status' => 'returned'
        ]);

        // Restore item quantity
        $borrowing->item->increment('quantity', $borrowing->quantity);

        return redirect()->route('profile')
            ->with('success', 'Barang berhasil dikembalikan');
    }

    // Admin return item method (update borrowing status to returned)
    public function adminReturnItem(Request $request, $id)
    {
        $borrowing = Borrowing::with('item')->findOrFail($id);

        $borrowing->update([
            'return_date' => now(),
            'status' => 'returned'
        ]);

        // Restore item quantity
        $borrowing->item->increment('quantity', $borrowing->quantity);

        return back()->with('success', 'Peminjaman berhasil dikembalikan');
    }
}
