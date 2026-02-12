<x-app-layout>
    <div class="container mt-4">
        <div class="bg-white p-4 rounded">
            <h3 class="mb-3">Edit Teacher</h3>

            <form action="{{ route('school.teachers.update', $teacher->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.fullname') }}</label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ old('name', $teacher->name) }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ old('email', $teacher->email) }}">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- IC --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.ic') }}</label>
                    <input type="text" name="ic" class="form-control" 
                           value="{{ old('ic', $teacher->ic) }}">
                    @error('ic')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.address') }}</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $teacher->address) }}</textarea>
                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.phonenum') }}</label>
                    <input type="text" name="phone_num" class="form-control" 
                           value="{{ old('phone_num', $teacher->phone_num) }}">
                    @error('phone_num')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Assign Classes --}}
                <div class="mb-3">
                    <label class="form-label">Assign Classes:</label>
                    <select name="classes[]" class="form-select select2" multiple>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ $teacher->classes->contains($class->id) ? 'selected' : '' }}>
                                {{ $class->class_name }} ({{ $class->grade->grade_name }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Start typing to search classes. Hold CTRL (or CMD) to select multiple.</small>
                    @error('classes')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $teacher->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $teacher->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Buttons --}}
                <button type="submit" class="btn btn-success">{{ __('messages.save') }}</button>
                <a href="{{ route('school.teachers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>

    {{-- Include Select2 --}}
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select classes",
                width: '100%'
            });
        });
    </script>
    @endpush

</x-app-layout>
