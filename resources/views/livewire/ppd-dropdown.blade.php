<div>
    <!-- State -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.state') }}</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[state]' : 'state' }}"
                    wire:model="selectedState"
                    class="form-select form-select-solid form-select-lg fw-semibold">
                <option value="">{{ __('messages.selectstate') }}</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- District -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.district') }}</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[district]' : 'district' }}"
                    wire:model="selectedDistrict"
                    class="form-select form-select-solid form-select-lg fw-semibold"
                    {{ $districts->isEmpty() ? 'disabled' : '' }}>
                <option value="">{{ __('messages.selectdistrict') }}</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}">{{ $district->ppd }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- School -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.school') }}</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[school]' : 'school' }}"
                    wire:model="selectedSchool"
                    class="form-select form-select-solid form-select-lg fw-semibold"
                    {{ $schools->isEmpty() ? 'disabled' : '' }}>
                <option value="">{{ __('messages.selectschool') }}</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
