<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\ItemExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Schedule;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $items = Item::with('category')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', '%' . $search . '%')
                             ->orWhereHas('category', function ($q) use ($search) {
                                 $q->where('category_name', 'LIKE', '%' . $search . '%');
                             });
            })->get();
        return view('admin.item.index', compact('items'));
    }

    public function home() {
        // where() -> untuk mencari data. format yang digunakan where ('field', 'operator', 'value')
        // get() -> mengambil semua data hasil filter
        // first() -> mengambil 1 data pertama hasil filter
        // paginate() -> membagi data menjadi beberapa halaman
        // orderBy() -> untuk mengrutkan data. formatnya orderby ('field', 'type/asc|desc')
        // type ASC -> untuk mengurutkan dari A-Z, atau 0-9 atau dari data lama ke baru
        // type DESC -> untuk mengurutkan dari Z-A, atau 9-0 atau dari data baru ke lama
        // limit() -> mengambil data dengan jumlah tertentu formatnya limit(angka)
        if (auth()->check()) {
            $items = Item::where('actived', 1)->orderBy('created_at', 'desc')->limit(4)->get();
        } else {
            $items = collect(); // Empty collection if not logged in
        }
        $categories = Category::withCount(['items' => function($q) {
            $q->where('actived', 1);
        }])->having('items_count', '>', 0)->get();
        return view('home', compact('items', 'categories'));
    }

    public function itemsByCategory($category_id) {
        $items = Item::where('category_id', $category_id)->where('actived', 1)->orderBy('created_at', 'desc')->get();
        return view('items', compact('items'));
    }

    public function homeMovies(Request $request) {
        //pengambilan data dari input form search
        //name inputnya name="search_item
        $nameItem = $request->search_item;
        //jika namaItem (input search diisi, tidak kosong)
        if ($nameItem != ""){
            //LIKE : mencari data yang mirip /mengandung teks yang diminta
            //% depan : mencari kata belakang, % belakang : mencari kata depan, % depan belakang mencari dri kata  depan belakang
            $items = Item::where('title', 'LIKE', '%' . $nameItem . '%')
                ->where('actived', 1)
                ->orderBy('created_at', 'DESC')
                ->get();
        }else {
             $items = Item::where('actived', 1)->orderBy('created_at', 'desc')->get();
        }
        return view('items', compact('items'));
    }

    public function itemSchedule($item_id, Request $request)
    {
        //mengambil ? bisa dengan Request $request
        $sortirHarga = $request->sortirHarga;

        if ($sortirHarga ) {
            // with ['namarelasi' => function($q) {...}]) : melakukan filter di relasi
            $item = Item::where('id', $item_id)->with(['schedules' => function ($q)
            use ($sortirHarga) {
                //$q mewakilkan query yang artinya model schedule
                // karana$sortirHarga ada diluar function($q) jdi import pake use()
                $q->orderBy('price', $sortirHarga);
            }, 'schedules.cinema'])->first();
        } else {
            $item = Item::where('id', $item_id)->with(['schedules', 'schedules.cinema'])->first();
        }
        // karena cinema relasi adanya di schedules, jd with nya schedules.cinema (pake titik)
        // ambil satu item : first()
        return view('schedule.detail-item', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama barang wajib diisi',
            'name.min' => 'Nama barang harus diisi minimal 3 karakter',
            'description.required' => 'Keterangan wajib diisi',
            'description.min' => 'Keterangan harus diisi minimal 10 karakter',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'quantity.required' => 'Jumlah barang wajib diisi',
            'quantity.integer' => 'Jumlah barang harus berupa angka',
            'quantity.min' => 'Jumlah barang minimal 1',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
        }

        $createData = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'quantity' => $request->quantity,
            'photo' => $path,
        ]);

        if ($createData) {
            return redirect()->route('admin.items.index')->with('success', 'Berhasil menambahkan data item baru!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data item, coba lagi!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function deactivate($id)
    {
        Item::where('id', $id)->update([
            'actived' => 0
        ]);
        return redirect()->route('admin.items.index')->with('success', 'Item berhasil dinonaktifkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Item::find($id);
        return view('admin.item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama barang wajib diisi',
            'name.min' => 'Nama barang harus diisi minimal 3 karakter',
            'description.required' => 'Keterangan wajib diisi',
            'description.min' => 'Keterangan harus diisi minimal 10 karakter',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'quantity.required' => 'Jumlah barang wajib diisi',
            'quantity.integer' => 'Jumlah barang harus berupa angka',
            'quantity.min' => 'Jumlah barang minimal 1',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $item = Item::find($id);
        $path = $item->photo; // Keep existing photo if no new one uploaded

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($item->photo && file_exists(storage_path('app/public/' . $item->photo))) {
                unlink(storage_path('app/public/' . $item->photo));
            }
            $path = $request->file('photo')->store('photos', 'public');
        }

        $updateData = Item::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'quantity' => $request->quantity,
            'photo' => $path,
        ]);

        if ($updateData) {
            return redirect()->route('admin.items.index')->with('success', 'Berhasil mengedit data item!');
        } else {
            return redirect()->back()->with('error', 'Gagal mengedit data item, coba lagi!');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if ($item) {
            $item->delete(); // Soft delete
            return redirect()->route('admin.items.index')->with('success', 'Data item berhasil dihapus');
        }
        return redirect()->route('admin.items.index')->with('error', 'Item tidak ditemukan');
    }

    public function trash()
    {
       $items = Item::onlyTrashed()->get();
       return view('admin.item.trash', compact('items'));
    }

    public function restore($id)
    {
        $item = Item::onlyTrashed()->find($id);
        $item->restore();
        return redirect()->route('admin.items.index')->with('success','Berhasil mengembalikan data item!');
    }

    public function deletePermanent($id)
    {
        $item = Item::onlyTrashed()->find($id); // hanya cari yang sudah dihapus (soft delete)
        if ($item) {
            // Hapus file foto jika ada
            if ($item->photo && file_exists(storage_path('app/public/' . $item->photo))) {
                unlink(storage_path('app/public/' . $item->photo));
            }
            $item->forceDelete();
            return redirect()->back()->with('success','Berhasil menghapus data item seutuhnya!');
        } else {
            return redirect()->back()->with('error','Data item tidak ditemukan!');
        }
    }

    public function exportExcel()
     {
        $fileName = 'data-item.xlsx';
        // memproses donwload
        return Excel::download(new ItemExport, $fileName);
     }

     public function chart()
    {
        $categories = Category::withCount(['items' => function ($q) {
            $q->where('actived', 1);
        }])->get();

        $categoryNames = $categories->pluck('category_name');
        $itemCounts = $categories->pluck('items_count');

        return view('admin.item.chart', compact('categoryNames', 'itemCounts'));
    }




}

