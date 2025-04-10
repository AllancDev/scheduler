<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendário') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Calendário Semanal</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Visualize os horários da semana</p>
                    </div>
                </div>

                <div class="border-t border-gray-200">
                    <div class="overflow-x-auto">
                        <div class="min-w-full">
                            <div class="grid grid-cols-6 bg-gray-50">
                                <div class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Horário
                                </div>
                                <div class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Segunda
                                </div>
                                <div class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Terça
                                </div>
                                <div class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quarta
                                </div>
                                <div class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quinta
                                </div>
                                <div class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sexta
                                </div>
                            </div>

                            <div class="divide-y divide-gray-200">
                                @foreach($timeSlots as $timeSlot)
                                <div class="grid grid-cols-6">
                                    <div class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ $timeSlot['start'] }} - {{ $timeSlot['end'] }}
                                    </div>
                                    @foreach(range(1, 5) as $day)
                                        <div class="py-4 px-4">
                                            @if(isset($schedules[$day][$timeSlot['start']]))
                                                <div class="bg-indigo-50 p-2 rounded-lg">
                                                    <p class="text-sm font-medium text-indigo-700">
                                                        {{ $schedules[$day][$timeSlot['start']]['subject'] }}
                                                    </p>
                                                    <p class="text-xs text-indigo-500">
                                                        {{ $schedules[$day][$timeSlot['start']]['teacher'] }}
                                                    </p>
                                                    <p class="text-xs text-indigo-400">
                                                        Sala {{ $schedules[$day][$timeSlot['start']]['room'] }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 