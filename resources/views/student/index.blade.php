@role('teacher')
<x-app-layout>
    @slot('title')
        Students
    @endslot

    <x-card title="Students Movement / Remarks">

        <div class="mb-3 text-end text-muted">
            {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </div>

        {{-- Success & Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Filter Form (Grade & Class) --}}
        <div class="mb-5">
            <form method="GET" action="{{ route('student.filter') }}">
    <!-- Remove @csrf for GET -->

                

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.grade') }}</label>
                        <select name="grade" id="grade" class="form-select" onchange="updateClasses()" required>
                            <option value="">-- {{ __('messages.selectgrade') }} --</option>
                            @foreach([
                                'Darjah 1','Darjah 2','Darjah 3','Darjah 4','Darjah 5','Darjah 6',
                                'Tingkatan 1','Tingkatan 2','Tingkatan 3','Tingkatan 4','Tingkatan 5','Tingkatan 6'
                            ] as $grade)
                                <option value="{{ $grade }}"
                                    {{ ($selectedGrade ?? '') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.class') }}</label>
                        <select name="class_name" id="class_name" class="form-select" required>
                            <option value="">-- {{ __('messages.selectclass') }} --</option>
                            @foreach($allClasses ?? [] as $class)
                                <option value="{{ $class }}"
                                    {{ ($selectedClass ?? '') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.showstudents') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Students Table --}}
        @if(isset($students) && count($students) > 0)
            <div class="mt-4">
                <h5>
                    {{ __('messages.studentlist') }} – {{ $selectedClass }} ({{ $selectedGrade }})
                </h5>

                <form method="POST" action="{{ route('student.remarks') }}">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>{{ __('messages.fullname') }}</th>
                                    <th>{{ __('messages.remark') }}</th>
                                    <th>{{ __('messages.details') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $student->name }}</td>

                                        <td>
                                        <select name="remarks[{{ $student->id }}][type]"
                                                class="form-select attendance-select"
                                                onchange="updateSelectColor(this)">
                                            <option value="">-- None --</option>
                                            <option value="activity" {{ ($remarks[$student->id]->remark_type ?? '') === 'activity' ? 'selected' : '' }}>{{ __('messages.schoolactivity') }}</option>
                                            <option value="clinic" {{ ($remarks[$student->id]->remark_type ?? '') === 'clinic' ? 'selected' : '' }}>{{ __('messages.clinic') }}</option>
                                            <option value="others" {{ ($remarks[$student->id]->remark_type ?? '') === 'others' ? 'selected' : '' }}>{{ __('messages.others') }}</option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text"
                                            name="remarks[{{ $student->id }}][text]"
                                            class="form-control"
                                            placeholder="{{ __('messages.optionaldetails') }}"
                                            value="{{ $remarks[$student->id]->remark_text ?? '' }}">
                                    </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </x-card>

    {{-- SAME STYLE LOGIC AS ATTENDANCE --}}
    <style>
        .attendance-present {
            background-color: #d4edda !important;
            border-color: #c3e6cb;
        }
        .attendance-absent {
            background-color: #f8d7da !important;
            border-color: #f5c6cb;
        }
        .attendance-others {
            background-color: #fff3cd !important;
            border-color: #ffeeba;
        }
    </style>

    <script>
    function updateClasses(selectedClass = null) {
        const grade = document.getElementById('grade').value;
        const classSelect = document.getElementById('class_name');

        classSelect.innerHTML = '<option value="">-- Select Class --</option>';

        if (!grade) return;

        const gradeNumber = grade.replace(/\D/g, '');

        const classes = [
            gradeNumber + 'A',
            gradeNumber + 'B'
        ];

        classes.forEach(cls => {
            const option = document.createElement('option');
            option.value = cls;
            option.text = cls;

            if (selectedClass && selectedClass === cls) {
                option.selected = true;
            }

            classSelect.appendChild(option);
        });
    }

    function updateSelectColor(select) {
        select.classList.remove('attendance-present','attendance-absent','attendance-others');

        if (select.value === 'activity') {
            select.classList.add('attendance-present');
        } else if (select.value === 'clinic') {
            select.classList.add('attendance-absent');
        } else if (select.value === 'others') {
            select.classList.add('attendance-others');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateClasses(@json($selectedClass ?? null));
    });
</script>


</x-app-layout>
@endrole
