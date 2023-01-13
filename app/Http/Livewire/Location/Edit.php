<?php

namespace App\Http\Livewire\Location;

use LivewireUI\Modal\ModalComponent;

class Edit extends ModalComponent
{
    public $location;
    public $name;

    public function edit()
    {
        $this->validate([
            'name' => 'string|required',
        ]);

        $this->location->name = $this->name;
        $this->location->save();

        $this->closeModalWithEvents(['updateLocationsList']);

        $this->alert(
            'success',
            __('wapp.edited')
        );
    }

    public function mount(Location $location)
    {
        if(auth()->user()->id != $location->user_id) {
            abort(400);
        }

        $this->location = $location;
        $this->name = $location->name;
    }

    public function render()
    {
        return view('livewire.location.edit');
    }
}
