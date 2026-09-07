<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use App\Exports\UsersTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['institution', 'roles'])->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $institutions = Institution::all();
        $roles = Role::all();
        return view('users.create', compact('institutions', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'institution_id' => 'required',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'institution_id' => $request->institution_id,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $institutions = Institution::all();
        $roles = Role::all();
        return view('users.show', compact('user', 'institutions', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'institution_id' => 'required',
            'role' => 'required'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->institution_id = $request->institution_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $import = new UsersImport();
        Excel::import($import, $request->file('file'));

        $r = $import->results;
        $msg = "Import selesai: {$r['created']} pengguna berhasil dibuat.";

        if ($r['skipped'] > 0) {
            $msg .= " {$r['skipped']} email sudah terdaftar (dilewati).";
        }
        if (!empty($r['errors'])) {
            $msg .= " Peringatan: " . implode(' | ', $r['errors']);
        }

        return back()->with('success', $msg);
    }

    public function downloadUsersTemplate()
    {
        return Excel::download(new UsersTemplateExport(), 'template-import-pengguna.xlsx');
    }
}
