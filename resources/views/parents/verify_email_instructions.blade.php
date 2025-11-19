
<x-app-layout>
    @slot('title')
        {{ __('Verify') }}
    @endslot
<h3>Email Not Verified</h3>
<p>An email has been sent to <strong>{{ $email }}</strong>. Please check your inbox and click the verification link to access the dashboard.</p>
</x-app-layout>
