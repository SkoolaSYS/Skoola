<x-app-layout>
    @slot('title') Add Student @endslot

    <x-card title="Add Student">
        <form method="POST" action="{{ route('school.student.store', ['school' => $school->id]) }}">
            @csrf

            <input type="hidden" name="school_id" value="{{ $school->id }}">

            <div class="mb-3">
                <label class="form-label">Student Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">NRIC</label>
                <input type="text" class="form-control" name="ic" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Grade</label>
                <input type="text" class="form-control" name="grade" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Class</label>
                <input type="text" class="form-control" name="class_name" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Student</button>
            <a href="{{ route('dashboard.school', ['school' => $school->id]) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
</x-app-layout>
