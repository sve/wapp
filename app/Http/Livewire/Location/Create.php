<?php

namespace App\Http\Livewire\Location;

use App\Models\UserLocation;
use LivewireUI\Modal\ModalComponent;

class Create extends ModalComponent
{
    public string $name;

    public function create()
    {
        $this->validate([
            'name' => 'string|required',
        ]);

        $location = new UserLocation();
        $location->name = $this->name;
        $location->user_id = auth()->user()->id;
        $location->save();

        $this->closeModalWithEvents(['updateLocationsList']);

        $this->dispatchBrowserEvent(
            'success',
            __('wapp.created')
        );
    }
    public function render()
    {
        return view('livewire.location.create');
    }
}
