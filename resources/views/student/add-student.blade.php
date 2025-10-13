@role('parent')
<x-app-layout>
    @slot('title')
    Add Student
    @endslot 
    <x-card>
        <!--begin::Content menu-->
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Student List</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar wrapper-->
        <!--end::Toolbar-->
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Student Profile Details</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_account_profile_details_form" class="form" method="post" action="{{ route('student.store') }}" enctype="multipart/form-data">
    @csrf
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <!-- container INSIDE the form: new blocks will be appended here -->
    <div id="student-forms-container">
        @include('student._student-form', ['prefix' => 'students[0]', 'key' => 'student-0'])
    </div>

    <!-- short left-aligned Add button -->
    <button type="button" class="btn btn-secondary btn-sm mt-1" id="add-student-btn">
        Add More Student
    </button>

    <!-- Actions -->
    <div class="card-footer d-flex justify-content-end py-6 px-9">
        <a href="{{ route('student.show')}}" class="btn btn-light btn-active-light-primary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
    </div>
</form>

                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->
        </div>
        <!--end::Body-->

        <script>
document.addEventListener('DOMContentLoaded', function() {
    // Count existing .student-form-wrapper blocks to start index correctly
    let studentIndex = document.querySelectorAll('.student-form-wrapper').length || 1;
    const addBtn = document.getElementById('add-student-btn');
    const container = document.getElementById('student-forms-container');

    if (!container) {
        console.error('student-forms-container not found inside the form — make sure container is inside the <form>.');
        return;
    }

    addBtn.addEventListener('click', function() {
        fetch('/student-form-partial?index=' + studentIndex)
            .then(response => response.text())
            .then(html => {
                const wrapper = document.createElement('div');
                wrapper.className = 'student-form-wrapper';
                wrapper.innerHTML = html;

                // create a small remove button (not full width)
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-danger mt-2 remove-student-btn';
                removeBtn.textContent = 'Remove';
                wrapper.appendChild(removeBtn);

                container.appendChild(wrapper);
                studentIndex++;

                // If you're using Livewire, re-scan (you already had this)
                if (window.Livewire) window.Livewire.rescan();
            })
            .catch(err => console.error('Error fetching partial:', err));
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-student-btn')) {
            e.target.closest('.student-form-wrapper').remove();
        }
    });
});
</script>

    </x-card>
</x-app-layout>
@endrole


