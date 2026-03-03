@role('parent')
<x-app-layout>
	@slot('title')
	Student List
	@endslot
	<x-card>
		@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

		<!--begin::Content menu-->
		<!--begin::Toolbar wrapper-->
		<div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">{{ __('messages.studentlist') }}</h1>
				<!--end::Title-->
			</div>
			<!--end::Page title-->
			<!-- Add Student button -->
			<div class="ms-auto">
				<!-- <a href="{{ route('student.create') }}" class="btn btn-primary">
					<i class="ki-duotone ki-plus fs-2"></i>{{ __('messages.addstudent') }}
				</a> -->
			</div>
		</div>
		<!--end::Toolbar wrapper-->
		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
					<livewire:student-table />
				</div>
			</div>
		</div>
	</x-card>
</x-app-layout>
@endrole
