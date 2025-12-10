<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{
    //FORM REGISTER
    public function registerForm()
    {
        return view('auth.signup');
    }

      public function register(Request $request)
{
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email:dns',
        'password' => 'required'
    ], [
        'name.required' => 'Nama belakang wajib diisi',
        'name.min' => 'Nama belakang diisi minimal 3 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Email diisi dengan data valid',
        'password.required' => 'Password wajib diisi'
    ]);

    $createData = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user'
    ]);

    if ($createData) {
        return redirect()->route('login')->with('success', 'Berhasil membuat akun. Silahkan login');
    } else {
        return redirect()->back()->with('error', 'Gagal silahkan coba lagi.');
    }
}

    //FORM LOGIN (show)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    //PROSES LOGIN (renamed to match route)
public function login(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required'
    ], [
        'email.required' => 'Email harus diisi',
        'password.required' => 'Password harus diisi'
    ]);

    $data = $request->only(['email', 'password']);
    if (Auth::attempt($data)) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil login !');
        } else {
            return redirect()->route('home')->with('success', 'Berhasil login !');
        }
    } else {
        return redirect()->back()->with('error', 'Gagal! silakan coba lagi');
    }
}

public function logout()
{
    Auth::logout();
    return redirect()->route('home')->with('logout', 'Anda sudah logout ! silahkan login kembali untuk akses lengkap');
}


public function index(Request $request)
{
    $search = $request->get('search');
    $users = User::whereIn('role',['admin', 'staff', 'user'])
        ->when($search, function ($query) use ($search) {
            return $query->where('name', 'LIKE', '%' . $search . '%')
                         ->orWhere('email', 'LIKE', '%' . $search . '%');
        })->get();
    return view('admin.user.index', compact('users'));
}

public function create()
{
    return view('admin.user.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email:dns',
        'password' => 'required'
    ], [
        'name.required' => 'Nama harus diisi',
        'name.min' => 'Nama harus diisi minimal 3 karakter',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Email diisi dengan data valid',
        'password.required' => 'Password wajib diisi'
    ]);

    $createData = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'staff'
    ]);

    if ($createData) {
        return redirect()->route('admin.users.index')->with('success', 'Berhasil menambahkan data!');
    } else {
        return redirect()->back()->with('error', 'Gagal! silakan coba lagi');
    }
}

public function show(string $id)
{
    //
}

public function edit(string $id)
{
    $user = User::find($id);
    return view('admin.user.edit', compact('user'));
}

public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email:dns'
    ], [
        'name.required' => 'Nama harus diisi',
        'name.min' => 'Nama harus diisi minimal 3 karakter',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Email diisi dengan data valid'
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $updateData = User::where('id', $id)->update($data);

    if ($updateData) {
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengubah data!');
    } else {
        return redirect()->back()->with('error', 'Gagal! silakan coba lagi');
    }
}

public function destroy(string $id)
{
    User::where('id', $id)->delete();
    return redirect()->route('admin.users.index')->with('success', 'Berhasil menghapus data!');
}

public function trash()
{
    $users = User::onlyTrashed()->whereIn('role', ['admin', 'staff', 'user'])->get();
    return view('admin.user.trash', compact('users'));
}

public function restore($id)
{
    $user = User::onlyTrashed()->find($id);
    if ($user) {
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    } else {
        return redirect()->back()->with('error', 'Data tidak ditemukan!');
    }
}

public function deletePermanent($id)
{
    $user = User::onlyTrashed()->find($id);
    if ($user) {
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data seutuhnya!');
    } else {
        return redirect()->back()->with('error', 'Data tidak ditemukan!');
    }
}

public function exportExcel()
    {
        //nama file yg akan terunduh
        $fileName = 'data_user.csv';
        //proses download:
        return Excel::download(new UserExport, $fileName);
    }

    public function profile(Request $request)
    {
        $search = $request->get('search');
        $borrowings = auth()->user()->borrowings()
            ->with('item')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('item', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                })->orWhere('status', 'LIKE', '%' . $search . '%');
            })
            ->get();
        return view('profile', compact('borrowings'));
    }

    public function chart()
    {
        $userCounts = User::selectRaw('role, COUNT(*) as count')
            ->whereIn('role', ['admin', 'staff', 'user'])
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        $roles = ['admin', 'staff', 'user'];
        $counts = [];
        foreach ($roles as $role) {
            $counts[] = $userCounts[$role] ?? 0;
        }

        return view('admin.user.chart', [
            'roles' => json_encode($roles),
            'counts' => json_encode($counts),
        ]);
    }
}


