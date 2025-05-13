<?php

namespace App\Livewire\restaurant\menus;

use App\Models\Menu;
use App\Models\MenuDetails;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuAdd extends Component
{
    use AuthorizesRequests;

    public $languages;
    public $name;

    public $pages;

    protected function rules()
    {
        $rules = [
            'name.*' => 'required|string',
        ];
        return $rules;
    }

    public function mount()
    {
        // $this->authorize('roomMenu-create');
        $this->languages = LanguageManagementService::getLanguages();
        foreach ($this->languages['data'] as $lang) {
            $this->name["name_" . $lang['code']] = '';
        }
    }

    public function store()
    {

        $this->resetErrorBag();

        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();

        $this->dispatch('saved');

        DB::beginTransaction();

        try {
            $menu = new Menu();
            $menu->path = 1;
            $menu->save();

            foreach ($this->languages['data'] as $value) {

                $menu->translations()->create([
                    'language_id' => $value['id'],
                    'name' => $validatedData['name']['name_' . $value['code']],
                    'model_type' => Menu::class,
                ]);
            }
            DB::commit();

            return redirect()->route('menus')->with('success',  __('messages.menu created successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the Menu.');
        }
    }

    public function render()
    {
        return view('livewire.restaurant.menus.menu-add');
    }
}
