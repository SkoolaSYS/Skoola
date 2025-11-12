<x-app-layout>
    <x-card title="Email Verification">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <p>Please verify your email to access attendance data.</p>
        <form method="POST" action="{{ route('attendance.send') }}">
            @csrf
            <input type="email" name="email" class="form-control mb-3" placeholder="Enter your email" required>
            <button type="submit" class="btn btn-primary">Send Verification Link</button>
        </form>

        @isset($expired)
            <div class="text-danger mt-3">Your link has expired. Please verify again.</div>
        @endisset
    </x-card>
</x-app-layout>
