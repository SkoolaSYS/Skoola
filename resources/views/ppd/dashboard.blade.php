@role('admin|country|state|ppd')
<x-app-layout>
    @slot ('title')
    PPD Dashboard
    @endslot
    <x-card title=''>
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100"> </div>
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!--begin::Chart widget 22-->
            <div class="card h-xl-100 shadow-sm p-3 mb-5 bg-white rounded">
                <!--begin::Header-->
                <div class="card-header position-relative py-0 border-bottom-2 justify-content-center">
                    <span class="d-flex flex-column justify-content-center">
                        <h1 class="page-heading text-dark fw-bolder fs-1 m-0 text-center">Dashboard</h1>
                        <h6 class="text-muted text-center fs-7">PPD Level</h6>
                    </span>
                    <!--end::Nav-->
                </div>
                <!--end::Header-->
                <div class="card-body pb-4">
                    <!--begin::Tab Content-->
                    <!--begin::Tap pane-->
                    <div class="row">
                        <div class="d-flex flex-wrap flex-md-nowrap pb-4">
                            <!--begin::Container-->
                            <div class="mx-auto">
                                <div class="card" style="width: 37rem;">
                                    <div class="card-header position-relative py-0 border-bottom-2">
                                        <span class="d-flex flex-column justify-content-center">
                                            <h1 class="page-heading text-dark fw-bolder fs-2 m-0 text-center">{{$totalPelajarPPD}}</h1>
                                            <h6 class="text-muted text-center fs-7">Total Students</h6>
                                        </span>
                                        <span class="d-flex flex-column justify-content-center">
                                            <h1 class="page-heading text-dark fw-bolder fs-2 m-0 text-center">{{$formattedAttendancePercentagePPD}}%</h1>
                                            <h6 class="text-muted text-center fs-7">Average Attendance</h6>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Tap pane-->
                </div>
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
                            <!--begin::Page title-->
                            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                                <!--begin::Title-->
                                <h3 class="text-2xl font-semibold mb-3">LIST OF SCHOOLS IN {{$ppd->ppd}}</h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Page title-->
                            <a href="{{ route('dashboard.ppd_export',['ppd_id' => $ppd_id])}}" type="button" class="btn btn-light-success me-3">
                                <i class="ki-duotone ki-exit-up fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>Export to Excel
                            </a>
                        </div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                            @livewire('school-table', ['ppd_id' => $ppd_id])
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Row-->
    </x-card>
</x-app-layout>
@endrole