<?php

namespace App\Livewire\rooms;

use App\Models\Language;
use App\Models\Room;
use App\Models\RoomCategory;
use Livewire\Attributes\On;
use App\Models\Amenity;
use App\Models\Image;
use App\Models\RoomDetails;
use App\Models\Translation;
use Illuminate\Validation\Rule;
use App\Services\LanguageManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\MediaManagementService;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;



use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoomEdit extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $room;
    public $search = '';

    public $searchableFields = [''];

    public $languages;
    public $statuses;
    public $views;
    public $descriptions;


    public $number;
    public $floor;
    public $capacity;
    public $roomCategory;
    public $bedCount;
    public $pricePerNight;
    public $size;
    public $isAvailable;

    public $selectedCategory;
    public $categoryOptions;
    public $amenityOptions;
    public $selectedAmenities;
    public $language;

    public array $existingPhotos = [];   // Paths from DB
    public array $photos = [];        // Files uploaded via dropzone
    public array $removedPhotos = [];
    // photos
    public array $oldDocs = [];




    protected function rules()
    {
        $allowedStatuses = [
            'available',
            'occupied',
            'maintenance',
            'out of service',
            'متاح',
            'مشغول',
            'صيانة',
            'خارج الخدمة',
            'در دسترس',
            'اشغال شده',
            'در حال تعمیر',
            'خارج از سرویس'
        ];

        return [
            'bedCount' => 'required|integer',
            'pricePerNight' => 'required|numeric|min:0',
            'number' => 'required|string', // should be unique in rooms table! google/chatgpt it
            'capacity' => 'nullable|integer|min:1',
            'floor' => 'nullable|integer',
            'size' => 'nullable|numeric|min:0',
            'views.*' => 'nullable|string',
            'descriptions.*' => 'nullable|string',
            'selectedCategory' => 'required|integer|exists:room_categories,id',
            'selectedAmenities' => 'nullable|array',
            'selectedAmenities.*' => 'integer|exists:amenities,id',
        ];
    }


    public function toggleAvailability()
    {
        $this->isAvailable = !$this->isAvailable;
    }

    public function removeImage($index)
    {
        unset($this->paths[$index]);
        $this->oldDocs = array_values($this->paths);
        MediaManagementService::removeMedia($this->paths[$index]);
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
            $this->room->images()->where('id', $oldUpload['id'])->delete();
        }
    }

    #[On('categorySelectize')]
    public function categorySelectize($values)
    {
        $this->selectedCategory = (int) $values;
        //dd($this->selectedCategory);
    }

    #[On('amenitySelectize')]
    public function amenitySelectize($values)
    {
        $this->selectedAmenities = $values;
        //dd($this->selectedAmenities);
    }

    public function mount($id)
    {
        $this->authorize('room-edit');
        $this->room = Room::findOrFail($id);

        $this->languages = LanguageManagementService::getLanguages();

        foreach ($this->languages['data'] as $lang) {
            $translation = Translation::where('model_id', $id)
                ->where('language_id', $lang['id'])
                ->where('model_type', Room::class)
                ->first();
            $this->descriptions["description_" . $lang['code']] = $translation ? $translation->description : '';
            $this->views["view_" . $lang['code']] = $translation ? $translation->view : '';
        }
        $images = $this->room->images->map(function ($image) {
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

        $this->selectedCategory = $this->room->room_category_id;
        $this->bedCount = $this->room->bed_count;
        $this->size = $this->room->size;
        $this->floor = $this->room->floor;
        $this->number = $this->room->number;
        $this->capacity = $this->room->capacity;
        $this->pricePerNight = $this->room->price_per_night;
        $this->isAvailable = $this->room->is_available;

        $this->selectedAmenities = $this->room->amenities->pluck('id')->toArray();

        $this->language = Language::where('code', app()->getLocale())->first()->id;
        $language = $this->language;
        $this->categoryOptions = RoomCategory::whereHas('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->get('id')
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => optional($category->translations->first())->name ?? 'Unnamed',
                ];
            })
            ->toArray();;

        $this->amenityOptions = Amenity::whereHas('translations', function ($query) use ($language) {
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

    public function update()
    {
        $this->resetErrorBag();
        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();
        $this->dispatch('saved');
        DB::beginTransaction();

        try {
            $room = Room::findOrFail($this->room->id);

            $room->capacity = $validatedData['capacity'];
            $room->room_category_id = $validatedData['selectedCategory']; // -->undefined array key roomCategory
            $room->number = $validatedData['number'];
            $room->floor = $validatedData['floor'];
            $room->price_per_night = $validatedData['pricePerNight'];
            $room->size = $validatedData['size'];
            $room->bed_count = $validatedData['bedCount'];
            $room->is_available = $this->isAvailable ? 1 : 0; // if its true its available, set 1, otherwise, 0.
            $room->save();

            if (!empty($this->selectedAmenities)) {
                $room->amenities()->sync($validatedData['selectedAmenities']);
            } else {
                $room->amenities()->detach();
            }

            if ($this->photos) {
                //$flatPhotos = is_array($this->photos[0]) ? Arr::flatten($this->photos) : $this->photos;
                foreach ($this->photos as $photo) {
                    $photo = TemporaryUploadedFile::createFromLivewire($photo['tmpFilename']);
                    $image = MediaManagementService::uploadMedia(
                        $photo,
                        '/rooms',
                        env('FILESYSTEM_DRIVER'),
                        explode('.', $photo->getClientOriginalName())[0] . '_' . time() . rand(0, 999999999999) . '.' . $photo->getClientOriginalExtension()
                    );
                    $room->images()->create([
                        'path' => $image,
                    ]);
                }
            }

            $this->deleteOldUploads($this->removedPhotos);


            foreach ($this->languages['data'] as $value) {
                $room->translations()->updateOrCreate(
                    [
                        'language_id' => $value['id'],
                        'model_type' => Room::class,
                    ],
                    [
                        'description' => $validatedData['descriptions']['description_' . $value['code']],
                        'view' => $validatedData['views']['view_' . $value['code']]
                    ]
                );
            }

            DB::commit();

            return redirect()->route('rooms')->with('success', __('messages.section updated successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', 'An error occurred while updating the Section.');
        }
    }
    public function render()
    {
        return view('livewire.rooms.room-edit');
    }
}
