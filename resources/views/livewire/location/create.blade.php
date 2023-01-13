<div>
    <x-card title="{{ __('wapp.add_location') }}">
        <x-input label="{{ __('wapp.location.name') }} *" placeholder="{{ __('wapp.location.name') }}" wire:model="name" />

        <x-slot name="footer">
            <div class="flex justify-between items-center">
                <x-button label="{{ __('wapp.save') }}" wire:click="create" primary />
            </div>
        </x-slot>
    </x-card>

</div>
