<?php

namespace App\Livewire\restaurant\menus;

use App\Models\Language;
use App\Models\Menu;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;


class MenuView extends Component
{
    use AuthorizesRequests, WithPagination;

    protected $menus;
    public $language;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $searchableFields = ['name'];

    public function mount()
    {
        //$this->authorize('menu-list');
        $this->language = Language::where('code', app()->getLocale())->first()->id;
    }

    #[On('destroy')]
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $menu = Menu::find($id);

            if ($menu) {
                $menu->delete();
            }

            DB::commit();

            return redirect()->route('menus')
                ->with('success', __('messages.section deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('menus')
                ->with('error', __('An error occurred while deleting the section.'));
        }
    }



    public function render()
    {
        $language = $this->language;

        $this->menus = Menu::whereHas('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })
            ->searchMany($this->searchableFields, $this->search);
        return view('livewire.restaurant.menus.menu-view', ['menus' => $this->menus]);
    }
}
