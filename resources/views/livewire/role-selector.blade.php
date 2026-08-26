<div class="row mb-6">
    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Roles</label>
    <div class="col-lg-8 fv-row ">
        <div class="row-lg-8 mb-7">
            <div class="form-check form-check-inline">
                <input wire:model="selectedRole" class="form-check-input" type="radio" name="roles[]" value="1" checked />
                <label class="form-check-label">
                    Admin Level
                    <span class="ms-1" data-bs-toggle="tooltip" title="View all Admin, Country, State, PPD, School, Student Attendance pages only">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
            </div>
        </div>
        <div class="row-lg-8 mb-7">
            <div class="form-check form-check-inline">
                <input wire:model="selectedRole" class="form-check-input" type="radio" name="roles[]" value="2" />
                <label class="form-check-label" for="roleCheckbox2">
                    Country Level
                    <span class="ms-1" data-bs-toggle="tooltip" title="View all Country, State, PPD, School, Student Attendance pages only">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
            </div>
        </div>
        <div class="row-lg-8 mb-7">
            <div class="form-check form-check-inline">
                <input wire:model="selectedRole" class="form-check-input" type="radio" name="roles[]" value="3" />
                <label class="form-check-label" for="roleCheckbox3">
                    State Level
                    <span class="ms-1" data-bs-toggle="tooltip" title="View specific State, PPD, School, Student Attendance pages only">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                @if ($selectedRole == 3)
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-8 col-form-label required fw-semibold fs-6">Choose which State view</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-12 fv-row">
                        <!--begin::Col-->
                        <select name="state" class="form-select form-select-solid form-select-lg fw-semibold">
                            <option value="" selected>Select State</option>
                            @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                        <!--end::Col-->
                    </div>
                    <!--end::Col-->
                </div>
                @endif
            </div>
        </div>
        <div class="row-lg-8 mb-7">
            <div class="form-check form-check-inline">
                <input wire:model="selectedRole" class="form-check-input" type="radio" name="roles[]" value="4" />
                <label class="form-check-label" for="roleCheckbox4">
                    PPD Level
                    <span class="ms-1" data-bs-toggle="tooltip" title="View specific PPD, School, Student Attendance pages only">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                @if ($selectedRole == 4)
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-8 col-form-label required fw-semibold fs-6">Choose which PPD view</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-12 fv-row">
                        <!--begin::Col-->
                        <select name="ppd" class="form-select form-select-solid form-select-lg fw-semibold">
                            <option value="" selected>Select PPD</option>
                            @foreach($ppds as $ppd)
                            <option value="{{ $ppd->id }}">{{ $ppd->ppd }}</option>
                            @endforeach
                        </select>
                        <!--end::Col-->
                    </div>
                    <!--end::Col-->
                </div>
                @endif
            </div>
        </div>
        <div class="row-lg-8 mb-7">
            <div class="form-check form-check-inline">
                <input wire:model="selectedRole" class="form-check-input" type="radio" name="roles[]" value="5" />
                <label class="form-check-label" for="roleCheckbox5">
                    School Level
                    <span class="ms-1" data-bs-toggle="tooltip" title="View specific School, Student Attendance pages only">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                @if ($selectedRole == 5)
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-8 col-form-label required fw-semibold fs-6">Choose which School view</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-12 fv-row">
                        <!--begin::Col-->
                        <select name="school" class="form-select form-select-solid form-select-lg fw-semibold">
                            <option value="" selected>Select School</option>
                            @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                        <!--end::Col-->
                    </div>
                    <!--end::Col-->
                </div>
                @endif
            </div>
        </div>
        <div class="row-lg-8 mb-7">
            <div class="form-check form-check-inline">
                <input wire:model="selectedRole" class="form-check-input" type="radio" name="roles[]" value="6" />
                <label class="form-check-label" for="roleCheckbox6">
                    Parent Level
                    <span class="ms-1" data-bs-toggle="tooltip" title="View all Parent pages only">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
            </div>
        </div>
    </div>