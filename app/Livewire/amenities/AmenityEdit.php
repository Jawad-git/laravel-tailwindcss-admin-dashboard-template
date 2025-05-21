<?php

namespace App\Livewire\amenities;

use App\Models\Amenity;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AmenityEdit extends Component
{
    public $amenity;
    public $name;
    public $languages;
    public function render()
    {
        return view('livewire.amenities.amenity-edit');
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
        $this->authorize('amenity-edit');
        $this->amenity = Amenity::findOrFail($id);
        $this->languages = LanguageManagementService::getLanguages();

        foreach ($this->languages['data'] as $lang) {
            $name = Translation::where('model_id', $id)
                ->where('language_id', $lang['id'])
                ->where('model_type', Amenity::class)
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
            $amenity = Amenity::findOrFail($this->amenity->id);
            $amenity->save();

            foreach ($this->languages['data'] as $value) {
                $amenity->translations()->updateOrCreate(
                    [
                        'language_id' => $value['id'],
                        'model_type' => Amenity::class,
                    ],
                    [
                        'name' => $validatedData['name']['name_' . $value['code']],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('amenities')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }
}
