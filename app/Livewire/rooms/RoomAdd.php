<?php

namespace App\Livewire\rooms;

use App\Models\Language;
use App\Models\Room;
use App\Models\RoomCategory;
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
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoomAdd extends Component
{
    use AuthorizesRequests, WithPagination;

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
            'number' => 'required|string',
            'capacity' => 'nullable|integer|min:1',
            'floor' => 'nullable|integer',
            'size' => 'nullable|numeric|min:0',
            'views.*' => 'nullable|string',
            'descriptions.*' => 'nullable|string',
            'selectedCategory' => 'required|integer|exists:room_categories,id',
            'selectedAmenities' => 'array|integer|exists:amenities,id',
            'paths.*' => 'nullable|array|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB per file
        ];
    }

    public function mount()
    {
        $this->authorize('room-create');
        $this->languages = LanguageManagementService::getLanguages();
        foreach ($this->languages['data'] as $lang) {
            $this->statuses["status_" . $lang['code']] = '';
            $this->views["view_" . $lang['code']] = '';
            $this->descriptions["description" . $lang['code']] = '';
        }
    }

    #[On('categorySelectize')]
    public function categorySelectize($values)
    {
        $this->selectedCategory = $values;
        dd($this->selectedCategory);
    }

    #[On('amenitySelectize')]
    public function materialSelectize($values)
    {
        $this->selectedAmenities = $values;
        dd($this->selectedAmenities);
    }

    public function store()
    {

        dd($this->selectedCategory);
        dd($this->selectedAmenities);


        $this->resetErrorBag();

        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();

        $this->dispatch('saved');

        DB::beginTransaction();

        try {
            $room = new Room();
            $room->capacity = $validatedData['capacity'];
            $room->room_category = $validatedData['roomCategory'];
            $room->number = $validatedData['number'];
            $room->floor = $validatedData['floor'];
            $room->price_per_night = $validatedData['pricePerNight'];
            $room->size = $validatedData['size'];
            $room->is_available = 1;
            $room->save();

            if ($this->paths) {
                foreach ($this->paths as $path) {
                    $image = MediaManagementService::uploadMedia(
                        $this->path,
                        '/rooms',
                        env('FILESYSTEM_DRIVER'),
                        explode('.', $this->path->getClientOriginalName())[0] . '_' . time() . rand(0, 999999999999) . '.' . $this->path->getClientOriginalExtension()
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
