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

            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-3 align-middle">
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
                                $currentStatus = $existingAttendance[$student->id] ?? ''; // get old value if exist
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
</x-app-layout>
