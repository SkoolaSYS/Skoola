<x-app-layout>
    @slot('title')
        Add Attendance - {{ $selectedClass }}
    @endslot

    <x-card title="Add Attendance for {{ $selectedClass }}">
        <form method="POST" action="{{ route('class_attendance.save') }}">
            @csrf
            <input type="hidden" name="grade" value="{{ $selectedGrade }}">
            <input type="hidden" name="class_name" value="{{ $selectedClass }}">
            <input type="hidden" name="subject" value="{{ $selectedSubject }}">

            <!-- Search Bar -->
            <div class="mb-3 d-flex justify-content-end">
                <input type="text" id="searchInput" class="form-control w-auto me-2" placeholder="Search student name...">
                <button type="button" class="btn btn-primary" id="searchButton">Search</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-3 align-middle" id="attendanceTable">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $currentStatus = $existingAttendance[$student->id] ?? 'Present';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->name }}</td>
                                <td>
                                    <select name="attendance[{{ $student->id }}]" class="form-select">
                                        <option value="Present" {{ $currentStatus == 'Present' ? 'selected' : '' }}>Present</option>
                                        <option value="Absent" {{ $currentStatus == 'Absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="Late" {{ $currentStatus == 'Late' ? 'selected' : '' }}>Late</option>
                                        <option value="MC" {{ $currentStatus == 'MC' ? 'selected' : '' }}>MC</option>
                                        <option value="Unwell" {{ $currentStatus == 'Unwell' ? 'selected' : '' }}>Unwell</option>
                                        <option value="School Activity" {{ $currentStatus == 'School Activity' ? 'selected' : '' }}>School Activity</option>
                                        <option value="Others" {{ $currentStatus == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save Attendance</button>
        </form>
    </x-card>

    <!-- Search Script -->
    <script>
        document.getElementById('searchButton').addEventListener('click', function () {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceTable tbody tr');

            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(2)');
                if (nameCell) {
                    const name = nameCell.textContent.toLowerCase();
                    row.style.display = name.includes(input) ? '' : 'none';
                }
            });
        });

        // Optional: live search on typing
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const input = this.value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceTable tbody tr');

            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(2)');
                if (nameCell) {
                    const name = nameCell.textContent.toLowerCase();
                    row.style.display = name.includes(input) ? '' : 'none';
                }
            });
        });
    </script>
</x-app-layout>
