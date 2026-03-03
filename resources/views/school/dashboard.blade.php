@role('admin|country|state|ppd|school')
<x-app-layout>
    @slot('title')
        School Dashboard
    @endslot

    <x-card title=''>
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100"></div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="card h-xl-100 shadow-sm p-3 mb-5 bg-white rounded">

                <!-- Header -->
                <div class="card-header position-relative py-0 border-bottom-2 justify-content-center">
                    <span class="d-flex flex-column justify-content-center">
                        <h1 class="page-heading text-dark fw-bolder fs-1 m-0 text-center">{{ __('messages.dashboard') }}</h1>
                        <h6 class="text-muted text-center fs-7">{{ __('messages.schoollevel') }}</h6>
                    </span>
                </div>

                <!-- Body -->
                <div class="card-body pb-4">
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-5 pb-4 text-center">

                    <!-- Total Students -->
                    <div class="p-4 border-0 bg-transparent">
                        <h1 class="text-dark fw-bolder fs-1 mb-0">{{ $totalPelajar }}</h1>
                        <h6 class="text-muted fs-7">{{ __('messages.totalstudents') }}</h6>
                    </div>

                    <!-- Average Attendance -->
                    <div class="p-4 border-0 bg-transparent">
                        <div style="position: relative; width: 180px; height: 180px; margin: 0 auto;">
                            <canvas id="attendanceDonutChart"></canvas>
                        </div>
                        <h6 class="text-muted fs-7 mb-2">
                            {{ __('messages.avgattendance') }} Tahun {{ now()->year }}
                        </h6>

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

    @push('scripts')
<script>
window.addEventListener('open-student-modal', event => {
    // event.detail contains the payload we dispatched from Livewire
    const data = event.detail.attendanceData || [];
    const studentName = event.detail.studentName || 'Student Attendance';
    const detailUrl = event.detail.detailUrl || '#';

    // set title and detail link
    document.getElementById('studentModalLabel').innerText = "Kehadiran " + studentName;
    document.getElementById('modal_detail_link').setAttribute('href', detailUrl);

    // show modal
    const modalEl = document.getElementById('studentModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // render chart (destroy previous chart if any)
    setTimeout(() => {
        const chartContainer = document.querySelector("#modal_chart");
        if (!chartContainer) return;

        // clear previous chart HTML (if any)
        chartContainer.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            chartContainer.innerHTML = '<p class="text-muted">No attendance data.</p>';
            return;
        }

        // create ApexCharts donut
        const chart = new ApexCharts(chartContainer, {
            series: data,
            chart: { type: 'donut', width: 230, height: 200 },
            labels: [
                "{{ __('messages.present') }}", 
                "{{ __('messages.absent') }}", 
                "{{ __('messages.late') }}"
            ],
            colors: ['#50cd89', '#f1416c', '#ffc107'], // added color for 'late'
            legend: { show: true, position: 'bottom' },
        });


        // render chart, and keep reference if you want to destroy later
        chart.render();
    }, 150);
});
</script>
@endpush


</x-app-layout>
@endrole
