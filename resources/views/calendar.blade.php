<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendário') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex flex-col space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Calendário Semanal</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Visualize os horários da semana</p>
                            </div>
                        </div>
                        
                        <form method="GET" action="{{ route('calendar.index') }}" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[200px]">
                                <label for="class_id" class="block text-sm font-medium text-gray-700">Turma</label>
                                <select id="class_id" name="class_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todas as Turmas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label for="teacher_id" class="block text-sm font-medium text-gray-700">Professor</label>
                                <select id="teacher_id" name="teacher_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos os Professores</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label for="subject_id" class="block text-sm font-medium text-gray-700">Disciplina</label>
                                <select id="subject_id" name="subject_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todas as Disciplinas</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Filtrar
                                </button>
                                @if(request()->hasAny(['class_id', 'teacher_id', 'subject_id']))
                                    <a href="{{ route('calendar.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Limpar
                                    </a>
                                @endif
                            </div>
                        </form>
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
                                <div class="grid grid-cols-6 hover:bg-gray-50">
                                    <div class="py-3 px-4 text-sm font-medium text-gray-900 whitespace-nowrap border-r bg-gray-50">
                                        {{ $timeSlot['start'] }}
                                    </div>
                                    @foreach(range(1, 5) as $day)
                                        <div class="p-2 border-r min-h-[60px] relative">
                                            @if(isset($schedules[$day]) && isset($schedules[$day][$timeSlot['start']]))
                                                <div class="absolute inset-1">
                                                    <div class="h-full w-full rounded-lg border shadow-sm overflow-hidden" style="border-color: {{ $schedules[$day][$timeSlot['start']]['teacher_color'] }}40">
                                                        <div class="px-2 py-1" style="background-color: {{ $schedules[$day][$timeSlot['start']]['teacher_color'] }}10">
                                                            <div class="text-xs font-medium" style="color: {{ $schedules[$day][$timeSlot['start']]['teacher_color'] }}">
                                                                {{ $schedules[$day][$timeSlot['start']]['teacher'] }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 truncate">
                                                                {{ $schedules[$day][$timeSlot['start']]['subject'] }} - Sala {{ $schedules[$day][$timeSlot['start']]['room'] }}
                                                            </div>
                                                        </div>
                                                    </div>
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