@php
    $prefix = $prefix ?? '';
    $index = $index ?? 0;
@endphp

<div class="guardian-form mb-4">
    <h4>Guardian {{ $index + 1 }}</h4>

    <!-- Full Name -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Full Name</label>
        <div class="col-lg-8 fv-row">
            <input type="text" name="guardians[{{ $index }}][name]" class="form-control" placeholder="Full Name">
        </div>
    </div>
        <!-- Username -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Username</label>
        <div class="col-lg-8 fv-row">
            <input type="text" name="guardians[{{ $index }}][username]" class="form-control" placeholder="Username">
        </div>
    </div>
    <!-- Email -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Email</label>
        <div class="col-lg-8 fv-row">
            <input type="email" name="guardians[{{ $index }}][email]" class="form-control" placeholder="Email">
        </div>
    </div>
    <!-- Phone Number -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Phone Number</label>
        <div class="col-lg-8 fv-row">
        <input type="text" name="guardians[{{ $index }}][phone_num]" class="form-control">
        </div>
    </div>

    <!-- IC / Passport -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">IC / Passport</label>
        <div class="col-lg-8 fv-row">
        <input type="text" name="guardians[{{ $index }}][ic]" class="form-control">
        </div>
    </div>

    <!-- Relationship -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Relationship</label>
        <div class="col-lg-8 fv-row">
        <select name="guardians[{{ $index }}][relationship]" class="form-control">
            <option value="">Select Relationship</option>
            <option value="father">Father</option>
            <option value="mother">Mother</option>
            <option value="guardian">Guardian</option>
        </select>
        </div>
    </div>

    <!-- Occupation -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Occupation</label>
        <div class="col-lg-8 fv-row">
        <input type="text" name="guardians[{{ $index }}][occupation]" class="form-control">
        </div>
    </div>

    <!-- Address -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Address</label>
        <div class="col-lg-8 fv-row">
        <input type="text" name="guardians[{{ $index }}][address]" class="form-control">
        </div>
    </div>

    <!-- State, City, Postcode -->
    <div class="row mb-6">
        
        @livewire('malaysia-state', ['prefix' => "guardians[$index]"], key("guardian-$index"))
        
    </div>

    <!-- Password -->
    <div class="row mb-6" data-kt-password-meter="true">
        <label class="col-lg-4 col-form-label">Password</label>
        <div class="col-lg-8 fv-row">
        <div class="position-relative mb-3">
            <input type="password" 
                name="guardians[{{ $index }}][password]" 
                class="form-control bg-transparent" 
                required 
                autocomplete="new-password"
                data-kt-password-meter-control="input" />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" 
                data-kt-password-meter-control="visibility">
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

    <!-- Repeat Password -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label">Repeat Password</label>
        <div class="col-lg-8 fv-row">
        <input type="password" 
            name="guardians[{{ $index }}][password_confirmation]" 
            class="form-control bg-transparent" 
            required 
            autocomplete="new-password" />
            </div>
    </div>
</div>
