@role('parent')
<x-app-layout>
	@slot('title')
	Add Secondary Parent
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
		<!--end::Toolbar wrapper-->
		<!--end::Toolbar-->
		<!--begin::Basic info-->
		<div class="card mb-5 mb-xl-10">
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">Additional Guardian's Profile Details</h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_profile_details" class="collapse show">
				<!--begin::Form-->
				<form id="kt_account_profile_details_form" class="form" action="{{ route ('profile.guardian.store')}}" method="POST">
					@csrf
					<!--begin::Card body-->
					<div class="card-body border-top p-9">
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label required fw-semibold fs-6" hidden>User ID</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="id" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" value="" hidden />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Full name" value="" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!-- Username -->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required">Username</label>
							<div class="col-lg-8 fv-row">
								<input type="text" name="username" class="form-control form-control-lg form-control-solid" placeholder="Username" required>
							</div>
						</div>
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">Identification Card</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="ic" class="form-control form-control-lg form-control-solid" placeholder="NRIC" value="" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">
								<span class="required">Phone Number</span>
								<span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active">
									<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
							</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="tel" name="phone_num" class="form-control form-control-lg form-control-solid" placeholder="Phone Number" value="" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">
								<span class="required">Email</span>
								<span class="ms-1" data-bs-toggle="tooltip" title="Email must be active and in correct format with an @">
									<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
							</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="email" name="email" class="form-control form-control-lg form-control-solid" placeholder="Email" value="" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!-- Occupation -->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required">Occupation</label>
							<div class="col-lg-8 fv-row">
								<input type="text" name="occupation" class="form-control form-control-lg form-control-solid" placeholder="Occupation" required>
							</div>
						</div>
						<!-- Relationship -->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required">Relationship</label>
							<div class="col-lg-8 fv-row">
								<select name="relationship" class="form-control form-control-lg form-control-solid" required>
									<option value="">Select Relationship</option>
									<option value="Father">Father</option>
									<option value="Mother">Mother</option>
									<option value="Guardian">Guardian</option>
								</select>
							</div>
						</div>
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="address" class="form-control form-control-lg form-control-solid" placeholder="Address" value="" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<livewire:malaysia-state />
						<div class="row mb-6">
						<label class="col-lg-4 col-form-label required">Password</label>
						<div class="col-lg-8 fv-row" data-kt-password-meter="true">
							<div class="position-relative mb-3">
								<input type="password" 
									name="password" 
									class="form-control form-control-lg form-control-solid" 
									placeholder="Password" 
									required 
									autocomplete="new-password" />
								<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
									<i class="ki-duotone ki-eye-slash fs-2"></i>
									<i class="ki-duotone ki-eye fs-2 d-none"></i>
								</span>
							</div>

							<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
							</div>

							<div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required">Repeat Password</label>
						<div class="col-lg-8 fv-row">
							<input type="password" 
								name="password_confirmation" 
								class="form-control form-control-lg form-control-solid" 
								placeholder="Repeat Password" 
								required 
								autocomplete="new-password" />
						</div>
					</div>

					</div>
					<!--end::Card body-->

					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<a href="{{ route('profile.show')}}" class="btn btn-light btn-active-light-primary me-2">Cancel</a>
						<button type="submit" wire:loading.attr="disabled" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button> <!--Navigate back to Profile Page-->
						<!-- <div wire:loading>
							Loading..
						</div> -->
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Basic info-->
		<!--end::Body-->
	</x-card>
</x-app-layout>
@endrole