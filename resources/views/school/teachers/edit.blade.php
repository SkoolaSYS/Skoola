<x-app-layout>
    <div class="container mt-4">
        <div class="bg-white p-4 rounded">
            <h3 class="mb-3">Edit Teacher</h3>

            <form action="{{ route('school.teachers.update', $teacher->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.fullname') }}</label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ old('name', $teacher->name) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ old('email', $teacher->email) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.ic') }}</label>
                    <input type="text" name="ic" class="form-control" 
                           value="{{ old('ic', $teacher->ic) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.address') }}</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $teacher->address) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.phonenum') }}</label>
                    <input type="text" name="phone_num" class="form-control" 
                           value="{{ old('phone_num', $teacher->phone_num) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $teacher->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $teacher->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">{{ __('messages.save') }}</button>
                <a href="{{ route('school.teachers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</x-app-layout>
