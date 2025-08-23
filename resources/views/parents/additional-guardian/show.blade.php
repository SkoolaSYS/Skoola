@role('parent')
<x-app-layout>
	@slot('title')
	Additional Guardian Profile
	@endslot
	<x-card>
		<!--begin::Content menu-->
		<!--begin::Toolbar wrapper-->
		<div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Account Overview</h1>
				<!--end::Title-->
			</div>
			<!--end::Page title-->
		</div>
		<!--begin::Navbar-->
		<div class="card mb-5 mb-xl-10">
			<div class="card-body pt-9 pb-0">
				<!--begin::Details-->
				<div class="d-flex flex-wrap flex-sm-nowrap">
					<!--begin::Info-->
					<div class="flex-grow-1">
						<!--begin::Title-->
						<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
							<!--begin::User-->
							<div class="d-flex flex-column">
								<!--begin::Name-->
								<div class="d-flex align-items-center mb-2">
									<span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{$guardian->name}}</span>
								</div>
								<!--end::Name-->
								<!--begin::Info-->
								<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
									<a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
										<i class="ki-duotone ki-profile-circle fs-4 me-1">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
										</i>Additional Guardian</a>
									<a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
										<i class="ki-duotone ki-sms fs-4 me-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>{{$guardian->email}}</a>
								</div>
								<!--end::Info-->
							</div>
							<!--end::User-->
						</div>
						<!--end::Title-->
					</div>
					<!--end::Info-->
				</div>
				<!--end::Details-->
			</div>
		</div>
		<!--end::Navbar-->
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
		<!--begin::details View-->
		<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
			<!--begin::Card header-->
			<div class="card-header cursor-pointer">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">Guardian Profile Details</h3>
				</div>
				<!--end::Card title-->
				<!--begin::Actions-->
				<div class="card-footer d-flex justify-content-end py-6 px-9">
					<form class="form" action="{{ route('profile.guardian.delete', $guardian->id) }}" method="POST">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn btn-danger align-self-center me-2" onclick="return confirm('Are you sure you want to delete this guardian?')">Delete Profile</button>
					</form>
					<a href="{{ route('profile.guardian.edit', $guardian->id) }}" class="btn btn-primary">Edit Profile</a>
				</div>
				<!--end::Actions-->
			</div>
			<!--end::Card header-->
			<!--begin::Card body-->
			<div class="card-body p-9">
				<!--begin::Row-->
				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Full Name</label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8">
						<span class="fw-semibold fs-6 text-gray-800">{{$guardian->name}}</span>
					</div>
					<!--end::Col-->
				</div>
				<!--end::Row-->
				<!--begin::Input group-->
				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Identification Card</label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8 fv-row">
						<span class="fw-semibold text-gray-800 fs-6">{{$guardian->ic}}</span>
					</div>
					<!--end::Col-->
				</div>
				<!--end::Input group-->
				<!--begin::Input group-->
				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Phone Number
						<span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active">
							<i class="ki-duotone ki-information fs-7">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i>
						</span></label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8 d-flex align-items-center">
						<span class="fw-semibold fs-6 text-gray-800">{{$guardian->phone_num}}</span>
					</div>
					<!--end::Col-->
				</div>
				<!--end::Input group-->
				<!--begin::Input group-->
				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Email</label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8">
						<span class="fw-semibold fs-6 text-gray-800 text-hover-primary" a href="#">{{$guardian->email}}</a>
							<span class="badge badge-success">Verified</span>
					</div>
					<!--end::Col-->
				</div>
				<!--end::Input group-->
				<!--begin::Input group-->
				<div class="row mb-7">
					<!--begin::Label-->
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Adress</label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8">
						<span class="fw-semibold fs-6 text-gray-800">{{$guardian->address}}</span>
					</div>
					<!--end::Col-->
				</div>
				<!--end::Input group-->
				<!--begin::Input group-->
				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Postcode</label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8">
						<span class="fw-semibold fs-6 text-gray-800">{{$postcode}}</span>
					</div>
					<!--end::Col-->
				</div>
				<!--end::Input group-->
				<!--begin::Input group-->
				<div class="row mb-10">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">City</label>
					<!--begin::Label-->
					<!--begin::Label-->
					<div class="col-lg-8">
						<span class="fw-semibold fs-6 text-gray-800">{{$city}}</span>
					</div>
					<!--begin::Label-->
				</div>
				<!--end::Input group-->
				<!--begin::Input group-->
				<div class="row mb-10">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">State</label>
					<!--begin::Label-->
					<!--begin::Label-->
					<div class="col-lg-8">
						<span class="fw-semibold fs-6 text-gray-800">{{$state}}</span>
					</div>
					<!--begin::Label-->
				</div>
				<!--end::Input group-->
			</div>
			<!--end::Card body-->

			<!--end::Card body-->
		</div>
		<!--end::details View-->
		<!--end::Body-->
	</x-card>
</x-app-layout>
@endrole