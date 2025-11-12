<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('messages.editstudent') }}</h5>
                <a href="{{ route('dashboard.school', $student->school_id) }}" class="btn btn-light btn-sm">
                    ← Back
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('school.students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('messages.fullname') }}</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $student->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- IC Number --}}
                    <div class="mb-3">
                        <label for="ic" class="form-label">{{ __('messages.ic') }}</label>
                        <input type="text" name="ic" id="ic"
                            class="form-control @error('ic') is-invalid @enderror"
                            value="{{ old('ic', $student->ic) }}" required>
                        @error('ic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Birth Cert Number --}}
                    <div class="mb-3">
                        <label for="birth_cert_no" class="form-label">{{ __('messages.birthcertno') }}</label>
                        <input type="text" name="birth_cert_no" id="birth_cert_no"
                            class="form-control @error('birth_cert_no') is-invalid @enderror"
                            value="{{ old('birth_cert_no', $student->birth_cert_no) }}" >
                        @error('birth_cert_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DOB --}}
                    <div class="mb-3">
                        <label for="dob" class="form-label">{{ __('messages.dob') }}</label>
                        <input type="date" name="dob" id="dob"
                            class="form-control @error('dob') is-invalid @enderror"
                            value="{{ old('dob', $student->dob) }}" required>
                        @error('dob')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Grade --}}
                    <div class="mb-3">
                        <label for="grade" class="form-label">{{ __('messages.grade') }}</label>
                        <select name="grade" id="grade" class="form-select @error('grade') is-invalid @enderror" required>
                            <option value="">-- Select Darjah/Tingkatan --</option>

                            <optgroup label="Darjah">
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="Darjah {{ $i }}" {{ old('grade', $student->grade) == 'Darjah '.$i ? 'selected' : '' }}>
                                        Darjah {{ $i }}
                                    </option>
                                @endfor
                            </optgroup>

                            <optgroup label="Tingkatan">
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="Tingkatan {{ $i }}" {{ old('grade', $student->grade) == 'Tingkatan '.$i ? 'selected' : '' }}>
                                        Tingkatan {{ $i }}
                                    </option>
                                @endfor
                            </optgroup>
                        </select>

                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gender --}}
                    <div class="mb-3">
                        <label for="gender" class="form-label">{{ __('messages.gender') }}</label>
                        <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">-- {{ __('messages.selectgender') }} --</option>
                            <option value="Male" {{ old('gender', $student->gender ?? '') == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                            <option value="Female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Race --}}
                    <div class="mb-3">
                        <label for="race" class="form-label">{{ __('messages.race') }}</label>
                        <select name="race" id="race" class="form-select @error('race') is-invalid @enderror" required>
                            <option value="">-- {{ __('messages.selectrace') }} --</option>
                            <option value="Malay" {{ old('race', $student->race ?? '') == 'Malay' ? 'selected' : '' }}>{{ __('messages.malay') }}</option>
                            <option value="Chinese" {{ old('race', $student->race ?? '') == 'Chinese' ? 'selected' : '' }}>{{ __('messages.chinese') }}</option>
                            <option value="Indian" {{ old('race', $student->race ?? '') == 'Indian' ? 'selected' : '' }}>{{ __('messages.indian') }}</option>
                            <option value="Others" {{ old('race', $student->race ?? '') == 'Others' ? 'selected' : '' }}>{{ __('messages.others') }}</option>
                        </select>
                        @error('race')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Religion --}}
                    <div class="mb-3">
                        <label for="religion" class="form-label">{{ __('messages.religion') }}</label>
                        <select name="religion" id="religion" class="form-select @error('religion') is-invalid @enderror" required>
                            <option value="">-- {{ __('messages.selectreligion') }} --</option>
                            <option value="Islam" {{ old('religion', $student->religion ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Buddhism" {{ old('religion', $student->religion ?? '') == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                            <option value="Hinduism" {{ old('religion', $student->religion ?? '') == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                            <option value="Christianity" {{ old('religion', $student->religion ?? '') == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                            <option value="Others" {{ old('religion', $student->religion ?? '') == 'Others' ? 'selected' : '' }}>{{ __('messages.others') }}</option>
                        </select>
                        @error('religion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nationality --}}
                    <div class="mb-3">
                        <label for="nationality" class="form-label">{{ __('messages.nationality') }}</label>
                        <select name="nationality" id="nationality" class="form-select @error('nationality') is-invalid @enderror" required>
                            <option value="">-- {{ __('messages.selectnationality') }} --</option>
                            <option value="Malaysian" {{ old('nationality', $student->nationality ?? '') == 'Malaysian' ? 'selected' : '' }}>Malaysian</option>
                            <option value="Non-Malaysian" {{ old('nationality', $student->nationality ?? '') == 'Non-Malaysian' ? 'selected' : '' }}>Non-Malaysian</option>
                        </select>
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Orphan --}}
                    <div class="mb-3">
                        <label for="orphan" class="form-label">{{ __('messages.orphan') }}</label>
                        <select name="orphan" id="orphan" class="form-select @error('orphan') is-invalid @enderror" required>
                            <option value="">-- {{ __('messages.select') }} --</option>
                            <option value="Yes" {{ old('orphan', $student->orphan ?? '') == 'Yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                            <option value="No" {{ old('orphan', $student->orphan ?? '') == 'No' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                        </select>
                        @error('orphan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- OKU --}}
                    <div class="mb-3">
                        <label for="oku" class="form-label">OKU</label>
                        <select name="oku" id="oku" class="form-select @error('oku') is-invalid @enderror" required>
                            <option value="">-- {{ __('messages.select') }} --</option>
                            <option value="Yes" {{ old('oku', $student->oku ?? '') == 'Yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                            <option value="No" {{ old('oku', $student->oku ?? '') == 'No' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                        </select>
                        @error('oku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>





                    {{-- Address --}}
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('messages.address') }}</label>
                        <textarea name="address" id="address" rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            required>{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Class --}}
                    <div class="mb-3">
                        <label for="class" class="form-label">{{ __('messages.class') }}</label>
                        <input type="text" name="class_name" id="class_name"
                            class="form-control @error('class') is-invalid @enderror"
                            value="{{ old('class_name', $student->class_name) }}" required>
                        @error('class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    {{-- Buttons Row --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        {{-- Update Form --}}
                        <form action="{{ route('school.students.update', $student->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success px-4">{{ __('messages.update') }}</button>
                        </form>

                        {{-- Delete Form --}}
                        <form action="{{ route('school.students.destroy', $student->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">Delete</button>
                        </form>
                    </div>




                </form>
            </div>
        </div>
    </div>
</x-app-layout>
