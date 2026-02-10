@role('admin|country|state|ppd|school')
<x-app-layout>
    @slot('title')
        Student Management
    @endslot

    <x-card title="Student Management">
        <!-- Student Table Section -->
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">

                            <!-- Page title -->
                            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                                <h3 class="text-2xl font-semibold mb-3">{{ __('messages.listofstudents', ['school' => $school->name]) }}</h3>
                            </div>

                            <!-- Button Group (Add Student + Export) -->
                            <div class="d-flex align-items-center gap-2">
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('school'))
                                    <div class="dropdown">
                                        <button class="btn btn-light-primary dropdown-toggle" type="button" id="addStudentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ki-duotone ki-plus fs-2"></i> {{ __('messages.addstudent') }}
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="addStudentDropdown">
                                            <li><a class="dropdown-item" href="{{ route('school.student.create', ['school_id' => $school_id]) }}">{{ __('messages.addmanually') }}</a></li>
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkImportModal">{{ __('messages.importexcel') }}</a></li>
                                        </ul>
                                    </div>
                                @endif

                                <a href="{{ route('dashboard.school_export', ['school_id' => $school->id]) }}" class="btn btn-light-success">
                                        <i class="ki-duotone ki-exit-up fs-2"></i> {{ __('messages.export') }}
                                    </a>

                                    

                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        </div>

                        <!-- Students Table -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                            @livewire('admin-student-table', ['school_id' => $school_id])
                        </div>

                        <!-- Student Modal -->
                        <div wire:ignore.self class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content shadow-lg">
                            <div class="modal-header">
                                <h5 class="modal-title" id="studentModalLabel">{{ __('messages.studentattendance') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body text-center">
                                <div id="modal_chart" class="mx-auto" style="max-width:300px;"></div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <a id="modal_detail_link" href="#" class="btn btn-primary">{{ __('messages.viewdetails') }}</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                            </div>
                            </div>
                        </div>
                        </div>


                    </div>
                </div>
            

        


        <!-- Bulk Import Modal -->
        <div class="modal fade" id="bulkImportModal" tabindex="-1" aria-labelledby="bulkImportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('school.students.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkImportModalLabel">{{ __('messages.importstudentsfromexcel') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3">{{ __('messages.pleaseusetemplate') }}</p>
                        <a href="{{ route('school.students.template') }}" class="btn btn-link">{{ __('messages.downloadtemplate') }}</a>
                        <div class="mb-3">
                            <label for="import_file" class="form-label">{{ __('messages.uploadexcelfile') }}</label>
                            <input type="file" class="form-control" id="import_file" name="import_file" accept=".xlsx, .xls" required>
                        </div>
                    </div>

                    <input type="hidden" name="school_id" value="{{ $school_id }}">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </x-card>

    @push('scripts')
<script>
window.addEventListener('open-student-modal', event => {
    const data = event.detail.attendanceData || [];
    const studentName = event.detail.studentName || 'Student Attendance';
    const detailUrl = event.detail.detailUrl || '#';

    document.getElementById('studentModalLabel').innerText = "Kehadiran " + studentName;
    document.getElementById('modal_detail_link').setAttribute('href', detailUrl);

    const modalEl = document.getElementById('studentModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    setTimeout(() => {
        const chartContainer = document.querySelector("#modal_chart");
        if (!chartContainer) return;

        chartContainer.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            chartContainer.innerHTML = '<p class="text-muted">No attendance data.</p>';
            return;
        }

        const chart = new ApexCharts(chartContainer, {
            series: data,
            chart: { type: 'donut', width: 230, height: 200 },
            labels: ["{{ __('messages.present') }}", "{{ __('messages.absent') }}", "{{ __('messages.others') }}"],
            colors: ['#50cd89', '#f1416c', '#ffc107'],
            legend: { show: true, position: 'bottom' },
        });

        chart.render();
    }, 150);
});
</script>
@endpush

    

    
</x-app-layout>
@endrole
