<?php

namespace App\Livewire;

use App\Models\Availability;
use App\Models\Language;
use App\Models\SwimmingPool;
use App\Models\Image;
use Livewire\Attributes\On;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use App\Services\MediaManagementService;
use App\Services\WeekdayManagementService;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class SwimmingPoolManagement extends Component
{

    public $swimmingpool;
    public $names;
    public $descriptions;

    // scheduler-timer related variables
    public $weekdays;
    public $activeDays;
    public $startTimes;
    public $endTimes;

    // scheduler-timer related variables


    public $languages;

    public $price;
    public $selectedMenu;
    public $menuOptions;

    public $pages;
    public $language;
    public array $removedPhotos = [];

    public array $photos = [];        // Files uploaded via dropzone

    // photos
    public array $oldDocs = [];


    protected function rules()
    {
        $rules = [
            'names.*' => 'required|string',
            'descriptions.*' => 'required|string',
            'activeDays.*' => 'required|boolean',
            'startTimes.*' => [function ($attribute, $value, $fail) {
                if (!is_null($value) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
                    $fail('The ' . $attribute . ' must be a valid time (HH:mm).');
                }
            }],
            'endTimes.*' => [function ($attribute, $value, $fail) {
                if (!is_null($value) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
                    $fail('The ' . $attribute . ' must be a valid time (HH:mm).');
                }
            }],
        ];
        return $rules;
    }

    #[On('removeOldUpload')]
    public function removeOldUpload($id)
    {
        $photo = Image::find($id);

        $found = null;
        foreach ($this->oldDocs as $index => $doc) {
            if ($doc['id'] === $id) {
                unset($this->oldDocs[$index]); // remove from the actual array
                break;
            }
        }
        $this->oldDocs = array_values($this->oldDocs);
        $this->removedPhotos[] = $photo;
    }

    public function deleteOldUploads($oldUploads)
    {
        foreach ($oldUploads as $oldUpload) {
            MediaManagementService::removeMedia($oldUpload['path']);
            $this->swimmingpool->images()->where('id', $oldUpload['id'])->delete();
        }
    }

    // scheduler-timer related method

    public function toggleAvailability($weekdayName)
    {
        $this->activeDays[$weekdayName] = !$this->activeDays[$weekdayName];
        $this->startTimes[$weekdayName] = null;
        $this->endTimes[$weekdayName] = null;
    }
    // scheduler-timer related method

    public function mount()
    {
        $this->authorize('pool-list');
        $this->language = Language::where('code', app()->getLocale())->first()->id;
        $language = $this->language;

        $this->swimmingpool = SwimmingPool::first();
        if ($this->swimmingpool == null) {
            dd($this->swimmingpool);
        }

        $this->languages = LanguageManagementService::getLanguages();


        foreach ($this->languages['data'] as $lang) {
            $translation = Translation::where('model_id', $this->swimmingpool->id)
                ->where('language_id', $lang['id'])
                ->where('model_type', SwimmingPool::class)
                ->first();
            $this->names["name_" . $lang['code']] = $translation ? $translation->name : '';

            // note: second query is useless? copying bug?
            $translation = Translation::where('model_id', $this->swimmingpool->id)
                ->where('language_id', $lang['id'])
                ->where('model_type', SwimmingPool::class)
                ->first();
            $this->descriptions["description_" . $lang['code']] = $translation ? $translation->description : '';
        }

        // scheduler-timer related work
        $this->weekdays = WeekdayManagementService::getWeekdays()['data'];

        foreach ($this->weekdays as $weekday) {
            $availability = Availability::where('model_id', $this->swimmingpool->id)
                ->where('weekday_id', $weekday['id'])
                ->where('model_type', SwimmingPool::class)
                ->first();

            $this->startTimes[$weekday['name']] = $availability ? $availability->start_time : '';
            $this->endTimes[$weekday['name']] = $availability ? $availability->end_time : '';
            $this->activeDays[$weekday['name']] = $availability ? $availability->active : true;
        }
        // scheduler-timer related work

        $images = $this->swimmingpool->images->map(function ($image) {
            return [
                'id' => $image->id,
                'path' => $image->path,
            ];
        })->toArray();
        $disk = 'public';

        foreach ($images as $image) {
            $path = $image['path'];
            $file = [];
            $file['path'] = $path;
            $file['id'] = $image['id'];
            $file['name'] = basename($path);
            $file['extension'] = pathinfo($path, PATHINFO_EXTENSION);
            if (Storage::disk($disk)->exists($path)) {
                $fileSize = Storage::disk($disk)->size($path);
                $file['size'] = $fileSize;
            } else {
                throw new \Exception("File does not exist at: $path on disk $disk");
            }
            $file['temporaryUrl'] = Storage::url($path); // gives you /storage/uploads/example.pdf
            $this->oldDocs[] = $file;
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
            $swimmingpool = SwimmingPool::first();
            $swimmingpool->save();

            foreach ($this->languages['data'] as $value) {
                $swimmingpool->translations()->updateOrCreate(
                    [
                        'language_id' => $value['id'],
                        'model_type' => SwimmingPool::class,
                    ],
                    [
                        'name' => $validatedData['names']['name_' . $value['code']],
                        'description' => $validatedData['descriptions']['description_' . $value['code']],
                    ]
                );
            }

            // scheduler-timer related work
            foreach ($this->weekdays as $weekday) {
                if (($this->startTimes[$weekday['name']] && $this->startTimes[$weekday['name']]) && strtotime($validatedData['startTimes'][$weekday['name']]) >= strtotime($validatedData['endTimes'][$weekday['name']])) {
                    $this->addError('startTimes.' . $weekday['name'], __('messages.start time should be before end time'));
                    return;
                }

                $swimmingpool->availabilities()->updateOrCreate(
                    [
                        'weekday_id' => $weekday['id'],
                        'model_type' => SwimmingPool::class,
                    ],
                    [
                        'start_time' => $validatedData['startTimes'][$weekday['name']],
                        'end_time' => $validatedData['endTimes'][$weekday['name']],
                        'active' => $validatedData['activeDays'][$weekday['name']],
                        'weekday_id' => $weekday['id'],
                    ]
                );
            }
            // scheduler-timer related work

            if ($this->photos) {
                //$flatPhotos = is_array($this->photos[0]) ? Arr::flatten($this->photos) : $this->photos;
                foreach ($this->photos as $photo) {
                    $photo = TemporaryUploadedFile::createFromLivewire($photo['tmpFilename']);
                    $image = MediaManagementService::uploadMedia(
                        $photo,
                        '/swimmingpool',
                        env('FILESYSTEM_DRIVER'),
                        explode('.', $photo->getClientOriginalName())[0] . '_' . time() . rand(0, 999999999999) . '.' . $photo->getClientOriginalExtension()
                    );
                    $swimmingpool->images()->create([
                        'path' => $image,
                    ]);
                }
            }

            $this->deleteOldUploads($this->removedPhotos);


            DB::commit();

            return redirect()->route('pool')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }
    public function render()
    {
        return view('livewire.swimming-pool-management');
    }
}
