<?php

namespace App\Livewire\admins;

use App\Models\User;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class AdminView extends Component
{
    use AuthorizesRequests, WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $user;

    public $adminList;
    public $neverExpired = [];
    public $table;
    public $search = '';
    public $searchableFields = ['name', 'email'];

    protected $listeners = [
        'destroy',
    ];

    public function mount()
    {
        $this->authorize('admin-list');

        $this->adminList = User::get();
        foreach ($this->adminList as $menu) {
            $this->neverExpired[$menu->id] = $menu->status == 1 ? true : false;
        }
    }




    public function updatedNeverExpired($data)
    {
        $menuIdsToUpdate = array_keys(array_filter($this->neverExpired));

        User::whereIn('id', $menuIdsToUpdate)->update(['status' => 1]);
        User::whereNotIn('id', $menuIdsToUpdate)->update(['status' => 0]);

        session()->flash('success', 'Status changed');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('destroy')]
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('admins')
            ->with('success', 'تم حذف المشرف بنجاح');
    }

    public function render()
    {
        if (auth()->user()->hasRole(1)) {
            $this->user = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Organization');
            })->searchMany($this->searchableFields, $this->search);
        } else {
            $this->user = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Organization')->orWhere('name', 'Super Admin')->orWhere('name', 'American Board');
            })->searchMany($this->searchableFields, $this->search);
        }

        return view('livewire.admins.admin-view', [
            'admins' => $this->user,
        ]);
    }
}
