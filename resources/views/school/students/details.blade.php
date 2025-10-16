<x-app-layout>
    @slot('title') Student Details @endslot
    <div class="container mt-4">
        <div class="bg-white p-4 rounded shadow-sm">
            <h3 class="mb-3">{{ $student->name }}'s Details</h3>

            <table class="table table-bordered">
                <tr>
                    <th>Student ID</th>
                    <td>{{ $student->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <th>IC Number</th>
                    <td>{{ $student->ic ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Age</th>
                    <td>{{ $student->age }}</td>
                </tr>
                <tr>
                    <th>Birth Certificate Number</th>
                    <td>{{ $student->birth_cert_no }}</td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td>{{ $student->dob }}</td>
                </tr>
                <tr>
                    <th>Grade</th>
                    <td>{{ $student->grade }}</td>
                </tr>
                <tr>
                    <th>Class</th>
                    <td>{{ $student->class_name }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ $student->gender }}</td>
                </tr>
                <tr>
                    <th>Race</th>
                    <td>{{ $student->race }}</td>
                </tr>
                <tr>
                    <th>Religion</th>
                    <td>{{ $student->religion }}</td>
                </tr>
                <tr>
                    <th>Nationality</th>
                    <td>{{ $student->nationality }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $student->address ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Orphan</th>
                    <td>{{ $student->orphan }}</td>
                </tr>
                <tr>
                    <th>OKU</th>
                    <td>{{ $student->oku }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($student->status === 'Active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>School</th>
                    <td>{{ $student->school->name ?? 'N/A' }}</td>
                </tr>
            </table>

            <hr>

            

            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</x-app-layout>
