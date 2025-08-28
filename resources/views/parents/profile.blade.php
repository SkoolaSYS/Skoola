@role('parent')
<x-app-layout>
    @slot('title')
    Profile
    @endslot
    <x-card>
        <!--begin::Content menu-->
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Account
                    Overview</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar wrapper-->
        <!--end::Toolbar-->
        <!--begin::Navbar-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $parent->name }}</span>
                                </div>
                                <!--end::Name-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <i class="ki-duotone ki-profile-circle fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>Primary Guardian</a>
                                    <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                        <i class="ki-duotone ki-sms fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>{{ $parent->email }}</a>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
            </div>
        </div>
        @if ($parent->ic == null && $parent->address == null)
        <x-profile-notice />
        @endif
        <!--end::Navbar-->
        @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
        @endif
        <!--begin::details View-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Profile Details</h3>
                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                <a href="/profile/edit" class="btn btn-sm btn-primary align-self-center">Edit Profile</a>
                <!--end::Action-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body p-9">
    <div class="row">
        <!-- Left column -->
        <div class="col-md-6">
            <!-- Full Name -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $parent->name }}</span>
                </div>
            </div>

            <!-- Identification Card -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Identification Card</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $parent->ic ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Phone Number -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Phone Number</label>
                <div class="col-lg-8 d-flex align-items-center">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $parent->phone_num }}</span>
                </div>
            </div>

            <!-- Email -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Email</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $parent->email }}</span>
                    <span class="badge badge-success">Verified</span>
                </div>
            </div>

            <!-- Occupation -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Occupation</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $parent->occupation ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Relationship -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Relationship</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $parent->relationship ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Username -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Username</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $parent->username ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Right column -->
        <div class="col-md-6">
            <!-- Address -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Address</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $parent->address ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Postcode -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Postcode</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $postcode->name ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- City -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">City</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $citie->name ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- State -->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">State</label>
                <div class="col-lg-8">
                    <span class="fw-semibold fs-6 text-gray-800">{{ $state->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

        <!--begin::Statements-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">List of Additional Guardian</h3>
                </div>
                <!--end::Card title-->
                <!--begin::Action-->
                <a href="{{ route('profile.guardian.create') }}" class="btn btn-sm btn-primary align-self-center">Add
                    Additional Guardian</a>
                <!--end::Action-->
            </div>
            <!--end::Card header-->
            <!--begin::Tab Content-->
            <div id="kt_referred_users_tab_content" class="tab-content">
                <!--begin::Tab panel-->
                <div id="kt_referrals_1" class="card-body p-0 tab-pane fade show active" role="tabpanel">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                            <!--begin::Thead-->
                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                <tr>
                                    <th class="min-w-175px ps-9">No.</th>
                                    <th class="min-w-150px px-0">Name</th>
                                    <th class="min-w-150px px-0">Phone Number</th>
                                    <th class="min-w-150px px-0">Email</th>
                                    <th class="min-w-125px text-center" colspan="3">Action</th>
                                </tr>
                            </thead>
                            <!--end::Thead-->
                            <!--begin::Tbody-->
                            <tbody class="fs-6 fw-semibold text-gray-600">
    @forelse ($guardians as $index => $guardian)
        <tr>
            <td class="ps-9">{{ $index + 1 }}</td>
            <td class="ps-0">{{ $guardian->name }}</td>
            <td class="ps-0">{{ $guardian->phone_num ?? '-' }}</td>
            <td class="ps-0">{{ $guardian->email ?? '-' }}</td>
            <td class="text-center d-flex justify-content-center">
                <div class="d-flex gap-2">
                    <a href="{{ route('profile.guardian.show', $guardian->id) }}" class="btn btn-sm btn-light align-self-center">View</a>
                    <a href="{{ route('profile.guardian.edit', $guardian->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                    <form class="form" action="{{ route('profile.guardian.delete', $guardian->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this guardian?')">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td class="text-center" colspan="5">No additional guardians found.</td>
        </tr>
    @endforelse
</tbody>

                            <!--end::Tbody-->
                        </table>
                        <!--end::Table-->

                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Tab panel-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::details View-->
        <!--end::Body-->
    </x-card>
</x-app-layout>
@endrole