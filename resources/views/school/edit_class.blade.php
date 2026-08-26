<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Edit Class Names</h2>
            <small class="text-muted">Manage classes under each activated grade</small>
        </div>
    </x-slot>

    <div class="py-4 bg-light min-vh-100">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('dashboard.school.update_class_names') }}" method="POST">
                @csrf

                <div class="row g-4">
                    @foreach($activatedGrades as $grade)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100" data-grade-id="{{ $grade->id }}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $grade->grade_name }}</h5>

                                    {{-- Existing Classes --}}
                                    <div class="mb-3 existing-classes">
                                        @foreach($grade->classes as $class)
                                            <div class="input-group mb-2 class-row">
                                                <input type="text"
                                                    name="class_name[{{ $class->id }}]"
                                                    value="{{ $class->class_name }}"
                                                    class="form-control form-control-sm"
                                                    placeholder="Class Name">
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="removeExistingClass(this, {{ $class->id }})">
                                                    ✕
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- New Classes --}}
                                    <div class="mb-2 new-classes"></div>

                                    {{-- Add New Class Button --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary mt-auto"
                                            onclick="addClassInput({{ $grade->id }})">
                                        + Add New Class
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Hidden container for deleted classes --}}
                <div id="deleted-classes"></div>

                {{-- Save Changes --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addClassInput(gradeId) {
            const container = document.querySelector('.card[data-grade-id="'+gradeId+'"] .new-classes') ||
                              document.querySelector('.grade-block[data-grade-id="'+gradeId+'"] .new-classes');

            const wrapper = document.createElement('div');
            wrapper.className = 'input-group mb-2';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'new_class['+gradeId+'][]';
            input.placeholder = 'New class name';
            input.className = 'form-control form-control-sm';

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-outline-danger btn-sm';
            button.innerHTML = '✕';
            button.onclick = function () { wrapper.remove(); };

            wrapper.appendChild(input);
            wrapper.appendChild(button);

            container.appendChild(wrapper);
        }

        function removeExistingClass(button, classId) {
            const deletedContainer = document.getElementById('deleted-classes');

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_class[]';
            input.value = classId;

            deletedContainer.appendChild(input);

            button.closest('.class-row').remove();
        }
    </script>
</x-app-layout>
