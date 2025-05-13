<?php

namespace App\Livewire\restaurant\menus;

use App\Models\Menu;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class MenuEdit extends Component
{
    public $menu;
    public $name;
    public $languages;
    public function render()
    {
        return view('livewire.restaurant.menus.menu-edit');
    }
    protected function rules()
    {
        $rules = [
            'name.*' => 'required|string',
        ];
        return $rules;
    }

    public function mount($id)
    {
        $this->menu = Menu::findOrFail($id);

        $this->languages = LanguageManagementService::getLanguages();

        foreach ($this->languages['data'] as $lang) {
            $name = Translation::where('model_id', $id)
                ->where('language_id', $lang['id'])
                ->where('model_type', Menu::class)
                ->first();
            $this->name["name_" . $lang['code']] = $name ? $name->name : '';
        }
    }

    public function update()
    {
        $this->resetErrorBag();
        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();
        $this->dispatch('saved');
        DB::beginTransaction();

        try {
            $menu = Menu::findOrFail($this->menu->id);
            $menu->save();

            foreach ($this->languages['data'] as $value) {
                $menu->translations()->updateOrCreate(
                    [
                        'language_id' => $value['id'],
                        'model_type' => Menu::class,
                    ],
                    [
                        'name' => $validatedData['name']['name_' . $value['code']],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('menus')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }
}
