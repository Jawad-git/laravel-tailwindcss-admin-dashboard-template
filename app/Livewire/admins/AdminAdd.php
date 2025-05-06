<?php

namespace App\Livewire\admins;

use App\Models\User;
use App\Models\Country;
use App\Services\MediaManagementService;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class AdminAdd extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $notBooted = true;

    public $roles;

    public $selectedrole;
    public $name;

    public $phone;
    public $email;
    public $password;
    public $password_confirmation;
    public $expiry_date;
    public $address;
    public $countries;
    public $countryIdField;
    public $selectedCountry = 121;
    public $path;
    protected $listeners = [
        'cardLoaded',
    ];

    protected $rules = [
        'name' => 'required',
        'phone' => 'required',
        'address' => 'nullable',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|required_with:password_confirmation|same:password_confirmation',
        'password_confirmation' => 'required|required_with:password|same:password',
        'selectedrole' => 'required',
    ];

    public function mount()
    {
        $this->authorize('admin-create');
        $this->countries = Country::with('flag')->where('status', 1)->orderBy('name')->get()->map(function ($country) {
            return [
                'id' => $country->id,
                'phonecode' => $country->phonecode,
                'flag' => $country->flag ? $country->flag->image : 'default.png',
                'name' => $country->name,
            ];
        })->toArray();

        if (auth()->user()->hasRole(1)) {
            $this->roles = Role::whereNotIn('id', [2])->pluck('name', 'id');
        } else {
            $this->roles = Role::whereNotIn('id', [1, 2, 3])->pluck('name', 'id');
        }
    }

    public function cardLoaded($isBooted)
    {
        $this->notBooted = $isBooted;
    }
    public function removeImage()
    {
        $this->path = null;
    }
    public function store()
    {
        $this->dispatch('scrollToElement');

        $validatedData = $this->validate($this->rules);

        $this->dispatch('saved');

        DB::beginTransaction();

        try {
            // if ($this->path) {

            //     $path = MediaManagementService::uploadMedia(
            //         $this->path,
            //         '/admins',
            //         env('FILESYSTEM_DRIVER'),
            //         explode('.', $this->path->getClientOriginalName())[0] . '_' . time() . rand(0, 999999999999) . '.' . $this->path->getClientOriginalExtension()
            //     );
            // } else {
            //     $path = null;
            // }
            $path = null;
            $user = new User();
            $user->name = $validatedData['name'];
            $user->phone = $validatedData['phone'];
            $user->email = $validatedData['email'];
            $user->password = $validatedData['password'];
            $user->address = $validatedData['address'];
            $user->phone_code = $this->selectedCountry;
            $user->avatar = $path;
            $user->save();

            $role = Role::find($validatedData['selectedrole']);
            $user->assignRole($role->name);

            DB::commit();

            return redirect()->route('admins')->with('success',  'تم انشاء المشرف بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the admin.');
        }
    }

    public function render()
    {
        return view('livewire.admins.admin-add');
    }
}
