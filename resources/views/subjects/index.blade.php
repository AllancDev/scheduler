@extends('layouts.app')

@section('title', 'Matérias')

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

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Lista de Matérias</h3>
                        <button onclick="openModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                            Nova Matéria
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subjects as $subject)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $subject->name }}</td>
                                        <td class="px-6 py-4">{{ $subject->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="editSubject({{ $subject->id }}, '{{ $subject->name }}', '{{ $subject->description }}')" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Tem certeza que deseja excluir esta matéria?')">
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
    <div id="subjectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modalTitle">Nova Matéria</h3>
                <form id="subjectForm" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="subject_id" id="subject_id">
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('subjectModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Nova Matéria';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('subjectForm').action = "{{ route('subjects.store') }}";
            document.getElementById('subject_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('description').value = '';
        }

        function closeModal() {
            document.getElementById('subjectModal').classList.add('hidden');
        }

        function editSubject(id, name, description) {
            document.getElementById('subjectModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Editar Matéria';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('subjectForm').action = `/subjects/${id}`;
            document.getElementById('subject_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('description').value = description;
        }
    </script>
@endsection 