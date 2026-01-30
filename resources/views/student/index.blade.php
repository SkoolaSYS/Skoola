@role('teacher')
<x-app-layout>
    @slot('title')
        Students
    @endslot

    <x-card title="Students Movement / Remarks">

        <div class="mb-3 text-end text-muted">
            {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </div>

        {{-- Success & Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Filter Form (Grade & Class) --}}
        <div class="mb-5">
    <!-- Remove @csrf for GET -->

                

                

                    <div class="mb-4">
                    <label class="form-label">{{ __('messages.searchstudents') }}</label>
                    <input type="text"
                        id="studentSearch"
                        class="form-control"
                        placeholder="{{ __('messages.searchstudent') }}">

                    <div id="searchResults" class="list-group mt-2 d-none"></div>
                </div>

                </div>

                
        </div>

        {{-- Students Table --}}
            <div class="mt-4">


                <form method="POST" action="{{ route('student.remarks.store') }}">
                    @csrf

                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>{{ __('messages.fullname') }}</th>
                                <th>{{ __('messages.reason') }}</th>
                                <th>{{ __('messages.details') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="selectedStudents">
                            {{-- Added dynamically --}}
                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        <button class="btn btn-success">{{ __('messages.save') }}</button>
                    </div>
                    </form>

            </div>

    </x-card>

    {{-- SAME STYLE LOGIC AS ATTENDANCE --}}
    <style>
        .attendance-present {
            background-color: #d4edda !important;
            border-color: #c3e6cb;
        }
        .attendance-absent {
            background-color: #f8d7da !important;
            border-color: #f5c6cb;
        }
        .attendance-others {
            background-color: #fff3cd !important;
            border-color: #ffeeba;
        }
    </style>

    <script>
let addedStudents = [];

/* =========================
   STUDENT SEARCH
========================= */
document.getElementById('studentSearch').addEventListener('keyup', function () {
    const q = this.value.trim();
    const resultBox = document.getElementById('searchResults');

    if (q.length < 2) {
        resultBox.classList.add('d-none');
        resultBox.innerHTML = '';
        return;
    }

    fetch(`{{ route('teacher.students.search') }}?q=${q}`)

        .then(res => res.json())
        .then(data => {
            resultBox.innerHTML = '';
            resultBox.classList.remove('d-none');

            if (data.length === 0) {
                resultBox.innerHTML =
                    `<div class="list-group-item text-muted">No students found</div>`;
                return;
            }

            data.forEach(student => {
                resultBox.innerHTML += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${student.name}</strong><br>
                            <small>${student.grade} - ${student.class_name}</small>
                        </div>
                        <button type="button"
                            class="btn btn-sm btn-primary"
                            onclick="addStudent(${student.id}, '${student.name}', '${student.grade}', '${student.class_name}')">
                            Add
                        </button>
                    </div>
                `;
            });
        });
});

/* =========================
   ADD STUDENT
========================= */
function addStudent(id, name, grade, className) {
    if (addedStudents.includes(id)) {
        alert('Student already added');
        return;
    }

    addedStudents.push(id);

    const table = document.getElementById('selectedStudents');
    const rowCount = table.rows.length + 1;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="row-no">${rowCount}</td>
        <td class="text-start">
            ${name}<br>
            <small class="text-muted">${grade} - ${className}</small>
            <input type="hidden" name="student_ids[]" value="${id}">
        </td>
        <td>
            <select name="remarks[${id}][type]" class="form-select" required>
                <option value="">-- Pilih --</option>
                <option value="school activity">Aktiviti Sekolah</option>
                <option value="clinic">Klinik</option>
                <option value="others">Lain Lain</option>
            </select>
        </td>
        <td>
            <input type="text"
                name="remarks[${id}][text]"
                class="form-control"
                placeholder="Optional justification">
        </td>
        <td>
            <button type="button"
                class="btn btn-sm btn-danger"
                onclick="removeStudent(this, ${id})">
                Remove
            </button>
        </td>
    `;

    table.appendChild(row);

    // Reset search
    document.getElementById('studentSearch').value = '';
    document.getElementById('searchResults').innerHTML = '';
    document.getElementById('searchResults').classList.add('d-none');
}

/* =========================
   REMOVE STUDENT
========================= */
function removeStudent(button, studentId) {
    // Optional confirm
    if (!confirm('Remove this student?')) return;

    // Remove from array
    addedStudents = addedStudents.filter(id => id !== studentId);

    // Remove row
    const row = button.closest('tr');
    row.remove();

    // Re-number rows
    document.querySelectorAll('#selectedStudents .row-no')
        .forEach((cell, index) => {
            cell.textContent = index + 1;
        });
}
</script>






</x-app-layout>
@endrole
