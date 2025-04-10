<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Turma') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('classes.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Nome" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="grade" value="Série" />
                            <x-text-input id="grade" name="grade" type="text" class="mt-1 block w-full" :value="old('grade')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('grade')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                            <a href="{{ route('classes.index') }}" class="btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 