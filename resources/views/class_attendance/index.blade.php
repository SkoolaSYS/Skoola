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
                            @foreach(['Tingkatan 1','Tingkatan 2','Tingkatan 3','Tingkatan 4','Tingkatan 5','Tingkatan 6',
                                      'Darjah 1','Darjah 2','Darjah 3','Darjah 4','Darjah 5','Darjah 6'] as $grade)
                                <option value="{{ $grade }}" {{ $selectedGrade == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                            @endforeach
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
    <h5>Student List - {{ $selectedClass }} ({{ $selectedGrade }})</h5>

    <div class="text-end mb-3 d-flex justify-content-end gap-2">
    <a href="{{ route('class_attendance.add', ['grade' => $selectedGrade, 'class_name' => $selectedClass, 'subject' => $selectedSubject]) }}"
       class="btn btn-primary">
        Add Attendance
    </a>

    @php
        $teacherHasAttendance = \App\Models\ClassAttendance::where('grade', $selectedGrade)
            ->where('class_name', $selectedClass)
            ->where('subject', $selectedSubject)
            ->where('teacher_id', auth()->id())
            ->whereDate('attendance_time', now()->toDateString())
            ->exists();
    @endphp

    @if($teacherHasAttendance)
        <a href="{{ route('class_attendance.edit', [
            'grade' => $selectedGrade,
            'class_name' => $selectedClass,
            'subject' => $selectedSubject
        ]) }}" class="btn btn-warning">
            Edit Attendance
        </a>
        
    @endif

        <a href="{{ route('class_attendance.pdf', [
    'grade' => $selectedGrade,
    'class_name' => $selectedClass,
    'subject' => $selectedSubject
]) }}" 
   class="btn btn-danger" target="_blank">
   Download PDF
</a>



</div>



    <div class="table-responsive">
        <table class="table table-bordered table-hover mt-3 align-middle text-center">
            <thead class="table-light">
                <tr>
        <th>No.</th>
        <th>Name</th>
        @foreach($subjectOrder as $subject => $num)
            <th>{{ $num }}</th>
        @endforeach
    </tr>
</thead>
<tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $student->name }}</td>
            @foreach($subjectOrder as $subject => $num)
                @php
                    $status = $attendanceRecords[$student->id][$subject] ?? null;
                    $color = match($status) {
                        'Present' => 'bg-success',
                        'Absent' => 'bg-danger',
                        default => ($status ? 'bg-warning' : '')
                    };
                @endphp
                <td class="{{ $color }}" style="text-align:center;">
                    {{ $status ? strtoupper(substr($status, 0, 1)) : '-' }}
                </td>
            @endforeach
        </tr>
    @endforeach
</tbody>
        </table>
    </div>
</div>
@endif


    </x-card>

    <style>
        /* Color styling for dropdowns */
        .attendance-select {
            transition: background-color 0.3s ease;
            color: #000;
        }
        .attendance-present {
            background-color: #d4edda !important; /* light green */
            border-color: #c3e6cb;
        }
        .attendance-absent {
            background-color: #f8d7da !important; /* light red */
            border-color: #f5c6cb;
        }
        .attendance-others {
            background-color: #fff3cd !important; /* light yellow */
            border-color: #ffeeba;
        }
    </style>

    <script>
        function filterClasses() {
            let grade = document.getElementById("grade").value;
            let classSelect = document.getElementById("class_name");

            for (let i = 0; i < classSelect.options.length; i++) {
                let option = classSelect.options[i];

                if (option.value === "") { option.style.display = ""; continue; }

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

        // Color logic
        function updateSelectColor(select) {
            select.classList.remove('attendance-present', 'attendance-absent', 'attendance-others');
            let value = select.value.toLowerCase();
            if (value === 'present') {
                select.classList.add('attendance-present');
            } else if (value === 'absent') {
                select.classList.add('attendance-absent');
            } else if (value === 'others') {
                select.classList.add('attendance-others');
            } else {
                // for MC, Unwell, etc., default to yellow as "others"
                select.classList.add('attendance-others');
            }
        }

        // Apply color on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.attendance-select').forEach(updateSelectColor);
            filterClasses();
        });
    </script>
</x-app-layout>
@endrole
