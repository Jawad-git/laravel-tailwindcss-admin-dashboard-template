<?php

namespace App\Livewire\amenities;

use App\Models\Language;
use App\Models\Amenity;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;


class AmenityView extends Component
{
    use AuthorizesRequests, WithPagination;

    protected $amenities;
    public $language;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $searchableFields = ['name'];

    public function mount()
    {
        //$this->authorize('amenity-list');
        $this->language = Language::where('code', app()->getLocale())->first()->id;
    }

    #[On('destroy')]
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $category = Amenity::find($id);

            if ($category) {
                $category->delete();
            }

            DB::commit();

            return redirect()->route('amenities')
                ->with('success', __('messages.section deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('amenities')
                ->with('error', __('An error occurred while deleting the section.'));
        }
    }



    public function render()
    {
        $language = $this->language;

        $this->amenities = Amenity::whereHas('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })
            ->searchMany($this->searchableFields, $this->search);
        return view('livewire.amenities.amenity-view', ['amenities' => $this->amenities]);
    }
}
