<?php

namespace App\Livewire\rooms;

use App\Models\Language;
use App\Models\Room;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;


class RoomView extends Component
{
    use AuthorizesRequests, WithPagination;

    protected $rooms;
    public $language;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $searchableFields = ['number'];

    public function mount()
    {
        $this->authorize('room-list');
        $this->language = Language::where('code', app()->getLocale())->first()->id;
    }

    #[On('destroy')]
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $room = Room::find($id);

            if ($room) {
                $room->delete();
            }

            DB::commit();

            return redirect()->route('rooms')
                ->with('success', __('messages.section deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('rooms')
                ->with('error', __('An error occurred while deleting the section.'));
        }
    }



    public function render()
    {
        $language = $this->language;

        $this->rooms = Room::whereHas('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with(['category.translations' => function ($query) use ($language) {
            $query->where('language_id', $language);
        }])->with(['amenities.translations' => function ($query) use ($language) {
                $query->where('language_id', $language);
        }])
        ->searchMany($this->searchableFields, $this->search);

        return view('livewire.rooms.room-view', ['rooms' => $this->rooms]);
    }
}
