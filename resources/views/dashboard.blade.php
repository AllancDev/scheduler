<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <a href="{{ route('teachers.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Professores</h5>
                            <p class="font-normal text-gray-700">Gerencie os professores da instituição.</p>
                        </a>

                        <a href="{{ route('subjects.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Matérias</h5>
                            <p class="font-normal text-gray-700">Gerencie as matérias lecionadas.</p>
                        </a>

                        <a href="{{ route('classes.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Turmas</h5>
                            <p class="font-normal text-gray-700">Gerencie as turmas e seus horários.</p>
                        </a>

                        <a href="{{ route('calendar') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Calendário</h5>
                            <p class="font-normal text-gray-700">Visualize o calendário de aulas.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
