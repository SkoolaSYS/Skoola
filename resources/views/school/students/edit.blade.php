<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Student</h5>
                <a href="{{ route('dashboard.school', $student->school_id) }}" class="btn btn-light btn-sm">
                    ← Back
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('school.students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $student->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            required>{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Class --}}
                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <input type="text" name="class_name" id="class_name"
                            class="form-control @error('class') is-invalid @enderror"
                            value="{{ old('class_name', $student->class_name) }}" required>
                        @error('class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- IC Number --}}
                    <div class="mb-3">
                        <label for="ic" class="form-label">IC Number</label>
                        <input type="text" name="ic" id="ic"
                            class="form-control @error('ic') is-invalid @enderror"
                            value="{{ old('ic', $student->ic) }}" required>
                        @error('ic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
