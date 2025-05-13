<?php

namespace App\Livewire\restaurant\foods;

use App\Models\Food;
use App\Models\Menu;
use App\Models\Language;
use App\Models\FoodFoodDetails;
use App\Models\Translation;
use Livewire\Attributes\On;
use App\Services\LanguageManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FoodAdd extends Component
{
    use AuthorizesRequests;

    public $languages;
    public $name;
    public $price;
    public $selectedMenu;
    public $menuOptions;

    public $pages;
    public $language;

    protected function rules()
    {
        $rules = [
            'name.*' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'selectedMenu' => 'required|integer|exists:menus,id'
        ];
        return $rules;
    }

    public function mount()
    {
        $this->language = Language::where('code', app()->getLocale())->first()->id;
        $language = $this->language;
        // $this->authorize('roomFood-create');
        $this->languages = LanguageManagementService::getLanguages();
        foreach ($this->languages['data'] as $lang) {
            $this->name["name_" . $lang['code']] = '';
        }

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
            ->toArray();
    }

    #[On('menuSelectize')]
    public function menuSelectize($values)
    {
        // dd("Event received with value: " . $values);  // Immediately dumps and dies

        $this->selectedMenu = $values;
    }

    public function store()
    {

        $this->resetErrorBag();

        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();

        $this->dispatch('saved');

        DB::beginTransaction();

        try {
            $food = new Food();
            $food->menu_id = $validatedData['selectedMenu'];
            $food->price = $validatedData['price'];
            $food->save();

            foreach ($this->languages['data'] as $value) {

                $food->translations()->create([
                    'language_id' => $value['id'],
                    'name' => $validatedData['name']['name_' . $value['code']],
                    'model_type' => Food::class,
                ]);
            }
            DB::commit();

            return redirect()->route('foods')->with('success',  __('messages.food created successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the Food.');
        }
    }

    public function render()
    {
        return view('livewire.restaurant.foods.food-add');
    }
}
