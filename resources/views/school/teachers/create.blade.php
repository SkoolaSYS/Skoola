<x-app-layout>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Register New Teacher</h4>

                <form method="POST" action="{{ route('school.teachers.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="teacher_id">Teacher ID</label>
                        <input type="text" name="teacher_id" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="ic">Identification Card Number</label>
                        <input type="text" name="ic" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone_num">Phone Number</label>
                        <input type="text" name="phone_num" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <input type="hidden" name="role" value="teacher">
                    <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Register Teacher</button>
                        <a href="{{ route('school.teachers.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
