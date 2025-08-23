@role('admin')
<x-app-layout>
    @slot('title')
    User List
    @endslot
    <x-card>
        <!--begin::Content menu-->
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100 justify-end"> 
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">User List</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!-- Add User button -->
            <div>
                <a href="{{ route('admin.add') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>Create User
                </a>
            </div>
        </div>
        <!--end::Toolbar wrapper-->
        <!--end::Content menu-->
        @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
        @endif
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <livewire:user-table />
                </div>
            </div>
        </div>
    </x-card>
</x-app-layout>
@endrole