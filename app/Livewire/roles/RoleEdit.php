<?php

namespace App\Livewire\roles;

use Livewire\Component;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleEdit extends Component
{
    use AuthorizesRequests;

    public $roleId;
    public $name;
    public $permission;
    public $role;

    public $searchTerm = '';

    public $selectAll = false;
    public $notBooted = true;
    public $groupSelectAll = [];

    // the variables have their structures described in the RoleAdd.php file,
    // refer to it for any issues.
    public $selectedPermission = [];
    public $filteredPermissions = [];
    public $allpermissions = [];

    protected $rules = [
        'name' => 'required|unique:roles,name',
        'permission' => 'required'
    ];

    public function mount($id)
    {
        $this->authorize('role-edit');

        $this->roleId = $id;
        $this->role = Role::findOrFail($id);
        $this->name = $this->role->name;

        $this->loadPermissions();
        $this->initializeSelectedPermissions();
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
        foreach ($this->permission as $key => $p) {
            $permissionName = explode("-", $p->name);
            $permHead = $permissionName[0];

            if ((count($tempobj) > 0 && array_keys($tempobj)[count($tempobj) - 1] != $permHead) || $key == 0) {
                $tempobj[$permHead] = [];

                // to prevent select all from being checked when checking only 1 group on launch
                $this->groupSelectAll[$permHead] = false;
                $this->selectAll = false;
            }
            $p->permissionSubName = $permissionName[1];
            array_push($tempobj[$permHead], $p);

            // to prevent select all from being checked when checking only 1 group on launch
            $this->selectedPermission[$p->id] = false;
        }

        $this->filteredPermissions = $tempobj;

        $this->allpermissions = DB::table('role_has_permissions')
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
    }

    public function initializeSelectedPermissions()
    {
        $rolePermissions = $this->role->permissions->pluck('id')->toArray();
        foreach ($this->permission as $p) {
            $this->selectedPermission[$p->id] = in_array($p->id, $rolePermissions);
        }
        $this->updateGroupSelectAll();
    }

    public function updatedSearchTerm()
    {
        $this->loadPermissions();
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


    public function selectGroupPermissions($groupName)
    {
        $selectAll = $this->groupSelectAll[$groupName] ?? false;

        foreach ($this->filteredPermissions[$groupName] as $permission) {
            $this->selectedPermission[$permission->id] = $selectAll;
        }

        $this->updateSelectAll();
    }

    public function updateSelectAll()
    {
        $this->selectAll = collect($this->groupSelectAll)->every(function ($value) {
            return $value === true;
        });
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

    public function updateGroupSelectAll()
    {
        foreach ($this->filteredPermissions as $groupName => $permissions) {
            $this->groupSelectAll[$groupName] = collect($permissions)->every(function ($permission) {
                return isset($this->selectedPermission[$permission->id]) && $this->selectedPermission[$permission->id];
            });
        }
        $this->updateSelectAll();
    }

    public function update()
    {
        $this->dispatch('scrollToElement');

        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
            'selectedPermission' => 'required'
        ]);

        $this->dispatch('saved');

        $selectedPermissions = array_keys(array_filter($this->selectedPermission));

        $this->role->update(['name' => $this->name]);
        $this->role->syncPermissions($selectedPermissions);


        return redirect()->route('roles')
            ->with('success', 'The role has been updated.');
    }

    public function render()
    {
        $this->dispatch('cardLoaded', true);

        return view('livewire.roles.role-edit');
    }
}
