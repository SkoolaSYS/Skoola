@role('admin|country|state|ppd|school')
<x-app-layout>
    @slot('title')
        School Dashboard
    @endslot

    <x-card title=''>
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100"></div>

        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="card h-xl-100 shadow-sm p-3 mb-5 bg-white rounded">

                <!-- Header -->
                <div class="card-header position-relative py-0 border-bottom-2 justify-content-center">
                    <span class="d-flex flex-column justify-content-center">
                        <h1 class="page-heading text-dark fw-bolder fs-1 m-0 text-center">Dashboard</h1>
                        <h6 class="text-muted text-center fs-7">School Level</h6>
                    </span>
                </div>

                <!-- Body -->
                <div class="card-body pb-4">
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-5 pb-4 text-center">

                    <!-- Total Students -->
                    <div class="p-4 border-0 bg-transparent">
                        <h1 class="text-dark fw-bolder fs-1 mb-0">{{ $totalPelajar }}</h1>
                        <h6 class="text-muted fs-7">Total Students</h6>
                    </div>

                    <!-- Average Attendance -->
                    <div class="p-4 border-0 bg-transparent">
                        <div style="position: relative; width: 180px; height: 180px; margin: 0 auto;">
                            <canvas id="attendanceDonutChart"></canvas>
                        </div>
                        <h6 class="text-muted fs-7 mb-2">Average Attendance Percentage</h6>
                    </div>

                </div>
                </div>

                </div>


                    </div>
                </div>

                <!-- Student Table Section -->
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">

                            <!-- Page title -->
                            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                                <h3 class="text-2xl font-semibold mb-3">LIST OF STUDENTS IN {{ $school->name }}</h3>
                            </div>

                            <!-- Button Group (Add Student + Export) -->
                            <div class="d-flex align-items-center gap-2">

                                <!-- Add Student Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-light-primary dropdown-toggle" 
                                            type="button" 
                                            id="addStudentDropdown" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <i class="ki-duotone ki-plus fs-2"></i> Add Student
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="addStudentDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('school.student.create', ['school_id' => $school_id]) }}">
                                                Add Student Manually
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                                                Import from Excel
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Export Button -->
                                <a href="{{ route('dashboard.school_export', ['school_id' => $school_id]) }}" 
                                type="button" 
                                class="btn btn-light-success">
                                    <i class="ki-duotone ki-exit-up fs-2"></i> Export to Excel
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
                        <h5 class="modal-title" id="bulkImportModalLabel">Import Students from Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3">Please use our Excel template before importing:</p>
                        <a href="{{ route('school.students.template') }}" class="btn btn-link">Download Template</a>
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Upload Excel File</label>
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

    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('attendanceDonutChart').getContext('2d');
        const percentage = {{ $formattedAttendancePercentageSchool }};
        const remaining = 100 - percentage;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [percentage, remaining],
                    backgroundColor: ['#4CAF50', '#E0E0E0'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            },
            plugins: [{
                id: 'centerText',
                afterDraw(chart) {
                    const { ctx, width, height } = chart;
                    ctx.save();
                    ctx.font = 'bold 24px sans-serif';
                    ctx.fillStyle = '#333';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(percentage + '%', width / 2, height / 1.9);
                    ctx.restore();
                }
            }]
        });
    });
    </script>
</x-app-layout>
@endrole
