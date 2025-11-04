<div>
    <!--begin::Input group-->
    <div class="row mb-6">
        <!--begin::Label-->
        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.state') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8 fv-row">
            <!--begin::Col-->
            <select name="state" wire:model="selectedState" aria-label="Select a State" data-placeholder="Select State" class="form-select form-select-solid form-select-lg fw-semibold">
                <option value="">{{ __('messages.selectstate') }}</option>
                @foreach($states as $negeri)
                <option value="{{$negeri->id}}">{{$negeri->name}}</option>
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
        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.city') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8 fv-row">
            <select name="city" wire:model="selectedCity" aria-label="Select a City" data-placeholder="Select City" class="form-select form-select-solid form-select-lg fw-semibold">
                <option value="">{{ __('messages.selectcity') }}</option>
                @foreach($cities as $bandar)
                <option value="{{$bandar->id}}">{{$bandar->name}}</option>
                @endforeach
            </select>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    @endif

    @if (!is_null($selectedCity))
    <!--begin::Input group-->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Postcode</label>
        <div class="col-lg-8 fv-row">
            <select name="postcode" wire:model="selectedPostcode" aria-label="Select a Postcode" data-placeholder="Select Postcode" class="form-select form-select-solid form-select-lg fw-semibold">
                <option value="">{{ __('messages.selectpostcode') }}</option>
                @foreach($postcodes as $pc)
                <option value="{{$pc->id}}">{{$pc->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--end::Input group-->
    @endif
</div>