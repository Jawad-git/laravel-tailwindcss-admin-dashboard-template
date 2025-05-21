<?php

namespace App\Livewire\amenities;

use App\Models\Amenity;
use App\Models\AmenityDetails;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AmenityAdd extends Component
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
        $this->authorize('amenity-create');
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
            $amenity = new Amenity();
            $amenity->save();

            foreach ($this->languages['data'] as $value) {

                $amenity->translations()->create([
                    'language_id' => $value['id'],
                    'name' => $validatedData['name']['name_' . $value['code']],
                    'model_type' => Amenity::class,
                ]);
            }
            DB::commit();

            return redirect()->route('amenities')->with('success',  __('messages.amenity created successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the Amenity.');
        }
    }

    public function render()
    {
        return view('livewire.amenities.amenity-add');
    }
}
