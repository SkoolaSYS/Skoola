<div>
    <!--begin::Input group-->
    <div class="row mb-6">
        <!--begin::Label-->
        <label class="col-lg-4 col-form-label required fw-semibold fs-6">State</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8 fv-row">
            <!--begin::Col-->
            <select name="{{ $prefix ? $prefix . '[state]' : 'state' }}" wire:model="selectedState" class="form-select form-select-solid form-select-lg fw-semibold">
                <option value="" selected>Select State</option>
                @foreach($states as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
            </select>
            <!--end::Col-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    @if (!is_null($selectedState))
    <!--begin::Input group-->
    <div class="row mb-6">
        <!--begin::Label-->
        <label class="col-lg-4 col-form-label fw-semibold fs-6">District</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[district]' : 'district' }}" wire:model="selectedDistrict" class="form-select form-select-solid form-select-lg fw-semibold">
                <option value="" selected>Select District</option>
                @foreach($districts as $district)
                <option value="{{ $district->id }}">{{ $district->ppd }}</option>
                @endforeach
            </select>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    @endif
    
    @if (!is_null($selectedDistrict))
    <!--begin::Input group-->
    <div class="row mb-6">
        <!--begin::Label-->
        <label class="col-lg-4 col-form-label fw-semibold fs-6">School</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[school]' : 'school' }}" wire:model="selectedSchool" class="form-select form-select-solid form-select-lg fw-semibold">
                <option value="">Select a School</option>
                @foreach ($schools as $school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    @endif
</div>