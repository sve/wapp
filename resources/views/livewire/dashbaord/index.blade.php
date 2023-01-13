<div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-4">
        <x-button primary label="{{ __('wapp.create') }}" wire:click="$emit('openModal', 'location.create')"  />
    </div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-4">
        @foreach($locations as $location)
            <div class="mt-4 mb-4">
                <x-card title="{{ $location->name }}">
                    <x-slot name="action">
                        <button wire:click="remove({{ $location }})" class="rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                onclick="confirm('Are you sure you want to remove the user from this group?') || event.stopImmediatePropagation()"
                            >
                            <x-icon name="trash" class="w-4 h-4 text-gray-500" />
                        </button>
                    </x-slot>

                    @foreach ($location->weather as $option => $item)
                        @if (is_array($item))
                            {{ $option }} :
                            @foreach ($item as $key => $value)
                                <p>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    {{ $key }} :
                                    @if (is_array($value))
                                        {{ var_dump($value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </p>
                            @endforeach
                        @else
                            <p>{{ $option }} : {{ $item }}</p>
                        @endif
                    @endforeach
                </x-card>
            </div>
        @endforeach
    </div>

</div>
