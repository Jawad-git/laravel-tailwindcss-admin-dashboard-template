<?php

namespace App\Livewire\roomCategories;

use App\Models\RoomCategory;
use App\Models\RoomCategoryDetails;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CategoryAdd extends Component
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
        // $this->authorize('roomCategory-create');
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
            $category = new RoomCategory();
            $category->save();

            foreach ($this->languages['data'] as $value) {

                $category->translations()->create([
                    'language_id' => $value['id'],
                    'name' => $validatedData['name']['name_' . $value['code']],
                    'model_type' => RoomCategory::class,
                ]);
            }
            DB::commit();

            return redirect()->route('categories')->with('success',  __('messages.category created successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the Category.');
        }
    }

    public function render()
    {
        return view('livewire.roomCategories.category-add');
    }
}
