@role('teacher')
<x-app-layout>
    @slot('title')
        Class Attendance
    @endslot

    <x-card title="Class Attendance">
        <div class="mb-3 text-end text-muted">
    {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
</div>

<!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

        <!-- Form to select Grade, Class, Subject -->
        <div class="mb-5">
            <form method="GET" action="{{ route('class_attendance.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="grade" class="form-label">Tingkatan/Darjah</label>
                        <select name="grade" id="grade" class="form-select" onchange="filterClasses()" required>
                            <option value="">-- Select Grade --</option>
                            <option value="Tingkatan 1" {{ $selectedGrade == 'Tingkatan 1' ? 'selected' : '' }}>Tingkatan 1</option>
                            <option value="Tingkatan 2" {{ $selectedGrade == 'Tingkatan 2' ? 'selected' : '' }}>Tingkatan 2</option>
                            <option value="Tingkatan 3" {{ $selectedGrade == 'Tingkatan 3' ? 'selected' : '' }}>Tingkatan 3</option>
                            <option value="Tingkatan 4" {{ $selectedGrade == 'Tingkatan 4' ? 'selected' : '' }}>Tingkatan 4</option>
                            <option value="Tingkatan 5" {{ $selectedGrade == 'Tingkatan 5' ? 'selected' : '' }}>Tingkatan 5</option>
                            <option value="Tingkatan 6" {{ $selectedGrade == 'Tingkatan 6' ? 'selected' : '' }}>Tingkatan 6</option>
                            <option value="Darjah 1" {{ $selectedGrade == 'Darjah 1' ? 'selected' : '' }}>Darjah 1</option>
                            <option value="Darjah 2" {{ $selectedGrade == 'Darjah 2' ? 'selected' : '' }}>Darjah 2</option>
                            <option value="Darjah 3" {{ $selectedGrade == 'Darjah 3' ? 'selected' : '' }}>Darjah 3</option>
                            <option value="Darjah 4" {{ $selectedGrade == 'Darjah 4' ? 'selected' : '' }}>Darjah 4</option>
                            <option value="Darjah 5" {{ $selectedGrade == 'Darjah 5' ? 'selected' : '' }}>Darjah 5</option>
                            <option value="Darjah 6" {{ $selectedGrade == 'Darjah 6' ? 'selected' : '' }}>Darjah 6</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="class_name" class="form-label">Class</label>
                        <select name="class_name" id="class_name" class="form-select" required>
                            <option value="">-- Select Class --</option>
                            @foreach($allClasses as $class)
                                <option value="{{ $class }}" {{ $selectedClass == $class ? 'selected' : '' }}>{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="subject" class="form-label">Subject</label>
                        <select name="subject" id="subject" class="form-select" required>
                            <option value="">-- Select Subject --</option>
                            @foreach(['Mathematics','Science','English','Bahasa Melayu','Sejarah','Geografi'] as $subject)
                                <option value="{{ $subject }}" {{ $selectedSubject == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Show Students</button>
                </div>
            </form>
        </div>

        @if(isset($students) && count($students) > 0)
        <div class="mt-5">
            <h5>Student List - {{ $selectedClass }} ({{ $selectedSubject }})</h5>
            <form method="POST" action="{{ route('class_attendance.store') }}">
                @csrf
                <input type="hidden" name="grade" value="{{ $selectedGrade }}">
                <input type="hidden" name="class_name" value="{{ $selectedClass }}">
                <input type="hidden" name="subject" value="{{ $selectedSubject }}">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-3">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>
                                        <select name="attendance[{{ $student->id }}]" class="attendance-select">
                                        @foreach(['Present','Absent','Late','MC','Unwell','School Activity','Others'] as $status)
                                            <option value="{{ $status }}" 
                                                {{ ($attendanceRecords[$student->id] ?? 'Present') == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>



                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-success mt-3">Save Attendance</button>
            </form>
        </div>
        @endif

    </x-card>

    <script>
        function filterClasses() {
            let grade = document.getElementById("grade").value;
            let classSelect = document.getElementById("class_name");

            for (let i = 0; i < classSelect.options.length; i++) {
                let option = classSelect.options[i];

                if(option.value === "") { option.style.display = ""; continue; }

                if ((grade.includes("Tingkatan") && option.value.startsWith(grade.replace("Tingkatan ",""))) ||
                    (grade.includes("Darjah") && option.value.startsWith(grade.replace("Darjah ",""))) ||
                    option.value.startsWith(grade)) {
                    option.style.display = "";
                } else {
                    option.style.display = "none";
                }
            }
            classSelect.value = "{{ $selectedClass ?? '' }}";
        }

        // Run filterClasses on page load to maintain selected class
        document.addEventListener('DOMContentLoaded', function() {
            filterClasses();
        });
    </script>

</x-app-layout>
@endrole
