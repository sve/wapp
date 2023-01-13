<?php

namespace App\Http\Livewire\Dashbaord;

use App\Models\UserLocation;
use App\Services\WeatherService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * @var
     */
    public $location;

    /**
     * @var string[]
     */
    protected $listeners = [
        'confirmedDone',
        'cancelledDone',
        'confirmedRemove',
        'cancelledRemove',
        'updateLocationsList' => '$refresh',
    ];

    /**
     * @var WeatherService
     */
    protected WeatherService $weatherService;

    /**
     * @param UserLocation $location
     * @return void
     */
    public function done(UserLocation $location)
    {
        $this->location = $location;
    }

    /**
     * @return void
     */
    public function confirmedDone()
    {
        $this->dispatchBrowserEvent(
            'success',
            __('wapp.done')
        );
    }

    /**
     * @return void
     */
    public function cancelledDone()
    {
        $this->dispatchBrowserEvent(
            'success',
            __('wapp.cancelled')
        );
    }

    /**
     * @param UserLocation $location
     * @return void
     */
    public function remove(UserLocation $location)
    {
        $this->location = $location;
        $this->location->delete();
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function cancelledRemove()
    {
        $this->dispatchBrowserEvent(
            'success',
            __('wapp.cancelled')
        );
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $weatherService = resolve(WeatherService::class);
        $locations = UserLocation::with(['user'])
            ->where('user_id', auth()->user()->id)->latest()->paginate(config('wapp.per_page'));

        foreach ($locations as $location) {
            $location->weather = $weatherService->byUserLocation($location);
        }

        return view('livewire.dashbaord.index', compact('locations'));
    }
}
