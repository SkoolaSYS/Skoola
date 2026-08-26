

@role('parent')

<x-app-layout>
	@slot('title')
	Edit Profile
	@endslot 
	<x-card>
		<!--begin::Content menu-->
		<!--begin::Toolbar wrapper-->
		

		<div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">{{ __('messages.acc') }}</h1>
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
					<h3 class="fw-bold m-0">{{ __('messages.editprofile') }}</h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_profile_details" class="collapse show">
				<!--begin::Form-->
				<form id="kt_account_profile_details_form" class="form" action="profile/update" method="POST">
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
								<input type="text" name="id" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" value="{{$user['id']}}" hidden />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.fullname') }}</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Full name" value="{{$user['name']}}" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">Username</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="username" class="form-control form-control-lg form-control-solid" placeholder="Username" value="{{ $user['username'] ?? '' }}" />
							</div>
							<!--end::Col-->
							</div>
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.ic') }}</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="ic" class="form-control form-control-lg form-control-solid" placeholder="NRIC" value="{{$user['ic']}}" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">
								<span class="required">{{ __('messages.phonenum') }}</span>
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
								<input type="tel" name="phone_num" class="form-control form-control-lg form-control-solid" placeholder="Phone Number" value="{{$user['phone_num']}}" required />
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
								<input type="email" name="email" class="form-control form-control-lg form-control-solid" placeholder="Email" value="{{$user['email']}}" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.occupation') }}</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="occupation" class="form-control form-control-lg form-control-solid" placeholder="Occupation" value="{{ $user['occupation'] ?? '' }}" />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->

						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.relay') }}</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<select name="relationship" class="form-control form-control-lg form-control-solid">
									<option value="">-- {{ __('messages.selectrelay') }} --</option>
									<option value="Father" {{ (isset($user['relationship']) && $user['relationship'] == 'Father') ? 'selected' : '' }}>
    									{{ __('messages.father') }}</option>

									<option value="Mother" {{ (isset($user['relationship']) && $user['relationship'] == 'Mother') ? 'selected' : '' }}>
    								{{ __('messages.mother') }}</option>

									<option value="Guardian" {{ (isset($user['relationship']) && $user['relationship'] == 'Guardian') ? 'selected' : '' }}>
    								{{ __('messages.guardian') }}</option>

								</select>
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->


						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.address') }}</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="address" class="form-control form-control-lg form-control-solid" placeholder="Address" value="{{$user['address']}}" required />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						@if(!$postcode == NULL)
						@livewire('malaysia-state', ['selectedPostcode' => $postcode->id])
						@else
						@livewire('malaysia-state')
						@endif
					</div>
					<!--end::Card body-->
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<a href="{{ route('profile.show')}}" class="btn btn-light btn-active-light-primary me-2">Cancel</a>
						<button type="submit" wire:loading.attr="disabled" class="btn btn-primary" id="kt_account_profile_details_submit">{{ __('messages.save') }}</button> <!--Navigate back to Profile Page-->
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