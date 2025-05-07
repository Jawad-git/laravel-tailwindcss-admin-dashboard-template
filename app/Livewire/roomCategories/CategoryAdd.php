<?php

namespace App\Http\Livewire\Backend\Sections;

use App\Models\Sections;
use App\Models\SectionsDetails;
use App\Services\LanguageManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SectionAdd extends Component
{
    use AuthorizesRequests;

    public $languages;
    public $name;
    public $pages;

    protected function rules()
    {
        $rules = [
            'name.*' => 'required|string',
            'pages' => 'nullable',
            'pages.*' => 'array',
        ];
        foreach ($this->languages['data'] as $lang) {
            $rules["pages.*.name_{$lang['code']}"] = 'required|string';
        }
        return $rules;
    }

    public function mount()
    {
        $this->authorize('section-create');
        $this->languages = LanguageManagementService::getLanguages();
        foreach ($this->languages['data'] as $lang) {
            $this->name["name_" . $lang['code']] = '';
        }
        $this->addPage();
    }
    public function removePage($index)
    {
        unset($this->pages[$index]);
        $this->pages = array_values($this->pages);
    }
    public function addPage()
    {
        $pageEntry = [];

        foreach ($this->languages['data'] as $lang) {
            $pageEntry["name_" . $lang['code']] = '';
        }

        $this->pages[] = $pageEntry;
    }

    public function store()
    {
        $this->resetErrorBag();

        $this->dispatch('scrollToElement');

        $validatedData = $this->validate();

        $this->dispatch('saved');

        DB::beginTransaction();

        try {


            $section = new Sections();
            $section->save();


            foreach ($this->languages['data'] as $value) {

                $sectionDetail = new SectionsDetails();
                $sectionDetail->section_id = $section->id;
                $sectionDetail->language_id = $value['id'];
                $sectionDetail->name = $validatedData['name']['name_' . $value['code']];
                $sectionDetail->save();
            }
            $permissionName = $validatedData['name']['name_en'] . '-list';
            $permission = Permission::firstOrCreate(['name' => $permissionName]);

            $roles = Role::whereIn('name', ['Super Admin', 'American Board'])->get();
            foreach ($roles as $role) {
                $role->givePermissionTo($permission);
            }
            if (empty($this->pages)) {
                $baseName = $validatedData['name']['name_en'];
                $permissionActions = ['add', 'edit', 'delete'];

                foreach ($permissionActions as $action) {
                    $permissionName = "{$baseName}-{$action}";
                    $permission = Permission::firstOrCreate(['name' => $permissionName]);

                    foreach ($roles as $role) {
                        $role->givePermissionTo($permission);
                    }
                }
            } else {

                foreach ($this->pages as $page) {
                    $subSection = new Sections();
                    $subSection->parent_id = $section->id;
                    $subSection->save();

                    foreach ($this->languages['data'] as $value) {
                        $subSectionDetail = new SectionsDetails();
                        $subSectionDetail->section_id = $subSection->id;
                        $subSectionDetail->language_id = $value['id'];
                        $subSectionDetail->name = $page['name_' . $value['code']];
                        $subSectionDetail->save();
                    }

                    $baseName = $page['name_en'];
                    $permissionActions = ['list', 'add', 'edit', 'delete'];

                    foreach ($permissionActions as $action) {
                        $permissionName = "{$baseName}-{$action}";
                        $permission = Permission::firstOrCreate(['name' => $permissionName]);

                        foreach ($roles as $role) {
                            $role->givePermissionTo($permission);
                        }
                    }
                }
            }
            DB::commit();

            return redirect()->route('sections')->with('success',  __('messages.section created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the Section.');
        }
    }

    public function render()
    {
        return view('livewire.backend.sections.section-add');
    }
}
