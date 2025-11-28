<x-app-layout>
    @slot('title')
        Email Not Found
    @endslot

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-lg border-0">
                    <div class="card-body text-center py-5">

                        <h2 class="text-danger fw-bold mb-3">Email Not Found</h2>

                        <p class="mb-4">
                            The email <strong>{{ $email }}</strong> is not registered in our system.
                        </p>

                        <a href="{{ url()->previous() }}" class="btn btn-secondary px-4">
                            Back
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
