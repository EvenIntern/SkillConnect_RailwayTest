<x-app-layout>
    <x-slot name="title">
        {{ __('Create Opportunity') }}
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @include('projects.create')
        </div>
    </div>
</x-app-layout>
