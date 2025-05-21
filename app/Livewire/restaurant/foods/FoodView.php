<?php

namespace App\Livewire\restaurant\foods;

use App\Models\Language;
use App\Models\Food;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;


class FoodView extends Component
{
    use AuthorizesRequests, WithPagination;

    protected $foods;
    public $language;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $searchableFields = ['name'];

    public function mount()
    {
        $this->authorize('food-list');
        $this->language = Language::where('code', app()->getLocale())->first()->id;
    }

    #[On('destroy')]
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $food = Food::find($id);

            if ($food) {
                $food->delete();
            }

            DB::commit();

            return redirect()->route('foods')
                ->with('success', __('messages.section deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('foods')
                ->with('error', __('An error occurred while deleting the section.'));
        }
    }



    public function render()
    {
        $language = $this->language;

        // $this->rooms = Room::whereHas('translations', function ($query) use ($language) {
        //     $query->where('language_id', $language);
        // })->with('translations', function ($query) use ($language) {
        //     $query->where('language_id', $language);
        // })->with(['category.translations' => function ($query) use ($language) {
        //     $query->where('language_id', $language);
        // }])->with(['amenities.translations' => function ($query) use ($language) {
        //     $query->where('language_id', $language);
        // }])
        //     ->searchMany($this->searchableFields, $this->search);


        $this->foods = Food::whereHas('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with(['menu.translations' => function ($query) use ($language) {
            $query->where('language_id', $language);
        }])
            ->searchMany($this->searchableFields, $this->search);
        return view('livewire.restaurant.foods.food-view', ['foods' => $this->foods]);
    }
}
