@extends('layouts.app')

@section('title', 'Horários - ' . $subject->name)

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Horários da Matéria: {{ $subject->name }}</h3>
                        <button onclick="openModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                            Novo Horário
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sala</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Substituto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $schedule->room }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($schedule->day_of_week) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $schedule->teacher->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($schedule->is_substitute)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Sim ({{ $schedule->originalTeacher->name }})
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Não
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="editSchedule({{ $schedule->id }}, {{ $schedule->teacher_id }}, '{{ $schedule->room }}', '{{ $schedule->day_of_week }}', '{{ $schedule->start_time->format('H:i') }}', '{{ $schedule->end_time->format('H:i') }}', {{ $schedule->is_substitute ? 'true' : 'false' }}, {{ $schedule->original_teacher_id ?? 'null' }})" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                            <form action="{{ route('subjects.schedules.destroy', [$subject, $schedule]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Tem certeza que deseja excluir este horário?')">
                                                    Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="scheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modalTitle">Novo Horário</h3>
                <form id="scheduleForm" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="schedule_id" id="schedule_id">
                    
                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700">Professor</label>
                        <select name="teacher_id" id="teacher_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione um professor</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="room" class="block text-sm font-medium text-gray-700">Sala</label>
                        <input type="text" name="room" id="room" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Ex: A101">
                    </div>

                    <div>
                        <label for="day_of_week" class="block text-sm font-medium text-gray-700">Dia da Semana</label>
                        <select name="day_of_week" id="day_of_week" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione um dia</option>
                            <option value="segunda">Segunda-feira</option>
                            <option value="terca">Terça-feira</option>
                            <option value="quarta">Quarta-feira</option>
                            <option value="quinta">Quinta-feira</option>
                            <option value="sexta">Sexta-feira</option>
                            <option value="sabado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Horário de Início</label>
                            <input type="time" name="start_time" id="start_time" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">Horário de Término</label>
                            <input type="time" name="end_time" id="end_time" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_substitute" id="is_substitute" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_substitute" class="ml-2 block text-sm text-gray-900">
                            É substituto?
                        </label>
                    </div>

                    <div id="originalTeacherContainer" class="hidden">
                        <label for="original_teacher_id" class="block text-sm font-medium text-gray-700">Professor Original</label>
                        <select name="original_teacher_id" id="original_teacher_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione o professor original</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Novo Horário';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('scheduleForm').action = "{{ route('subjects.schedules.store', $subject) }}";
            document.getElementById('schedule_id').value = '';
            document.getElementById('teacher_id').value = '';
            document.getElementById('room').value = '';
            document.getElementById('day_of_week').value = '';
            document.getElementById('start_time').value = '';
            document.getElementById('end_time').value = '';
            document.getElementById('is_substitute').checked = false;
            document.getElementById('originalTeacherContainer').classList.add('hidden');
            document.getElementById('original_teacher_id').value = '';
        }

        function closeModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        function editSchedule(id, teacherId, room, dayOfWeek, startTime, endTime, isSubstitute, originalTeacherId) {
            document.getElementById('scheduleModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Editar Horário';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('scheduleForm').action = `/subjects/{{ $subject->id }}/schedules/${id}`;
            document.getElementById('schedule_id').value = id;
            document.getElementById('teacher_id').value = teacherId;
            document.getElementById('room').value = room;
            document.getElementById('day_of_week').value = dayOfWeek;
            document.getElementById('start_time').value = startTime;
            document.getElementById('end_time').value = endTime;
            document.getElementById('is_substitute').checked = isSubstitute;
            if (isSubstitute) {
                document.getElementById('originalTeacherContainer').classList.remove('hidden');
                document.getElementById('original_teacher_id').value = originalTeacherId;
            } else {
                document.getElementById('originalTeacherContainer').classList.add('hidden');
            }
        }

        document.getElementById('is_substitute').addEventListener('change', function() {
            const container = document.getElementById('originalTeacherContainer');
            if (this.checked) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });
    </script>
@endsection 