<x-app-layout>
    @slot('title')
        Edit Attendance - {{ $selectedClass }}
    @endslot

    <x-card title="Edit Attendance for {{ $selectedSubject }} ({{ $selectedClass }})">
        <form method="POST" action="{{ route('class_attendance.update') }}">
            @csrf
            <input type="hidden" name="grade" value="{{ $selectedGrade }}">
            <input type="hidden" name="class_name" value="{{ $selectedClass }}">
            <input type="hidden" name="subject" value="{{ $selectedSubject }}">

            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-3 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>{{ __('messages.fullname') }}</th>
                            <th>{{ __('messages.attendance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $status = $attendanceRecords[$student->id] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->name }}</td>
                                <td>
                                    <select name="attendance[{{ $student->id }}]" class="form-select">
                                        <option value="Present" {{ $status == 'Present' ? 'selected' : '' }}>{{ __('messages.present') }}</option>
                                        <option value="Absent" {{ $status == 'Absent' ? 'selected' : '' }}>{{ __('messages.absent') }}</option>
                                        <option value="Late" {{ $status == 'Late' ? 'selected' : '' }}>{{ __('messages.late') }}</option>
                                        <option value="MC" {{ $status == 'MC' ? 'selected' : '' }}>MC</option>
                                        <option value="Unwell" {{ $status == 'Unwell' ? 'selected' : '' }}>{{ __('messages.unwell') }}</option>
                                        <option value="School Activity" {{ $status == 'School Activity' ? 'selected' : '' }}>{{ __('messages.schoolactivity') }}</option>
                                        <option value="Others" {{ $status == 'Others' ? 'selected' : '' }}>{{ __('messages.others') }}</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success mt-3">{{ __('messages.update') }}</button>
        </form>
    </x-card>
</x-app-layout>
