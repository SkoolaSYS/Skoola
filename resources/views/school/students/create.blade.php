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
                <label class="form-label">Birth Certificate Number</label>
                <input type="text" class="form-control" name="birth_cert_no">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="textarea" class="form-control" name="address" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="dob" required>
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
                <label class="form-label">Darjah/Tingkatan</label>
                <select name="race" class="form-select" required>
                    <option value="">Select Darjah/Tingkatan</option>
                    <option value="Darjah 1">Darjah 1</option>
                    <option value="Darjah 2">Darjah 2</option>
                    <option value="Darjah 3">Darjah 3</option>
                    <option value="Darjah 4">Darjah 4</option>
                    <option value="Darjah 5">Darjah 5</option>
                    <option value="Darjah 6">Darjah 6</option>
                    <option value="Tingkatan 1">Tingkatan 1</option>
                    <option value="Tingkatan 2">Tingkatan 2</option>
                    <option value="Tingkatan 3">Tingkatan 3</option>
                    <option value="Tingkatan 4">Tingkatan 4</option>
                    <option value="Tingkatan 5">Tingkatan 5</option>
                    <option value="Tingkatan 6">Tingkatan 6</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Class</label>
                <input type="text" class="form-control" name="class_name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Race</label>
                <select name="race" class="form-select" required>
                    <option value="">Select Race</option>
                    <option value="Melayu">Malay</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Indian">Indian</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Religion</label>
                <select name="religion" class="form-select" required>
                    <option value="">Select Religion</option>
                    <option value="Islam">Islam</option>
                    <option value="Christianity">Christianity</option>
                    <option value="Buddhism">Buddhism</option>
                    <option value="Hinduism">Hinduism</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nationality</label>
                <select name="nationality" class="form-select" required>
                    <option value="">Select Nationality</option>
                    <option value="Malaysian">Malaysian</option>
                    <option value="Non-Malaysian">Non-Malaysian</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Orphan</label>
                <select name="orphan" class="form-select" required>
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">OKU</label>
                <select name="oku" class="form-select" required>
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>


            <button type="submit" class="btn btn-primary">Add Student</button>
            <a href="{{ route('dashboard.school', ['school' => $school->id]) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
</x-app-layout>
