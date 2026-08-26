
<x-app-layout>
    @slot('title')
        {{ __('Verification Email Sent') }}
    @endslot

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h4>{{ __('Verification Email Sent') }}</h4>
                    </div>
                    <div class="card-body text-center">
                        <p>{{ __('Your email is not verified. We have sent a verification link to your email:') }} <strong>{{ $email }}</strong></p>
                        <p>{{ __('Please check your inbox and click the link to verify your email. Once verified, you will be able to access your parent dashboard and view your child\'s attendance.') }}</p>
                        <a href="{{ url('/') }}" class="btn btn-secondary mt-3">{{ __('Back to Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
