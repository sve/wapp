<?php

namespace App\Http\Livewire\Location;

use App\Models\UserLocation;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $location;

    protected $listeners = [
        'confirmedRemove',
        'cancelledRemove',
        'updateLocationsList' => '$refresh',
    ];

    public function remove(UserLocation $location)
    {
        $location->delete();
    }

    public function confirmedRemove()
    {
        if(auth()->user()->id != $this->location->user_id) {
            abort(400);
        }

        $this->location->delete();

        $this->dispatchBrowserEvent(
            'success',
            __('wapp.done')
        );
    }

    public function cancelledRemove()
    {
        $this->dispatchBrowserEvent(
            'success',
            __('wapp.cancelled')
        );
    }

    public function render()
    {
        $locations = UserLocation::where('user_id', auth()->user()->id)->latest()->paginate(config('wapp.per_page'));
        return view('livewire.location.index', compact('locations'));
    }
}
