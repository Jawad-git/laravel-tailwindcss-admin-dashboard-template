<?php

namespace App\Livewire\restaurant\foods;

use App\Models\Language;
use App\Models\Food;
use App\Models\Menu;
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
    public array $removedPhotos = [];

    public array $photos = [];        // Files uploaded via dropzone

    // photos
    public array $oldDocs = [];
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
            $this->food->images()->where('id', $oldUpload['id'])->delete();
        }
    }

    public function mount($id)
    {
        $this->authorize('food-edit');
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

        $images = $this->food->images->map(function ($image) {
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
            ->toArray();
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

            if ($this->photos) {
                //$flatPhotos = is_array($this->photos[0]) ? Arr::flatten($this->photos) : $this->photos;
                foreach ($this->photos as $photo) {
                    $photo = TemporaryUploadedFile::createFromLivewire($photo['tmpFilename']);
                    //dump($photo);
                    $image = MediaManagementService::uploadMedia(
                        $photo,
                        '/foods',
                        env('FILESYSTEM_DRIVER'),
                        explode('.', $photo->getClientOriginalName())[0] . '_' . time() . rand(0, 999999999999) . '.' . $photo->getClientOriginalExtension()
                    );
                    $food->images()->create([
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
}
