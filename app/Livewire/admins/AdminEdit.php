<?php

namespace App\Livewire\admins;

use App\Models\User;
use App\Models\Country;
use App\Services\MediaManagementService;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class AdminEdit extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $user;
    public $roles;
    public $notBooted = true;

    public $selectedrole;
    public $name;
    public $phone;
    public $email;
    public $password;
    public $address;
    public $password_confirmation;
    public $expiry_date;
    public $countries;
    public $countryIdField;
    public $selectedCountry;
    protected $listeners = [
        'cardLoaded',
    ];

    public function mount($id)
    {
        $this->user = User::find($id);

        $this->authorize('admin-edit');
        $this->authorize('edit', $this->user);

        $this->roles = Role::pluck('name', 'id')->all();
        $this->name = $this->user->name;
        $this->phone = $this->user->phone;
        $this->email = $this->user->email;
        $this->address = $this->user->address;
        $this->selectedCountry = $this->user->phone_code;

        $this->countries = Country::with('flag')->where('status', 1)->orderBy('name')->get()->map(function ($country) {
            return [
                'id' => $country->id,
                'phonecode' => $country->phonecode,
                'flag' => $country->flag ? $country->flag->image : 'default.png',
                'name' => $country->name,
            ];
        })->toArray();
        // $this->expiry_date = $this->user->expiry_date;
        $this->selectedrole = count($this->user->roles) > 0 ? Role::where('name', $this->user->getRoleNames()[0])->first()->id : null;
    }


    public function cardLoaded($isBooted)
    {
        $this->notBooted = $isBooted;
    }

    public function store()
    {
        //$this->authorize('update', $this->user);

        $this->dispatch('scrollToElement');

        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'selectedrole' => 'required',

        ];

        if (!empty($this->password) || !empty($this->password_confirmation)) {
            $rules['password'] = 'required|required_with:password_confirmation|same:password_confirmation';
            $rules['password_confirmation'] = 'required|required_with:password|same:password';
        }

        $validatedData = $this->validate($rules);

        $this->dispatch('saved');
        DB::beginTransaction();

        try {

            if (isset($validatedData['password'])) {
                $this->user->password = $validatedData['password'];
            }


            // try{
            $this->user->name = $validatedData['name'];
            $this->user->phone = $validatedData['phone'];
            $this->user->email = $validatedData['email'];
            $this->user->address = $validatedData['address'];
            // $this->user->expiry_date = $validatedData['expiry_date'];
            // }catch  (\Exception $e){
            //     dd($e);
            // }


            // $this->user->syncRoles([]);

            $this->user->save();

            if ($validatedData['selectedrole']) {

                $role = Role::find($validatedData['selectedrole']);
                $this->user->assignRole($role->name);
            }

            DB::commit();

            return redirect()->route('admins')->with('success',  'تم تحديث المشرف بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('scrollToElement');

            session()->flash('error', 'An error occurred while creating the admin.');
        }
    }

    public function render()
    {
        return view('livewire.admins.admin-edit');
    }
}
