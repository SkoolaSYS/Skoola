<x-app-layout>
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">{{ __('messages.teachers') }}</h4>
                    <a href="{{ route('school.teachers.create') }}" class="btn btn-primary">
                        {{ __('messages.addteacher') }}
                    </a>
                </div>

                @if ($teachers->isEmpty())
                    <div class="alert alert-info">{{ __('messages.noteachers') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.fullname') }}</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="text-center">{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teachers as $index => $teacher)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $teacher->name }}</td>
                                        <td>{{ $teacher->email }}</td>
                                        <td>
                                            @if ($teacher->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif ($teacher->status === 'inactive')
                                                <span class="badge bg-secondary">Inactive</span>
                                            @else
                                                <span class="badge bg-light text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
    <button 
        class="btn btn-sm btn-primary me-1 view-teacher" 
        data-name="{{ $teacher->name }}"
        data-email="{{ $teacher->email }}"
        data-status="{{ ucfirst($teacher->status ?? 'N/A') }}"
        data-ic="{{ $teacher->ic ?? '-' }}"
        data-address="{{ $teacher->address ?? '-' }}"
        data-phone="{{ $teacher->phone_num ?? '-' }}"
    >
        {{ __('messages.view') }}
    </button>

    <a href="{{ route('school.teachers.edit', $teacher->id) }}" class="btn btn-sm btn-warning me-1">
        {{ __('messages.edit') }}
    </a>

    <form action="{{ route('school.teachers.toggle', $teacher->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('PUT')
        @if ($teacher->status === 'active')
            <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.deactivate') }}</button>
        @else
            <button type="submit" class="btn btn-sm btn-success">{{ __('messages.activate') }}</button>
        @endif
    </form>
</td>


</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="teacherModalLabel">{{ __('messages.teacherdetails') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <p><strong>{{ __('messages.fullname') }}:</strong> <span id="modal-teacher-name"></span></p>
                <p><strong>{{ __('messages.ic') }}:</strong> <span id="modal-teacher-ic"></span></p>
                <p><strong>{{ __('messages.address') }}:</strong> <span id="modal-teacher-address"></span></p>
                <p><strong>{{ __('messages.phonenum') }}:</strong> <span id="modal-teacher-phone"></span></p>
                <p><strong>Email:</strong> <span id="modal-teacher-email"></span></p>
                <p><strong>Status:</strong> <span id="modal-teacher-status"></span></p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>


    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-teacher');
    const modal = new bootstrap.Modal(document.getElementById('teacherModal'));

    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('modal-teacher-name').textContent = this.dataset.name;
            document.getElementById('modal-teacher-ic').textContent = this.dataset.ic;
            document.getElementById('modal-teacher-address').textContent = this.dataset.address;
            document.getElementById('modal-teacher-phone').textContent = this.dataset.phone;
            document.getElementById('modal-teacher-email').textContent = this.dataset.email;
            document.getElementById('modal-teacher-status').textContent = this.dataset.status;

            modal.show();
        });
    });
});
</script>
@endpush

</x-app-layout>
