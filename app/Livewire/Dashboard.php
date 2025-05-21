<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\User;

use Livewire\Component;

class Dashboard extends Component
{
    public $totalRooms;
    public $availableRooms;
    public $totalUsers;
    public $totalAmenities;


    public function render()
    {
        return view('livewire.dashboard');
    }

    public function mount()
    {
        $this->totalRooms = Room::count();
        $this->totalUsers = User::count();
        $this->totalAmenities = User::count();
        $this->availableRooms = Room::where('is_available', 1)->orWhere('is_available', true)->count();
    }
}
