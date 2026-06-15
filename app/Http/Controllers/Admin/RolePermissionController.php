<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    // ─────────────────────────────────────────────
    //  ROLE MANAGEMENT
    // ─────────────────────────────────────────────

    /**
     * Daftar semua role beserta jumlah user & permission.
     */
    public function roleIndex()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();
        $totalPermissions = Permission::count();
        return view('admin.rbac.role-index', compact('roles', 'totalPermissions'));
    }

    /**
     * Form buat role baru.
     */
    public function roleCreate()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn($p) => explode('-', $p->name)[0]);
        return view('admin.rbac.role-form', compact('permissions'));
    }

    /**
     * Simpan role baru.
     */
    public function roleStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role <strong>{$role->name}</strong> berhasil dibuat dengan " . count($validated['permissions'] ?? []) . " permission.");
    }

    /**
     * Form edit role & permission-nya.
     */
    public function roleEdit(Role $role)
    {
        $permissions    = Permission::orderBy('name')->get()->groupBy(fn($p) => explode('-', $p->name)[0]);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.rbac.role-form', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role.
     */
    public function roleUpdate(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50', "unique:roles,name,{$role->id}"],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role <strong>{$role->name}</strong> berhasil diperbarui.");
    }

    /**
     * Hapus role.
     */
    public function roleDestroy(Role $role)
    {
        // Proteksi: role yang masih dipakai user tidak bisa dihapus
        if ($role->users()->count() > 0) {
            return back()->withErrors(['error' => "Role <strong>{$role->name}</strong> tidak dapat dihapus karena masih digunakan oleh {$role->users()->count()} pengguna."]);
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Role <strong>{$roleName}</strong> berhasil dihapus.");
    }

    // ─────────────────────────────────────────────
    //  PERMISSION MANAGEMENT
    // ─────────────────────────────────────────────

    /**
     * Daftar semua permission beserta role yang memilikinya.
     */
    public function permissionIndex()
    {
        $permissions = Permission::with('roles')->orderBy('name')->get()
            ->groupBy(fn($p) => explode('-', $p->name)[0]);
        return view('admin.rbac.permission-index', compact('permissions'));
    }

    /**
     * Form buat permission baru.
     */
    public function permissionCreate()
    {
        return view('admin.rbac.permission-form');
    }

    /**
     * Simpan permission baru.
     */
    public function permissionStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:permissions,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Permission::create(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission <strong>{$validated['name']}</strong> berhasil dibuat.");
    }

    /**
     * Hapus permission.
     */
    public function permissionDestroy(Permission $permission)
    {
        $permName = $permission->name;
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission <strong>{$permName}</strong> berhasil dihapus.");
    }

    // ─────────────────────────────────────────────
    //  USER-ROLE ASSIGNMENT
    // ─────────────────────────────────────────────

    /**
     * Tampilkan halaman assign role untuk user tertentu.
     */
    public function userRoles(User $user)
    {
        $roles     = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.rbac.user-roles', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Simpan assignment role ke user.
     */
    public function userRolesUpdate(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles'   => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $newRoles = $validated['roles'] ?? [];

        // Sync roles via spatie
        $user->syncRoles($newRoles);

        // Sinkronkan kolom `role` dengan role utama (first role)
        $primaryRole = count($newRoles) > 0 ? $newRoles[0] : null;
        if ($primaryRole) {
            $user->update(['role' => $primaryRole]);
        }

        return redirect()->route('admin.user.index')
            ->with('success', "Role pengguna <strong>{$user->name}</strong> berhasil diperbarui.");
    }
}
