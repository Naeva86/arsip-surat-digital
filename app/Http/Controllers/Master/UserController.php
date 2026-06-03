<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Bagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['jabatan', 'bagian'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
            });
        }
        if ($request->filled('role'))       $query->where('role', $request->role);
        if ($request->filled('jabatan_id')) $query->where('jabatan_id', $request->jabatan_id);
        if ($request->filled('bagian_id'))  $query->where('bagian_id', $request->bagian_id);
        if ($request->filled('status_aktif')) {
            $query->where('is_active', $request->status_aktif === 'aktif');
        }

        $users = $query->paginate(10)->withQueryString();

        $totalUser     = User::count();
        $countAdmin    = User::where('role', 'admin')->count();
        $countStaff    = User::where('role', 'staff')->count();
        $countDirektur = User::where('role', 'direktur')->count();
        $countKabag    = User::where('role', 'kabag')->count();
        $countKasubag  = User::where('role', 'kasubbag')->count();
        $countAktif    = User::where('is_active', true)->count();
        $countNonaktif = User::where('is_active', false)->count();

        $jabatans = \App\Models\Jabatan::orderBy('level')->get();
        $bagians  = \App\Models\Bagian::orderBy('nama_bagian')->get();

        return view('master.user.index', compact(
            'users', 'totalUser',
            'countAdmin', 'countStaff', 'countDirektur', 'countKabag', 'countKasubag',
            'countAktif', 'countNonaktif',
            'jabatans', 'bagians'
        ));
    }

    public function create()
    {
        $jabatans       = Jabatan::orderBy('level')->get();
        $bagianKabag    = Bagian::whereNull('parent_id')->orderBy('nama_bagian')->get();
        $bagianKasubag  = Bagian::whereNotNull('parent_id')->orderBy('nama_bagian')->get();

        return view('master.user.create', compact('jabatans', 'bagianKabag', 'bagianKasubag'));
    }

    /**
     * Map jabatan → role
     */
    private function resolveRole(int $jabatanId): string
    {
        $jabatan = Jabatan::find($jabatanId);
        if (!$jabatan) return 'staff';

        return match($jabatan->level) {
            1 => 'direktur',
            2 => 'kabag',
            3 => 'kasubbag',
            default => 'staff',
        };
    }

    public function store(Request $request)
    {
        $role = $this->resolveRole($request->jabatan_id);
        $bagianRequired = in_array($role, ['kabag', 'kasubbag']) ? 'required' : 'nullable';

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'nip'        => 'required|string|max:50|unique:users,nip',
            'email'      => 'required|email|max:255|unique:users,email',
            'jabatan_id' => 'required|exists:jabatans,id',
            'bagian_id'  => $bagianRequired . '|exists:bagians,id',
            'password'   => 'required|min:6|confirmed',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['role']      = $role;
        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        if (!in_array($role, ['kabag', 'kasubbag'])) {
            $validated['bagian_id'] = $request->bagian_id ?: null;
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        User::create($validated);

        return redirect()->route('master.user.index')
                         ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $jabatans       = Jabatan::orderBy('level')->get();
        $bagianKabag    = Bagian::whereNull('parent_id')->orderBy('nama_bagian')->get();
        $bagianKasubag  = Bagian::whereNotNull('parent_id')->orderBy('nama_bagian')->get();

        return view('master.user.edit', compact('user', 'jabatans', 'bagianKabag', 'bagianKasubag'));
    }

    public function update(Request $request, User $user)
    {
        $role = $this->resolveRole($request->jabatan_id);
        $bagianRequired = in_array($role, ['kabag', 'kasubbag']) ? 'required' : 'nullable';

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'nip'        => 'required|string|max:50|unique:users,nip,' . $user->id,
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'jabatan_id' => 'required|exists:jabatans,id',
            'bagian_id'  => $bagianRequired . '|exists:bagians,id',
            'password'   => 'nullable|min:6|confirmed',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'  => 'nullable',
        ]);

        $validated['role']      = $role;
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if (!in_array($role, ['kabag', 'kasubbag'])) {
            $validated['bagian_id'] = $request->bagian_id ?: null;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $validated['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        if ($request->has('hapus_foto') && $request->hapus_foto == '1') {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $validated['foto'] = null;
        }

        $user->update($validated);

        return redirect()->route('master.user.index')
                        ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('master.user.index')
                             ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->update(['is_active' => false]);
        $user->delete();

        return redirect()->route('master.user.index')
                         ->with('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
    }
}