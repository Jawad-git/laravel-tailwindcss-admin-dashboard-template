<?php

namespace App\Livewire\rooms;

use App\Models\Language;
use App\Models\Room;
use App\Models\RoomCategory;
use Livewire\Attributes\On;
use App\Models\Amenity;
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

    public $paths;
    public $selectedCategory;
    public $categoryOptions;
    public $amenityOptions;
    public $selectedAmenities;
    public $language;

    public $fileCount = 0;




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


    public function removeImage($index)
    {
        unset($this->paths[$index]);
        $this->paths = array_values($this->paths);
        MediaManagementService::removeMedia($this->paths[$index]);
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
        // this->authorize room-edit/update whatever
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
        $this->paths = $this->room->images->pluck('path')->toArray();
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

            foreach ($room->images as $image) {
                MediaManagementService::removeMedia($image->path);
                $image->delete();
            }

            if ($this->paths) {
                foreach ($this->paths as $path) {
                    $image;
                    if (!is_string(value: $this->path)) {
                        $image = MediaManagementService::uploadMedia(
                            $path,
                            '/rooms',
                            env('FILESYSTEM_DRIVER'),
                            explode('.', $path->getClientOriginalName())[0] . '_' . time() . rand(0, 999999999999) . '.' . $path->getClientOriginalExtension()
                        );
                    } else {
                        $image = $this->path;
                    }
                    if (!$room->images()->where('path', $image)->exists()) {
                        $room->images()->create([
                            'path' => $image,
                        ]);
                    }
                }
            }


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
