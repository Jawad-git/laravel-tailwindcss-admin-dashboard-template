<?php

namespace App\Livewire\roomCategories;

use App\Models\RoomCategory;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CategoryEdit extends Component
{
    public $category;
    public $name;
    public $languages;
    public function render()
    {
        return view('livewire.roomCategories.category-edit');
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
        $this->authorize('category-edit');

        $this->category = RoomCategory::findOrFail($id);

        $this->languages = LanguageManagementService::getLanguages();

        foreach ($this->languages['data'] as $lang) {
            $name = Translation::where('model_id', $id)
                ->where('language_id', $lang['id'])
                ->where('model_type', RoomCategory::class)
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
            $category = RoomCategory::findOrFail($this->category->id);
            $category->save();

            foreach ($this->languages['data'] as $value) {
                $category->translations()->updateOrCreate(
                    [
                        'language_id' => $value['id'],
                        'model_type' => RoomCategory::class,
                    ],
                    [
                        'name' => $validatedData['name']['name_' . $value['code']],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('categories')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }
}
