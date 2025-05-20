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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoomAdd extends Component
{
    use AuthorizesRequests, WithFileUploads;

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
    // room is available by default
    public $isAvailable = 1;

    public $selectedCategory;
    public $categoryOptions;
    public $amenityOptions;
    public $selectedAmenities;
    public $language;

    public $photos = [];


    public function toggleAvailability()
    {
        $this->isAvailable = !$this->isAvailable;
    }
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

    public function mount()
    {
        //$this->authorize('room-create');
        $this->languages = LanguageManagementService::getLanguages();
        foreach ($this->languages['data'] as $lang) {
            $this->statuses["status_" . $lang['code']] = '';
            $this->views["view_" . $lang['code']] = '';
            $this->descriptions["description" . $lang['code']] = '';
        }
    }

    public function removeImage($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
        $this->dispatch('resetFileInput', $index);
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
        $this->dispatch('$refresh');
    }

    public function store()
    {

        $this->resetErrorBag();

        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();

        $this->dispatch('saved');

        DB::beginTransaction();

        try {
            $room = new Room();
            $room->capacity = $validatedData['capacity'];
            $room->room_category_id = $validatedData['selectedCategory']; // -->undefined array key roomCategory
            $room->number = $validatedData['number'];
            $room->floor = $validatedData['floor'];
            $room->price_per_night = $validatedData['pricePerNight'];
            $room->size = $validatedData['size'];
            $room->bed_count = $validatedData['bedCount'];
            $room->is_available = $this->isAvailable ? 1 : 0; // if its true its available, set 1, otherwise, 0.
            $room->save();

            // dd([
            //     'From $validatedData' => $validatedData['selectedAmenities'],
            //     'From $this' => $this->selectedAmenities
            // ]);
            $room->amenities()->sync($validatedData['selectedAmenities']);

            if ($this->photos) {
                //$flatPhotos = is_array($this->photos[0]) ? Arr::flatten($this->photos) : $this->photos;
                foreach ($this->photos as $photo) {
                    $photo = TemporaryUploadedFile::createFromLivewire($photo['tmpFilename']);
                    //dump($photo);
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

            foreach ($this->languages['data'] as $value) {

                $room->translations()->create([
                    'language_id' => $value['id'],
                    'view' => $validatedData['views']['view_' . $value['code']],
                    'description' => $validatedData['descriptions']['description_' . $value['code']],
                    'model_type' => Room::class,
                ]);
            }
            DB::commit();

            return redirect()->route('rooms')->with('success',  __('messages.room created successfully'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the Room.');
        }
    }

    public function render()
    {
        $this->language = Language::where('code', app()->getLocale())->first()->id;
        $language = $this->language;
        $this->categoryOptions = RoomCategory::whereHas('translations', function ($query) use ($language) {
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
        return view('livewire.rooms.room-add');
    }
}
