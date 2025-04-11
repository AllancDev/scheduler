<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Horário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('classes.schedules.store', $class) }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="subject_id" value="Matéria" />
                                <select id="subject_id" name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition" required>
                                    <option value="">Selecione uma matéria</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('subject_id')" />
                            </div>

                            <div>
                                <x-input-label for="teacher_id" value="Professor" />
                                <select id="teacher_id" name="teacher_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition" required>
                                    <option value="">Selecione um professor</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('teacher_id')" />
                            </div>

                            <div>
                                <x-input-label for="room" value="Sala" />
                                <x-text-input id="room" name="room" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition" :value="old('room')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('room')" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="start_time" value="Horário de Início" />
                                    <x-text-input id="start_time" name="start_time" type="time" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition" :value="old('start_time')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                                </div>

                                <div>
                                    <x-input-label for="end_time" value="Horário de Término" />
                                    <x-text-input id="end_time" name="end_time" type="time" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition" :value="old('end_time')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label value="Dias da Semana" />
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-5 gap-4">
                                <label class="inline-flex items-center bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition cursor-pointer">
                                    <input type="checkbox" name="days[]" value="monday" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array('monday', old('days', [])) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ \App\Helpers\DateHelper::translateDayOfWeek('monday') }}</span>
                                </label>
                                <label class="inline-flex items-center bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition cursor-pointer">
                                    <input type="checkbox" name="days[]" value="tuesday" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array('tuesday', old('days', [])) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ \App\Helpers\DateHelper::translateDayOfWeek('tuesday') }}</span>
                                </label>
                                <label class="inline-flex items-center bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition cursor-pointer">
                                    <input type="checkbox" name="days[]" value="wednesday" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array('wednesday', old('days', [])) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ \App\Helpers\DateHelper::translateDayOfWeek('wednesday') }}</span>
                                </label>
                                <label class="inline-flex items-center bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition cursor-pointer">
                                    <input type="checkbox" name="days[]" value="thursday" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array('thursday', old('days', [])) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ \App\Helpers\DateHelper::translateDayOfWeek('thursday') }}</span>
                                </label>
                                <label class="inline-flex items-center bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition cursor-pointer">
                                    <input type="checkbox" name="days[]" value="friday" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array('friday', old('days', [])) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ \App\Helpers\DateHelper::translateDayOfWeek('friday') }}</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('days')" />
                        </div>

                        <div class="mt-6">
                            <label class="inline-flex items-center bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="is_substitute" id="is_substitute" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_substitute') ? 'checked' : '' }}>
                                <span class="ml-2">É substituição?</span>
                            </label>
                        </div>

                        <div id="original_teacher_div" class="{{ old('is_substitute') ? '' : 'hidden' }} mt-6">
                            <x-input-label for="original_teacher_id" value="Professor Original" />
                            <select id="original_teacher_id" name="original_teacher_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition">
                                <option value="">Selecione o professor original</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('original_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('original_teacher_id')" />
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                {{ __('Salvar') }}
                            </x-primary-button>
                            <a href="{{ route('classes.schedules', $class) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2">
                                    <path d="M18 6 6 18"/>
                                    <path d="m6 6 12 12"/>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('is_substitute').addEventListener('change', function() {
            const originalTeacherDiv = document.getElementById('original_teacher_div');
            if (this.checked) {
                originalTeacherDiv.classList.remove('hidden');
            } else {
                originalTeacherDiv.classList.add('hidden');
            }
        });

        // Mostrar o campo de professor original se houver erro de validação
        if (document.getElementById('is_substitute').checked) {
            document.getElementById('original_teacher_div').classList.remove('hidden');
        }
    </script>
</x-app-layout> 