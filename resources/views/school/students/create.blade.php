<x-app-layout>
    @slot('title') Add Student @endslot

    <x-card title="Add Student">
        <form method="POST" action="{{ route('school.student.store', ['school' => $school->id]) }}">
            @csrf

            <input type="hidden" name="school_id" value="{{ $school->id }}">

            <div class="mb-3">
                <label class="form-label">{{ __('messages.fullname') }}</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.ic') }}</label>
                <input type="text" class="form-control" name="ic" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.birthcertno') }}</label>
                <input type="text" class="form-control" name="birth_cert_no">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.address') }}</label>
                <input type="textarea" class="form-control" name="address" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.dob') }}</label>
                <input type="date" class="form-control" name="dob" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.gender') }}</label>
                <select name="gender" class="form-select" required>
                    <option value="">{{ __('messages.selectgender') }}</option>
                    <option value="Male">{{ __('messages.male') }}</option>
                    <option value="Female">{{ __('messages.female') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.grade') }}</label>
                <select name="grade" class="form-select" required>
                    <option value="">{{ __('messages.selectgrade') }}</option>
                    <option value="Darjah 1">Darjah 1</option>
                    <option value="Darjah 2">Darjah 2</option>
                    <option value="Darjah 3">Darjah 3</option>
                    <option value="Darjah 4">Darjah 4</option>
                    <option value="Darjah 5">Darjah 5</option>
                    <option value="Darjah 6">Darjah 6</option>
                    <option value="Tingkatan 1">Tingkatan 1</option>
                    <option value="Tingkatan 2">Tingkatan 2</option>
                    <option value="Tingkatan 3">Tingkatan 3</option>
                    <option value="Tingkatan 4">Tingkatan 4</option>
                    <option value="Tingkatan 5">Tingkatan 5</option>
                    <option value="Tingkatan 6">Tingkatan 6</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.class') }}</label>
                <input type="text" class="form-control" name="class_name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.race') }}</label>
                <select name="race" class="form-select" required>
                    <option value="">{{ __('messages.selectrace') }}</option>
                    <option value="Malay">{{ __('messages.malay') }}</option>
                    <option value="Chinese">{{ __('messages.chinese') }}</option>
                    <option value="Indian">{{ __('messages.indian') }}</option>
                    <option value="Others">{{ __('messages.others') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.religion') }}</label>
                <select name="religion" class="form-select" required>
                    <option value="">{{ __('messages.selectreligion') }}</option>
                    <option value="Islam">Islam</option>
                    <option value="Christianity">Christianity</option>
                    <option value="Buddhism">Buddhism</option>
                    <option value="Hinduism">Hinduism</option>
                    <option value="Others">{{ __('messages.others') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.nationality') }}</label>
                <select name="nationality" class="form-select" required>
                    <option value="">{{ __('messages.selectnationality') }}</option>
                    <option value="Malaysian">Malaysian</option>
                    <option value="Non-Malaysian">Non-Malaysian</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.orphan') }}</label>
                <select name="orphan" class="form-select" required>
                    <option value="">{{ __('messages.select') }}</option>
                    <option value="Yes">{{ __('messages.yes') }}</option>
                    <option value="No">{{ __('messages.no') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">OKU</label>
                <select name="oku" class="form-select" required>
                    <option value="">{{ __('messages.select') }}</option>
                    <option value="Yes">{{ __('messages.yes') }}</option>
                    <option value="No">{{ __('messages.no') }}</option>
                </select>
            </div>


            <button type="submit" class="btn btn-primary">{{ __('messages.addstudent') }}</button>
            <a href="{{ route('dashboard.school', ['school' => $school->id]) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
</x-app-layout>
