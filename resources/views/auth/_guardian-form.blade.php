@php
    $prefix = $prefix ?? '';
    $index = $index ?? 0;
@endphp

<div class="guardian-form mb-4">
    <h4>Guardian {{ $index + 1 }}</h4>

    <input type="text" name="guardians[{{ $index }}][name]" placeholder="Full Name" class="form-control mb-2">
    <input type="text" name="guardians[{{ $index }}][username]" placeholder="Username" class="form-control mb-2">
    <input type="email" name="guardians[{{ $index }}][email]" placeholder="Email" class="form-control mb-2">
    <input type="text" name="guardians[{{ $index }}][phone_num]" placeholder="Phone Number" class="form-control mb-2">
    <input type="text" name="guardians[{{ $index }}][ic]" placeholder="IC / Passport" class="form-control mb-2">

    <select name="guardians[{{ $index }}][relationship]" class="form-control mb-2">
        <option value="">Relationship</option>
        <option value="father">Father</option>
        <option value="mother">Mother</option>
        <option value="guardian">Guardian</option>
    </select>

    <input type="text" name="guardians[{{ $index }}][occupation]" placeholder="Occupation" class="form-control mb-2">
    <input type="text" name="guardians[{{ $index }}][address]" placeholder="Address" class="form-control mb-2">

    <div class="fv-row mb-8" data-kt-password-meter="true">
        <div class="mb-1">
            <div class="position-relative mb-3">
                <input type="password" placeholder="Password" name="{{ $prefix }}[password]" class="form-control bg-transparent" autocomplete="new-password" />
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
        </div>
        <div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
    </div>

    <div class="fv-row mb-8">
        <input type="password" placeholder="Repeat Password" name="{{ $prefix }}[password_confirmation]" class="form-control bg-transparent" autocomplete="new-password" />
    </div>
</div>
