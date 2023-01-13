<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-4">
    <x-button primary label="{{ __('wapp.create') }}" wire:click="$emit('openModal', 'location.create')"  />
</div>

<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-4">
    @foreach($locations as $location)
        <div class="mt-4 mb-4">
            <x-card title="{{ $location->name }}">
                <x-slot name="action">
                    <button wire:click="remove({{ $location }})" class="rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <x-icon name="trash" class="w-4 h-4 text-gray-500" />
                    </button>
                </x-slot>
                {{ __('wapp.location.name') }}:{{ $location->name }}
            </x-card>
        </div>
    @endforeach

    {{ $locations->links() }}
</div>
