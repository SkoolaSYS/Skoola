@php
    $prefix = $prefix ?? '';
    $key = $key ?? null;
@endphp

<div class="card-body border-top p-9">
    <div id="student-forms-container">
    <div class="student-form">
    <div class="card-body border-top p-9">

    <!-- Full Name -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
        <div class="col-lg-8 fv-row">
            <input type="text" name="{{ $prefix ? $prefix . '[name]' : 'name' }}" class="form-control form-control-lg form-control-solid" placeholder="Full Name" />
        </div>
    </div>

    <!-- Identification Card / Passport -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Birth Certificate Number</label>
        <div class="col-lg-8 fv-row">
            <input type="text" name="{{ $prefix ? $prefix . '[birth_cert_no]' : 'birth_cert_no' }}" class="form-control form-control-lg form-control-solid" placeholder="Birth Certiificate Number" />
        </div>
    </div>

    <!-- Identification Card / Passport -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">IC / Passport Number</label>
        <div class="col-lg-8 fv-row">
            <input type="text" name="{{ $prefix ? $prefix . '[ic]' : 'ic' }}" class="form-control form-control-lg form-control-solid" placeholder="IC / Passport Number" />
        </div>
    </div>

    <!-- Date of Birth & Age -->
<div class="row mb-6">
    <!-- Date of Birth -->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Date of Birth</label>
    <div class="col-lg-8 fv-row">
        <input type="date" 
               name="{{ $prefix ? $prefix . '[dob]' : 'dob' }}" 
               class="form-control form-control-lg form-control-solid dob-input" />
    </div>
</div>


    <!-- Tingkatan / Darjah -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Tingkatan / Darjah</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[grade]' : 'grade' }}" class="form-control form-control-lg form-control-solid">
                <option value="">Select Tingkatan / Darjah</option>
                <option value="Tingkatan 1">Tingkatan 1</option>
                <option value="Tingkatan 2">Tingkatan 2</option>
                <option value="Tingkatan 3">Tingkatan 3</option>
                <option value="Tingkatan 4">Tingkatan 4</option>
                <option value="Tingkatan 5">Tingkatan 5</option>
                <option value="Tingkatan 5">Tingkatan 6</option>
                <option value="Darjah 1">Darjah 1</option>
                <option value="Darjah 2">Darjah 2</option>
                <option value="Darjah 3">Darjah 3</option>
                <option value="Darjah 4">Darjah 4</option>
                <option value="Darjah 5">Darjah 5</option>
                <option value="Darjah 6">Darjah 6</option>
            </select>
        </div>
    </div>

    <!-- Gender -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Gender</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[gender]' : 'gender' }}" class="form-control form-control-lg form-control-solid">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
    </div>

    <!-- Race -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Race</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[race]' : 'race' }}" class="form-control form-control-lg form-control-solid">
                <option value="">Select Race</option>
                <option value="Melayu">Melayu</option>
                <option value="Chinese">Chinese</option>
                <option value="Indian">Indian</option>
                <option value="Bumiputera Sabah">Bumiputera Sabah</option>
                <option value="Bumiputera Sarawak">Bumiputera Sarawak</option>
                <option value="Orang Asli">Orang Asli</option>
                <option value="Others">Others</option>
            </select>
        </div>
    </div>

    <!-- Religion -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Religion</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[religion]' : 'religion' }}" class="form-control form-control-lg form-control-solid">
                <option value="">Select Religion</option>
                <option value="Islam">Islam</option>
                <option value="Christianity">Christianity</option>
                <option value="Buddhism">Buddhism</option>
                <option value="Hinduism">Hinduism</option>
                <option value="Sikhism">Sikhism</option>
                <option value="Taoism">Taoism</option>
                <option value="Others">Others</option>
            </select>
        </div>
    </div>

    <!-- Nationality -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Nationality</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[nationality]' : 'nationality' }}" class="form-control form-control-lg form-control-solid">
                <option value="">Select Nationality</option>
                <option value="Malaysian">Malaysian</option>
                <option value="Non-Malaysian">Non-Malaysian</option>
            </select>
        </div>
    </div>

    <!-- Orphan -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Orphan</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[orphan]' : 'orphan' }}" class="form-control form-control-lg form-control-solid">
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    <!-- Address -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
        <div class="col-lg-8 fv-row">
            <input type="text" name="{{ $prefix ? $prefix . '[address]' : 'address' }}" class="form-control form-control-lg form-control-solid" placeholder="Address" />
        </div>
    </div>

    <!-- OKU -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">OKU</label>
        <div class="col-lg-8 fv-row">
            <select name="{{ $prefix ? $prefix . '[oku]' : 'oku' }}" class="form-control form-control-lg form-control-solid">
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    <h3>School Details</h3>
    
   @livewire('ppd-dropdown', ['prefix' => $prefix], key('ppd-'.$key.'-'.uniqid()))
</div>
    </div>
</div>
</div>








