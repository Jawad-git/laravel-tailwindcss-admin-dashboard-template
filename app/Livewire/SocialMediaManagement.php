<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\SocialMedia;
use App\Models\SocialMediasActive;
use Illuminate\Support\Facades\DB;
use App\Services\LanguageManagementService;
use Exception;
use Livewire\Component;

class SocialMediaManagement extends Component
{
    public $contactInfos = [];
    public $values = [];
    public $languages = [];
    public $currentLanguage;
    public $socialMedias;
    public $socialMediasActive;


    protected function rules()
    {
        $rules = [];
        return $rules;
    }

    public function mount()
    {
        $this->authorize('social-list');
        $this->languages = LanguageManagementService::getLanguages()['data'];
        $languageId = Language::where('code', app()->getLocale())->first()->id;
        $language = collect($this->languages)
            ->firstWhere('id', $languageId);

        // noteLookUp, why 1 not 0? boolean true return?
        $this->currentLanguage = $language;

        $this->socialMediasActive = SocialMediasActive::all();
        foreach ($this->socialMediasActive as $socialMediaActive) {
            $this->contactInfos[] = [
                'type' => $socialMediaActive->name,
                'value' => $socialMediaActive->value,
                'language' => $socialMediaActive->language,
            ];
        }

        $this->socialMedias = SocialMedia::all();
        foreach ($this->socialMedias as $socialMedia) {
            $this->values[$socialMedia->name] = $socialMedia->value;
        }
        $this->addContactInfo();
    }

    public function getTypeNumber($index, $type, $language)
    {
        $count = 0;
        for ($i = 0; $i <= $index; $i++) {
            if (isset($this->contactInfos[$i]) && $this->contactInfos[$i]['type'] === $type && $this->contactInfos[$i]['language'] === $language) {
                $count++;
            }
        }
        return $count;
    }


    public function addContactInfo()
    {
        if (empty($this->contactInfos)) {
            $this->contactInfos[] = [
                'type' => 'WhatsApp',
                'value' => '',
                'language' => 'en',
            ];
        } else {
            $this->contactInfos[] = [
                'type' => 'WhatsApp',
                'value' => '',
                'language' => 'en',
            ];
        }
    }

    public function removeContactInfo($index)
    {
        unset($this->contactInfos[$index]);
        $this->contactInfos = array_values($this->contactInfos);
    }




    public function update()
    {
        $this->resetErrorBag();
        $this->dispatch('scrollToElement');
        $this->dispatch('saved');
        DB::beginTransaction();

        try {
            // drop all previous entries!
            foreach ($this->socialMediasActive as $socialMediaActive) {
                $socialMediaActive->delete();
            }

            foreach ($this->contactInfos as $index => $socialMedia) {
                // if ($socialMedia['type'] == 'WhatsApp' && !$socialMedia['value']) { // add number regex


                // } else if (!$socialMedia['type'] == 'WhatsApp' && !$socialMedia['value']) { // add https regex 
                // }

                // here we re-add the new ones
                if ($socialMedia['type'] && $socialMedia['language'] && $socialMedia['value']) {
                    SocialMediasActive::create([
                        'name' => $socialMedia['type'],
                        'value' => $socialMedia['value'],
                        'language' => $socialMedia['language']
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('social')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }

    public function render()
    {
        return view('livewire.social-media-management');
    }
}
