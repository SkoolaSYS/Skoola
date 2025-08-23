<x-app-layout>
    @slot('title')
    Student's Attendance
    @endslot 
    <x-card>
        <!--begin::Content menu-->
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Student's Attendance Log</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <a href="{{ route('attendance.export')}}" type="button" class="btn btn-light-success me-3">
                <i class="ki-duotone ki-exit-up fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Export to Excel
            </a>
        </div>
        <!--end::Toolbar wrapper-->
        <!--end::Content menu-->
        <!--begin::Card-->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <livewire:attendance-table />
                </div>
            </div>
        </div>
    </x-card>
</x-app-layout>