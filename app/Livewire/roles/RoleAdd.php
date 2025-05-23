<?php

namespace App\Livewire\roles;

use Livewire\Component;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleAdd extends Component
{
    public $name;
    public $permission;
    public $role;

    public $searchTerm = ''; // New property for search term

    public $selectAll = false;
    public $notBooted = true;
    // Group select all tracking, associative array (key => boolean), group name => boolean for status
    public $groupSelectAll = [];

    // Selected permissions, associative array (key => boolean), permission id => boolean for status
    public $selectedPermission = [];

    // filtered permissions, associative array (key => array), group name => associated permissions array (each array entry is the full object)
    public $filteredPermissions = [];

    // all permissions, array of strings
    // not used anywhere by the looks of it
    public $allpermissions = [];

    protected $rules = [
        'name' => 'required|unique:roles,name',
        'permission' => 'required'
    ];

    public function mount()
    {
        $this->authorize('role-create');

        $this->loadPermissions();
    }


    public function loadPermissions()
    {
        if (auth()->user()->hasRole(1)) {
            $this->permission = Permission::query()
                ->when($this->searchTerm, function ($query) {

                    $query->where('name', 'like', '%' . $this->searchTerm . '%');
                })
                ->get();
        } else {
            $this->permission = Permission::query()
                ->whereNotIn('name', [
                    'instructor-list',
                    'instructor-create',
                    'instructor-edit',
                    'instructor-delete',

                    'course-list',
                    'course-create',
                    'course-edit',
                    'course-delete',

                    'certificate-type-create',
                    'certificate-type-edit',
                    'certificate-type-delete',

                    'certificate-list',
                    'certificate-create',
                    'certificate-edit',
                    'certificate-delete',
                ])
                ->when($this->searchTerm, function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%');
                })
                ->get();
        }

        $tempobj = [];

        foreach ($this->permission as $p) {
            $permissionName = explode("-", $p->name);
            $permHead = $permissionName[0];
            $p->permissionSubName = $permissionName[1] ?? null; // safer in case there's no '-'

            // Initialize group if it doesn't exist
            if (!isset($tempobj[$permHead])) {
                $tempobj[$permHead] = [];

                // to prevent select all from being checked when checking only 1 group on launch
                $this->groupSelectAll[$permHead] = false;
                $this->selectAll = false;
            }

            $tempobj[$permHead][] = $p;

            // to prevent select all from being checked when checking only 1 group on launch
            $this->selectedPermission[$p->id] = false;
        }

        $this->filteredPermissions = $tempobj;

        $this->allpermissions = DB::table('role_has_permissions')
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
    }

    // Trigger search when the search term is updated
    public function updatedSearchTerm()
    {
        $this->loadPermissions();
    }
    public function togglePermission($permissionId, $isChecked)
    {
        $this->selectedPermission[$permissionId] = $isChecked;
        $permission = Permission::findOrFail($permissionId);
        $permissionName = explode("-", $permission->name);
        $permHead = $permissionName[0];
        
        // Update group select all state depending on the permission checked
        if (!$isChecked) {
            $this->unselectGroupPermissionOnUnselectSubCheckbox($permHead);
        }else{
            $this->selectGroupPermissionOnselectSubCheckbox($permHead);
        }
    }

    public function selectAllPermissions()
    {
        if ($this->selectAll) {
            foreach ($this->filteredPermissions as $groupName => $permissions) {
                $this->groupSelectAll[$groupName] = true;
                foreach ($permissions as $permission) {
                    $this->selectedPermission[$permission->id] = true;
                }
            }
        } else {
            foreach ($this->filteredPermissions as $groupName => $permissions) {
                $this->groupSelectAll[$groupName] = false;
                foreach ($permissions as $permission) {
                    $this->selectedPermission[$permission->id] = false;
                }
            }
        }
    }

    public function selectGroupPermissions($groupName)
    {
        $selectAll = $this->groupSelectAll[$groupName] ?? false;

        foreach ($this->filteredPermissions[$groupName] as $permission) {
            $this->selectedPermission[$permission->id] = $selectAll;
        }

        $this->selectAll = collect($this->groupSelectAll)->every(function ($value) {
            return $value === true;
        });
    }

    public function updateSelectAll()
    {
        $this->selectAll = collect($this->groupSelectAll)->every(function ($value) {
            return $value === true;
        });
    }

    public function updateGroupSelectAll()
    {
        foreach ($this->filteredPermissions as $groupName => $permissions) {
            $this->groupSelectAll[$groupName] = collect($permissions)->every(function ($permission) {
                return isset($this->selectedPermission[$permission->id]) && $this->selectedPermission[$permission->id];
            });
        }
        $this->updateSelectAll();
    }

    public function unselectGroupPermissionOnUnselectSubCheckbox($permHead)
    {
        $this->groupSelectAll[$permHead] = false;
        $this->selectAll = false;
    }

    public function selectGroupPermissionOnselectSubCheckbox($permHead)
    {
        $this->updateGroupSelectAll();
    }

    public function store()
    {
        $this->dispatch('scrollToElement');

        $validatedData = $this->validate([
            'name' => 'required|unique:roles,name',
            'selectedPermission' => 'required'
        ]);

        $this->dispatch('saved');

        foreach ($validatedData['selectedPermission'] as $key => $value) {
            if ($value == false) {
                unset($validatedData['selectedPermission'][$key]);
            }
        }

        $role = Role::create(['name' => $validatedData['name']]);
        $role->syncPermissions(array_keys($validatedData['selectedPermission']));

        return redirect()->route('roles')
            ->with('success',   'A new role has been added.');
    }

    public function render()
    {
        $this->dispatch('cardLoaded', true);

        return view('livewire.roles.role-add');
    }
}
