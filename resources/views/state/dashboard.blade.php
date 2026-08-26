@role('admin|country|state|ppd|school')
<x-app-layout>
    @slot ('title')
    State Dashboard
    @endslot
    <x-card title=''>
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100"> </div>
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!--begin::Chart widget 22-->
            <div class="card h-xl-100 shadow-sm p-3 mb-5 bg-white rounded">
                <!--begin::Header-->
                <div class="card-header position-relative py-0 border-bottom-2 justify-content-center">
                    <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
                        <span class="d-flex flex-column col-md-2">
                        </span>
                        <span class="d-flex flex-column col-md-2">
                            <h1 class="page-heading text-dark fw-bolder fs-1 m-0 text-center">Dashboard</h1>
                            <h6 class="text-muted text-center fs-7">State Level</h6>
                        </span>
                        <span class="d-flex flex-column col-md-2">
                            <a href="{{ route('dashboard.state_export',['state_id' => $state_id])}}" type="button" class="btn btn-light-success me-3">
                                <i class="ki-duotone ki-exit-up fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>Export to Excel
                            </a>
                        </span>
                        <!--end::Nav-->
                    </div>
                    <!--end::Header-->
                </div>
                <div class="card-body pb-3">
                    <!--begin::Tab Content-->
                    <!--begin::Tap pane-->
                    <div class="row">
                        <div class="d-flex flex-wrap flex-md-nowrap">
                            <!--begin::Container-->
                            <div class="mx-auto">
                                <div class="card" style="width: 37rem;">
                                    <div class="card-header position-relative py-0 border-bottom-2">
                                        <span class="d-flex flex-column justify-content-center">
                                            <h1 class="page-heading text-dark fw-bolder fs-2 m-0 text-center">{{$totalPelajar}}</h1>
                                            <h6 class="text-muted text-center fs-7">Total Students</h6>
                                        </span>
                                        <span class="d-flex flex-column justify-content-center">
                                            <h1 class="page-heading text-dark fw-bolder fs-2 m-0 text-center">{{$formattedAttendancePercentageState}}%</h1>
                                            <h6 class="text-muted text-center fs-7">Average Attendance</h6>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 mb-5">
                    <span class="d-flex flex-column justify-content-center">
                        <h1 class="page-heading text-dark fw-bolder fs-2 m-0 text-center">List of PPD in {{$state->name}}</h1>
                    </span>
                </div>
                <div class="row">
                    @foreach($districts as $district)
                    <div class="col-md-4 mb-4">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-wrap flex-md-nowrap">
                            <!--begin::Container-->
                            <div class="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pt-3 pb-10">
                                <div class="mx-auto">
                                    <!--begin::Label-->

                                    <div class="card shadow-sm p-3 mb-5 bg-white rounded" style="width: 20rem;">
                                        <div class="card-body">
                                            <div class="card-header justify-content-center">
                                                <div class="page-heading d-flex flex-column justify-content-center text-center text-dark fw-bolder fs-4">{{$district->ppd}}</div>
                                                <!--end::Title-->
                                                <!--end::Label-->
                                            </div>
                                            <!--begin::Label-->
                                            <div class="card-body pb-3">
                                                <!--begin::Tab Content-->
                                                <div class="tab-content">
                                                    <!--begin::Tap pane-->
                                                    <div class="tab-pane fade show active">
                                                        <!--begin::Wrapper-->
                                                        <div class="d-flex flex-wrap flex-md-nowrap">
                                                            <!--begin::Container-->
                                                            <div class="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pb-4">
                                                                <div class="mx-auto">
                                                                    <div class="fs-5 fw-semibold">{{$district->totalStudents}} students</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-wrap flex-md-nowrap">
                                                            <!--begin::Container-->
                                                            <div class="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pb-0">
                                                                <div class="mx-auto">
                                                                    <a href="{{ route('dashboard.ppd', $district->id)}}" class="btn btn-sm btn-primary align-self-center">View</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Labels-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    @endforeach
                </div> <!-- Close current row -->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Row-->
    </x-card>
</x-app-layout>
@endrole