<?php

namespace App\Livewire\roomCategories;

use App\Models\Language;
use App\Models\RoomCategory;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;


class CategoryView extends Component
{
    use AuthorizesRequests, WithPagination;

    protected $categories;
    public $language;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $searchableFields = ['name'];

    public function mount()
    {
        $this->authorize('category-list');
        $this->language = Language::where('code', app()->getLocale())->first()->id;
    }

    #[On('destroy')]
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $category = RoomCategory::find($id);

            if ($category) {
                $category->delete();
            }

            DB::commit();

            return redirect()->route('categories')
                ->with('success', __('messages.section deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories')
                ->with('error', __('An error occurred while deleting the section.'));
        }
    }



    public function render()
    {
        $language = $this->language;

        $this->categories = RoomCategory::whereHas('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })->with('translations', function ($query) use ($language) {
            $query->where('language_id', $language);
        })
            ->searchMany($this->searchableFields, $this->search);
        return view('livewire.roomCategories.category-view', ['categories' => $this->categories]);
    }
}
