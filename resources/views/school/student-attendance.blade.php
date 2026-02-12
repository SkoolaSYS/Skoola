<x-app-layout>
    @slot ('title')
    Dashboard
    @endslot
    <x-card title=''>
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">{{ __('messages.dashboardstudent', ['student' => $student->name]) }}</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!--begin::Chart widget 22-->
            <div class="card h-xl-100">
                <!--begin::Header-->
                <div class="card-header position-relative py-0 border-bottom-2">
                    <!--begin::Nav-->
                    <ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                    </ul>
                    <!--end::Nav-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="card-toolbar" data-kt-buttons="true">
                            <a class="btn btn-sm btn-color-muted btn-active btn-active-primary active px-4 me-1" id="">{{ __('messages.year') }}</a>
                            <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="">{{ __('messages.monthly') }}</a>
                            <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4" id="">{{ __('messages.weekly') }}</a>
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <div class="card-body pb-3">
                    <!--begin::Tab Content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-wrap flex-md-nowrap justify-content-center">
                                <!--begin::Container-->
                                <div class="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pt-3 pb-10">
                                    <!--begin::Title-->
                                    <div class="fs-4 fw-bold text-gray-900 text-center mb-5">{{ __('messages.attendancestudent', ['student' => $student->name]) }}<br /></div>
                                    <!--end::Title-->
                                    <div class="mx-auto mb-4" id="pie_chart_{{ $student->id }}"></div>
                                    <div class="mx-auto">
                                        <!--begin::Label-->
                                        <div class="card" style="width: 10rem;">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <!--begin::Bullet-->
                                                    <div class="bullet bullet-dot w-8px h-7px bg-success me-2" style="color:#50cd89;"></div>
                                                    <!--end::Bullet-->
                                                    <!--begin::Label-->
                                                    <div class="fs-8 fw-semibold text-muted">{{ __('messages.present') }}</div>
                                                    <!--end::Label-->
                                                </div>
                                                <!--end::Label-->
                                                <!--begin::Label-->
                                                <div class="d-flex align-items-center mb-2">
                                                    <!--begin::Bullet-->
                                                    <div class="bullet bullet-dot w-8px h-7px bg-danger me-2" style="color:#f1416c;"></div>
                                                    <!--end::Bullet-->
                                                    <!--begin::Label-->
                                                    <div class="fs-8 fw-semibold text-muted">{{ __('messages.absent') }}</div>
                                                    <!--end::Label-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Labels-->
                                </div>
                                <!--end::Container-->
                            </div>
                            <!-- Close current row -->
                            <div class="d-flex flex-wrap flex-md-nowrap">
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Tap pane-->
                    </div>
                </div>
                <!--end::Chart widget 22-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <!--begin::Chart widget 22-->
                <div class="card h-xl-100">
                    <!--begin::Header-->
                    <div class="card-header position-relative py-0 border-bottom-2">
                        <!--begin::Nav-->
                        <ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                        </ul>
                        <!--end::Nav-->
                    </div>
                    <!--begin::Statements-->
                    <div class="card">
                        <!--begin::Card header-->
                        <div class="card-header cursor-pointer">
                            <!--begin::Card title-->
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">{{ __('messages.listof', ['student' => $student->name]) }}</h3>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Tab Content-->
                        <div id="kt_referred_users_tab_content" class="tab-content">
                            <!--begin::Tab panel-->
                            <div id="kt_referrals_1" class="card-body p-0 tab-pane fade show active" role="tabpanel">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                        <!--begin::Thead-->
                                        <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                            <tr>
                                                <th class="min-w-125px ps-9">{{ __('messages.date') }}</th>
                                                <th class="min-w-125px ps-9">{{ __('messages.checkin') }}</th>
                                                <th class="min-w-125px ps-9">{{ __('messages.checkout') }}</th>
                                                <th class="min-w-150px px-0">{{ __('messages.fullname') }}</th>
                                                <th class="min-w-150px px-0">{{ __('messages.school') }}</th>
                                                <th class="min-w-150px ps-5">Status</th>
                                                <th class="min-w-150px ps-5">{{ __('messages.remarks') }}</th>
                                            </tr>
                                        </thead>
                                        <!--end::Thead-->
                                        <!--begin::Tbody-->
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            @forelse($attendanceList as $attendance)
                                            <tr>
                                                <td class="ps-9">{{$attendance->date}}</td>
                                                <td class="ps-9">{{$attendance->check_in}}</td>
                                                <td class="ps-9">{{$attendance->check_out}}</td>
                                                <td class="ps-0">{{$attendance->student->name}}</td>
                                                <td class="ps-0">{{$attendance->student->school->name}}</td>

                                                @if ($attendance->status == 'attend')
                                                <td class="text-center">
                                                    <a class="badge status-badge" style="background-color:#50cd89;">{{ __('messages.present') }}</a>
                                                </td>
                                                @else
                                                <td class="text-center">
                                                    <a class="badge status-badge" style="background-color:#f1416c;">{{ __('messages.absent') }}</a>
                                                </td>
                                                @endif
                                                @if ($attendance->remarks == NULL)
                                                <td class="ps-9">-</td>
                                                @else
                                                <td class="ps-9">{{$attendance->remarks}}</td>
                                                @endif
                                                <td class="ps-9">
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-center" colspan="8">{{ __('messages.noattendance') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <!--end::Tbody-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Tab panel-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::details View-->
                </div>
            </div>
        </div>
        <!--end::Row-->
        @push('scripts')
<script>
    // Use the full attendance list
    const attendanceList = @json($attendanceList); // contains date, status, etc.
    let chart;

    // Filter attendance by period
    function filterAttendance(period) {
        const now = new Date();
        let startDate;

        switch(period) {
            case 'week':
                startDate = new Date();
                startDate.setDate(now.getDate() - 7);
                break;
            case 'month':
                startDate = new Date();
                startDate.setMonth(now.getMonth() - 1);
                break;
            case 'year':
            default:
                startDate = new Date();
                startDate.setFullYear(now.getFullYear() - 1);
                break;
        }

        // Filter by attendance date
        const filtered = attendanceList.filter(a => new Date(a.date) >= startDate);

        // Count status
        const attend = filtered.filter(a => a.status === 'attend').length;
        const absent = filtered.filter(a => a.status === 'absent').length;

        return [attend, absent];
    }

    // Render or update chart
    function renderChart(series) {
        const options = {
            series: series,
            chart: { type: 'donut', width: 230, height: 200 },
            colors: ['#50cd89', '#f1416c'],
            legend: { show: false },
            plotOptions: { pie: { size: '50%' } },
            responsive: [{ breakpoint: 480, options: { chart: { width: 150, height: 150 } } }]
        };

        if(chart) {
            chart.updateSeries(series);
        } else {
            chart = new ApexCharts(document.querySelector("#pie_chart_{{ $student->id }}"), options);
            chart.render();
        }
    }

    // Hook buttons
    const btns = document.querySelectorAll('.card-toolbar a');
    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            btns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            let periodKey = 'year';
            const text = btn.textContent.toLowerCase();
            if(text.includes('month')) periodKey = 'month';
            if(text.includes('week')) periodKey = 'week';

            const series = filterAttendance(periodKey);
            renderChart(series);
        });
    });

    // Initial chart: yearly
    renderChart(filterAttendance('year'));
</script>
@endpush


    </x-card>
</x-app-layout>