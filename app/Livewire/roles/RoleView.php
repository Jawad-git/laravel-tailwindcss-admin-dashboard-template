<?php

namespace App\Livewire\roles;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\On;

use Livewire\WithPagination;

class RoleView extends Component
{
    use AuthorizesRequests, WithPagination;
    protected $role;
    protected $paginationTheme = 'bootstrap';
    public $searchableFields = ['name'];
    public $search = '';

    public function mount()
    {
        $this->authorize('role-list');
    }

    #[On('destroy')]
    public function destroy($id)
    {
        Role::where('id', $id)->delete();



        return redirect()->route('roles')
            ->with('success', 'The role has been deleted successfully.');
    }

    public function render()
    {
        if (auth()->user()->hasRole(roles: 1)) {
            $this->role = Role::searchMany($this->searchableFields, $this->search);
        } else {
            $this->role = Role::whereNotIn('id', [1, 2, 3])->searchMany($this->searchableFields, $this->search);
        }



        return view('livewire.roles.role-view', ['role' => $this->role]);
    }
}
