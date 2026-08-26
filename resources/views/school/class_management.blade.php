<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Class Management</h2>
            <small class="text-muted">Manage grade activation & class settings</small>
        </div>
    </x-slot>

    <div class="py-4 bg-light min-vh-100">
        <div class="container">

            {{-- Success Message --}}
            <div id="success-msg" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                <span id="success-text"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="card shadow-sm rounded-3 p-4">

                {{-- Section Title --}}
                <div class="mb-4">
                    <h5 class="card-title mb-1">Available Grades</h5>
                    <small class="text-muted">Activate grades to allow class management.</small>
                </div>

                {{-- Add New Grade Form --}}
                <form id="add-grade-form" class="row g-2 mb-4">
                    @csrf
                    <div class="col-auto">
                        <input type="text" id="new-grade-name" class="form-control form-control-sm" placeholder="Add new grade" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success btn-sm">Add Grade</button>
                    </div>
                </form>

                {{-- Grade Cards --}}
                <div class="row g-3" id="grade-cards">
                    @foreach($allGrades as $grade)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3" id="grade-card-{{ Str::slug($grade) }}">
                            <div class="card h-100 border-1 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h6 class="card-title">{{ $grade }}</h6>

                                    @if(in_array($grade, $activatedGrades))
                                        <div class="d-grid gap-2 mt-2">
                                            <span class="badge bg-success mb-2">✓ Activated</span>
                                            <form action="{{ route('dashboard.school.deactivate_grade') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="grade_name" value="{{ $grade }}">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    Deactivate Grade
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <form action="{{ route('dashboard.school.activate_grade') }}" method="POST" class="d-grid gap-2 mt-2">
                                            @csrf
                                            <input type="hidden" name="grade_name" value="{{ $grade }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Activate Grade
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">
                <div class="text-center">
                    <a href="{{ route('dashboard.school.edit_class') }}" class="btn btn-primary rounded-pill">
                        Edit Class Names
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- AJAX Script --}}
    @push('scripts')
    <script>
        document.getElementById('add-grade-form').addEventListener('submit', function(e){
            e.preventDefault();

            const gradeName = document.getElementById('new-grade-name').value.trim();
            if(!gradeName) return;

            fetch("{{ route('dashboard.school.add_grade') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ grade_name: gradeName })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // Show success message
                    const msg = document.getElementById('success-msg');
                    document.getElementById('success-text').textContent = data.success;
                    msg.classList.remove('d-none');

                    // Add new grade card to the page
                    const slug = gradeName.toLowerCase().replace(/\s+/g, '-');
                    const container = document.getElementById('grade-cards');

                    const newCard = `
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3" id="grade-card-${slug}">
                            <div class="card h-100 border-1 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h6 class="card-title">${gradeName}</h6>
                                    <div class="d-grid gap-2 mt-2">
                                        <span class="badge bg-success mb-2">✓ Activated</span>
                                        <form action="/dashboard/school/deactivate-grade" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="grade_name" value="${gradeName}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Deactivate Grade
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', newCard);

                    document.getElementById('new-grade-name').value = '';
                }
            })
            .catch(err => console.error(err));
        });
    </script>
    @endpush

</x-app-layout>
