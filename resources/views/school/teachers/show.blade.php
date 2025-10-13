<x-app-layout>
    <div class="container mt-4">
        <div class="bg-white p-4 rounded shadow-sm">
            <h3 class="mb-3">Teacher Details</h3>

            <div class="mb-2">
                <p><strong>Name:</strong> {{ $teacher->name }}</p>
            </div>

            <div class="mb-2">
                <p><strong>IC Number:</strong> {{ $teacher->ic ?? '—' }}</p>
            </div>

            <div class="mb-2">
                <p><strong>Address:</strong> {{ $teacher->address ?? '—' }}</p>
            </div>

            <div class="mb-2">
                <p><strong>Phone Number:</strong> {{ $teacher->phone_num ?? '—' }}</p>
            </div>

            <div class="mb-2">
                <p><strong>Email:</strong> {{ $teacher->email }}</p>
            </div>

            <div class="mb-2">
                <p><strong>Status:</strong> {{ ucfirst($teacher->status ?? 'N/A') }}</p>
            </div>

            <a href="{{ route('school.teachers.index') }}" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</x-app-layout>
