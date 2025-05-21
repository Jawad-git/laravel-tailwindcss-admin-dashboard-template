<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\AboutUs;
use App\Models\Image;
use Livewire\Attributes\On;
use App\Models\Translation;
use App\Services\LanguageManagementService;
use App\Services\MediaManagementService;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AboutUsManagement extends Component
{
    public $aboutus;
    public $names;
    public $descriptions;
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
            $this->aboutus->images()->where('id', $oldUpload['id'])->delete();
        }
    }

    public function mount()
    {
        $this->authorize('about-list');
        $this->language = Language::where('code', app()->getLocale())->first()->id;
        $language = $this->language;

        $this->aboutus = AboutUs::first();
        if ($this->aboutus == null) {
            dd($this->aboutus);
        }

        $this->languages = LanguageManagementService::getLanguages();

        foreach ($this->languages['data'] as $lang) {
            $translation = Translation::where('model_id', $this->aboutus->id)
                ->where('language_id', $lang['id'])
                ->where('model_type', AboutUs::class)
                ->first();
            $this->names["name_" . $lang['code']] = $translation ? $translation->name : '';

            $translation = Translation::where('model_id', $this->aboutus->id)
                ->where('language_id', $lang['id'])
                ->where('model_type', AboutUs::class)
                ->first();
            $this->descriptions["description_" . $lang['code']] = $translation ? $translation->description : '';
        }

        $images = $this->aboutus->images->map(function ($image) {
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
            dump($this->oldDocs);
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
            $aboutus = AboutUs::first();
            $aboutus->save();

            foreach ($this->languages['data'] as $value) {
                $aboutus->translations()->updateOrCreate(
                    [
                        'language_id' => $value['id'],
                        'model_type' => AboutUs::class,
                    ],
                    [
                        'name' => $validatedData['names']['name_' . $value['code']],
                        'description' => $validatedData['descriptions']['description_' . $value['code']],
                    ]
                );
            }

            if ($this->photos) {
                //$flatPhotos = is_array($this->photos[0]) ? Arr::flatten($this->photos) : $this->photos;
                foreach ($this->photos as $photo) {
                    $photo = TemporaryUploadedFile::createFromLivewire($photo['tmpFilename']);
                    //dump($photo);
                    $image = MediaManagementService::uploadMedia(
                        $photo,
                        '/aboutus',
                        env('FILESYSTEM_DRIVER'),
                        explode('.', $photo->getClientOriginalName())[0] . '_' . time() . rand(0, 999999999999) . '.' . $photo->getClientOriginalExtension()
                    );
                    $aboutus->images()->create([
                        'path' => $image,
                    ]);
                }
            }

            $this->deleteOldUploads($this->removedPhotos);


            DB::commit();

            return redirect()->route('foods')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }
    public function render()
    {
        return view('livewire.about-us-management');
    }
}
