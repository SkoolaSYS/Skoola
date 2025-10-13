@role('teacher')
<x-app-layout>
    @slot('title') Dashboard @endslot

    <x-card title="Attendance Dashboard">
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Teacher Dashboard</h1>
            </div>
        </div>

        @foreach($classes as $className => $grade)
        <div class="mb-5 text-center">
            <h4 class="fw-semibold text-dark mb-4">{{ $grade }} - {{ $className }}</h4>
            <div class="row g-3 justify-content-center">
                @foreach(['daily','weekly','monthly'] as $period)
                    <div class="col-lg-4 col-md-6 col-12 text-center mb-4">
                        <h6 class="mb-2 text-muted text-uppercase">{{ ucfirst($period) }}</h6>
                        <div id="chart-{{ $className }}-{{ $period }}" 
                            class="attendance-chart" 
                            style="width: 100%; height: 250px;">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @endforeach

        @push('scripts')
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            let attendanceData = @json($attendanceData);

            Object.keys(attendanceData).forEach(className => {
                ['daily','weekly','monthly'].forEach(period => {
                    let data = attendanceData[className][period]; // [present, absent, late]

                    // Set dynamic height for mobile
                    let chartHeight = 250;
                    if (window.innerWidth < 480) chartHeight = 180;
                    else if (window.innerWidth < 768) chartHeight = 200;

                    new ApexCharts(document.querySelector("#chart-" + className + "-" + period), {
                        series: data,
                        chart: { type: 'donut', height: chartHeight },
                        colors: ['#50cd89','#f1416c','#f5d70f'], // green, red, yellow
                        legend: { show: true, position: 'bottom' },
                        plotOptions: { pie: { size: '70%' } },
                        labels: ['Present','Absent','Late'],
                        responsive: [
                            { breakpoint: 1024, options: { chart: { height: 220 } } },
                            { breakpoint: 768, options: { chart: { height: 200 } } },
                            { breakpoint: 480, options: { chart: { height: 180 } } }
                        ]
                    }).render();
                });
            });
        });

        // Optional: rerender charts on window resize for responsiveness
        window.addEventListener('resize', () => {
            document.querySelectorAll('.attendance-chart').forEach(chartEl => {
                chartEl.innerHTML = ''; // clear chart
            });
            // Re-run the script to redraw charts
            document.dispatchEvent(new Event('DOMContentLoaded'));
        });
        </script>
        @endpush
    </x-card>
</x-app-layout>
@endrole
