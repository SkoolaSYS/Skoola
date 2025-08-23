@php
    $prefix = $prefix ?? '';
    $key = $key ?? null;
@endphp
<!--begin::Card body-->
<div class="card-body border-top p-9">
    <!--begin::Input group-->
    <div class="row mb-6">
        <!--begin::Label-->
        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8 fv-row">
            <input type="text" name="{{ $prefix ? $prefix . '[name]' : 'name' }}" class="form-control form-control-lg form-control-solid" placeholder="Full Name" />
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-6">
        <!--begin::Label-->
        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Identification Card</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8 fv-row">
            <input type="text" name="{{ $prefix ? $prefix . '[ic]' : 'ic' }}" class="form-control form-control-lg form-control-solid" placeholder="Identification Card" />
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    @livewire('ppd-dropdown', ['prefix' => $prefix, 'key' => $key])
</div>
<!--end::Card body--> 