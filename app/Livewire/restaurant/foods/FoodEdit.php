<?php

namespace App\Livewire\restaurant\foods;

use App\Models\Language;
use App\Models\Food;
use App\Models\Menu;
use Livewire\Attributes\On;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class FoodEdit extends Component
{
    public $food;
    public $name;
    public $languages;

    public $price;
    public $selectedMenu;
    public $menuOptions;

    public $pages;
    public $language;
    public function render()
    {
        return view('livewire.restaurant.foods.food-edit');
    }
    protected function rules()
    {
        $rules = [
            'name.*' => 'required|string',
            'price' => 'required|numeric|min:0',
            'selectedMenu' => 'required|integer|exists:menus,id'
        ];
        return $rules;
    }

    public function mount($id)
    {
        $this->language = Language::where('code', app()->getLocale())->first()->id;
        $language = $this->language;

        $this->food = Food::findOrFail($id);
        $this->languages = LanguageManagementService::getLanguages();

        foreach ($this->languages['data'] as $lang) {
            $name = Translation::where('model_id', $id)
                ->where('language_id', $lang['id'])
                ->where('model_type', Food::class)
                ->first();
            $this->name["name_" . $lang['code']] = $name ? $name->name : '';
        }

        $this->price = $this->food->price;
        $this->selectedMenu = $this->food->menu_id;

        $this->menuOptions  = Menu::whereHas('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->get('id')
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => optional($menu->translations->first())->name ?? 'Unnamed',
                ];
            })
            ->toArray();;
    }

    #[On('menuSelectize')]
    public function classSelectize($values)
    {
        $this->selectedMenu = $values;
    }

    public function update()
    {
        $this->resetErrorBag();
        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();
        $this->dispatch('saved');
        DB::beginTransaction();

        try {
            $food = Food::findOrFail($this->food->id);
            $food->menu_id = $validatedData['selectedMenu'];
            $food->price = $validatedData['price'];
            $food->save();

            foreach ($this->languages['data'] as $value) {
                $food->translations()->updateOrCreate(
                    [
                        'language_id' => $value['id'],
                        'model_type' => Food::class,
                    ],
                    [
                        'name' => $validatedData['name']['name_' . $value['code']],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('foods')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }
}
