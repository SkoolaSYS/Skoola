@role('parent')
<x-app-layout>
    @slot('title')
    Edit Student Profile
    @endslot 
    <x-card>
        <!--begin::Content menu-->
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">{{ __('messages.studentlist') }}</h1>
            </div>
        </div>

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ __('messages.studentprofiledetails') }}</h3>
                </div>
            </div>

            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" class="form" action="{{ route ('student.update', ['student' => $student->id])}}" method="POST">
                    @csrf
                    <div class="card-body border-top p-9">
                        <!-- Full Name -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.fullname') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="name" class="form-control form-control-lg form-control-solid" value="{{ $student->name }}" />
                            </div>
                        </div>

                        <!-- IC -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.ic') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="ic" class="form-control form-control-lg form-control-solid" value="{{ $student->ic }}" />
                            </div>
                        </div>

                        <!-- Birth Certificate Number -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.birthcertno') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="birth_cert_no" class="form-control form-control-lg form-control-solid" value="{{ $student->birth_cert_no ?? '' }}" />
                            </div>
                        </div>

                        <!-- Date of Birth + Age -->
                        <div class="row mb-6 align-items-center">
                            <!-- DOB Label -->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.dob') }}</label>

                            <!-- DOB Input -->
                            <div class="col-lg-4 fv-row">
                                <input type="date" name="dob" id="dob" 
                                    class="form-control form-control-lg form-control-solid" 
                                    value="{{ $student->dob }}" />
                            </div>

                            <!-- Age (Label + Input close together) -->
                            <div class="col-lg-4 d-flex align-items-center">
                                <label class="me-2 fw-semibold fs-6">{{ __('messages.age') }}:</label>
                                <input type="text" id="age" 
                                    class="form-control form-control-lg form-control-solid w-25" 
                                    value="{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->age : '' }}" 
                                    readonly />
                            </div>
                        </div>



                        <!-- Tingkatan / Darjah -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.grade') }}</label>
                            <div class="col-lg-8 fv-row">
                                <select name="grade" class="form-select form-select-lg form-select-solid">
                                    <option value="">-- {{ __('messages.select') }} --</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="Darjah {{ $i }}" {{ old('grade', $student->grade) == "Darjah $i" ? 'selected' : '' }}>Darjah {{ $i }}</option>
                                    @endfor
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="Tingkatan {{ $i }}" {{ old('grade', $student->grade) == "Tingkatan $i" ? 'selected' : '' }}>Tingkatan {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Class -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.class') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="class_name" class="form-control form-control-lg form-control-solid" value="{{ $student->class_name ?? '' }}" />
                            </div>
                        </div>


                        <!-- Gender -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.gender') }}</label>
                            <div class="col-lg-8 fv-row">
                                <select name="gender" class="form-select form-select-lg form-select-solid">
                                    <option value="">-- {{ __('messages.selectgender') }} --</option>
                                    <option value="Male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                    <option value="Female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Race -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.race') }}</label>
                            <div class="col-lg-8 fv-row">
                                <select name="race" class="form-select form-select-lg form-select-solid">
                                    <option value="">-- {{ __('messages.selectrace') }} --</option>
                                    @foreach (['Melayu','Chinese','Indian','Bumiputera Sabah','Bumiputera Sarawak','Orang Asli','Others'] as $race)
                                        <option value="{{ $race }}" {{ old('race', $student->race) == $race ? 'selected' : '' }}>{{ $race }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Religion -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.religion') }}</label>
                            <div class="col-lg-8 fv-row">
                                <select name="religion" class="form-select form-select-lg form-select-solid">
                                    <option value="">-- {{ __('messages.selectreligion') }} --</option>
                                    @foreach (['Islam','Christianity','Buddhism','Hinduism','Sikhism','Taoism','Others'] as $religion)
                                        <option value="{{ $religion }}" {{ old('religion', $student->religion) == $religion ? 'selected' : '' }}>{{ $religion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Nationality -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.nationality') }}</label>
                            <div class="col-lg-8 fv-row">
                                <select name="nationality" class="form-select form-select-lg form-select-solid">
                                    <option value="">-- {{ __('messages.select') }} --</option>
                                    <option value="Malaysian" {{ old('nationality', $student->nationality) == 'Malaysian' ? 'selected' : '' }}>Malaysian</option>
                                    <option value="Non-Malaysian" {{ old('nationality', $student->nationality) == 'Non-Malaysian' ? 'selected' : '' }}>Non-Malaysian</option>
                                </select>
                            </div>
                        </div>

                        <!-- Orphan -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.orphan') }}</label>
                            <div class="col-lg-8 fv-row">
                                <select name="orphan" class="form-select form-select-lg form-select-solid">
                                    <option value="">-- {{ __('messages.select') }} --</option>
                                    <option value="Yes" {{ old('orphan', $student->orphan) == 'Yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                    <option value="No" {{ old('orphan', $student->orphan) == 'No' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.address') }}</label>
                            <div class="col-lg-8 fv-row">
                                <textarea name="address" class="form-control form-control-lg form-control-solid">{{ $student->address ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- OKU -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">OKU</label>
                            <div class="col-lg-8 fv-row">
                                <select name="oku" class="form-select form-select-lg form-select-solid">
                                    <option value="">-- Select --</option>
                                    <option value="Yes" {{ old('oku', $student->oku) == 'Yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                    <option value="No" {{ old('oku', $student->oku) == 'No' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                </select>
                            </div>
                        </div>

                        @livewire('ppd-dropdown', ['selectedSchool' => $school->id])
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('student.show')}}" class="btn btn-light btn-active-light-primary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </x-card>
</x-app-layout>
@endrole

@push('scripts')
<script>
    document.getElementById('dob').addEventListener('change', function() {
        let dob = new Date(this.value);
        if (!isNaN(dob.getTime())) {
            let today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            let monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        } else {
            document.getElementById('age').value = '';
        }
    });
</script>
@endpush

