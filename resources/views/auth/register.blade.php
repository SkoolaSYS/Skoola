


<!DOCTYPE html>
<html lang="en">
	
	<!--begin::Head-->
	<head><base href="../../../"/>
		<title>Register</title>
		<meta charset="utf-8" />
		<meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<meta property="og:site_name" content="Keenthemes | Metronic" />
		<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		@livewireStyles

		@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Page bg image-->
			<style>body { background-image: url('assets/media/auth/bg10.jpeg'); } [data-bs-theme="dark"] body { background-image: url('assets/media/auth/bg10-dark.jpeg'); }</style>
			<!--end::Page bg image-->
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Aside-->
				<div class="d-flex flex-lg-row-fluid">
					<!--begin::Content-->
					<div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
						<!--begin::Image-->
						<img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="assets/media/auth/agency.png" alt="" />
						<img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="assets/media/auth/agency-dark.png" alt="" />
						<!--end::Image-->
						<!--begin::Title-->
						<h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">Fast, Efficient and Productive</h1>
						<!--end::Title-->
						
					</div>
					<!--end::Content-->
				</div>

				<!--begin::Body-->
				<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
					<!--begin::Wrapper-->
					<div class="bg-body d-flex flex-column flex-center rounded-4 w-md-700px p-10">
						<!--begin::Content-->
						<div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-600px">
							<!--begin::Wrapper-->
							<div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
								<!--begin::Form-->
                                    <form class="form w-100" data-kt-redirect-url="{{ route('register') }}" action="{{ route('register') }}" method="POST">
                                        @csrf
                                        <!--begin::Heading-->
                                        <div class="text-center mb-11">
                                            <!--begin::Title-->
                                            <h1 class="text-dark fw-bolder mb-3">Register</h1>
                                            <!--end::Title-->
                                        </div>
                                        <form action="{{ route('register') }}" method="POST">
    @csrf
	<!-- Main Parent / Guardian (Required) -->
<div id="parent-form">
    <h3>Parent / Guardian Details</h3>
    <div class="card-body border-top p-9">

        <!-- Full Name -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required">Full Name</label>
            <div class="col-lg-8 fv-row">
                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>
        </div>

        <!-- Username -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required">Username</label>
            <div class="col-lg-8 fv-row">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
        </div>

        <!-- Email -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required">Email</label>
            <div class="col-lg-8 fv-row">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
        </div>

        <!-- Phone Number -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required">Phone Number</label>
            <div class="col-lg-8 fv-row">
                <input type="text" name="phone_num" class="form-control" placeholder="Phone Number" required>
            </div>
        </div>

        <!-- IC -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required">IC / Passport Number</label>
            <div class="col-lg-8 fv-row">
                <input type="text" name="ic" class="form-control" placeholder="IC / Passport Number" required>
            </div>
        </div>

        <!-- Address -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required">Address</label>
            <div class="col-lg-8 fv-row">
                <input type="text" name="address" class="form-control" placeholder="Address" required>
            </div>
        </div>

        <!-- Occupation -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required">Occupation</label>
            <div class="col-lg-8 fv-row">
                <input type="text" name="occupation" class="form-control" placeholder="Occupation" required>
            </div>
        </div>

        <!-- Relationship -->
<div class="row mb-6">
    <label class="col-lg-4 col-form-label required">Relationship</label>
    <div class="col-lg-8 fv-row">
        <select name="relationship" class="form-control" required>
            <option value="">Select Relationship</option>
            <option value="Father">Father</option>
            <option value="Mother">Mother</option>
            <option value="Guardian">Guardian</option>
        </select>
    </div>
</div>


        <div class="fv-row mb-8" data-kt-password-meter="true">
										<!--begin::Wrapper-->
										<div class="mb-1">
											<!--begin::Input wrapper-->
											<div class="position-relative mb-3">
											<input type="password" placeholder="Password" name="password" class="form-control bg-transparent" required autofocus autocomplete="new-password" />
												<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
													<i class="ki-duotone ki-eye-slash fs-2"></i>
													<i class="ki-duotone ki-eye fs-2 d-none"></i>
												</span>
											</div>
											<!--end::Input wrapper-->
											<!--begin::Meter-->
											<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
												<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
												<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
												<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
												<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
											</div>
											<!--end::Meter-->
										</div>
										<!--end::Wrapper-->
										<!--begin::Hint-->
										<div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
										<!--end::Hint-->
									</div>
									<!--end::Input group=-->
									<!--end::Input group=-->
									<div class="fv-row mb-8">
										<!--begin::Repeat Password-->
										<input type="password" placeholder="Repeat Password" name="password_confirmation" class="form-control bg-transparent" required autofocus autocomplete="new-password" />
										<!--end::Repeat Password-->
									</div>
									<!--end::Input group=-->
										</div>
									</div>


										<!-- Additional Guardians (Optional) -->
										<div id="additional-guardians-container"></div>
										<button type="button" class="btn btn-secondary w-100 mb-4" id="add-guardian-btn">
											Add Additional Guardian
										</button>


                                    <!-- Student Profile Section -->
                                    <div class="mb-5">
                                        <h3 class="fw-bold mb-3">Student Profile Details</h3>
                                        <div id="student-forms-container">
                                            @include('student._student-form', ['prefix' => 'students[0]', 'key' => 'student-0'])
                                        </div>
										<hr>
                                        <button type="button" class="btn btn-secondary mt-1 w-100" id="add-student-btn">Add More</button>
										<hr>
                                    </div>
									<!--begin::Submit button-->
									<div class="d-grid mb-10">
										<button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
											<!--begin::Indicator label-->
											<span class="indicator-label">Register</span>
											<!--end::Indicator label-->
											<!--begin::Indicator progress-->
											<span class="indicator-progress">Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											<!--end::Indicator progress-->
										</button>
									</div>
									<!--end::Submit button-->
									<!--begin::Sign up-->
									<div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
									<a href="/login" class="link-primary fw-semibold">Log in</a></div>
									<!--end::Sign up-->
								</form>
								<!--end::Form-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Content-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/custom/authentication/sign-in/general.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
		<script>
document.addEventListener('DOMContentLoaded', function() {
    let guardianIndex = 1; // start after the first one
    const addBtn = document.getElementById('add-guardian-btn');
    const container = document.getElementById('additional-guardians-container'); // corrected ID

    addBtn.addEventListener('click', function() {
        fetch('/guardian-form-partial?index=' + guardianIndex)
            .then(response => response.text())
            .then(html => {
                const div = document.createElement('div');
                div.classList.add('guardian-form-wrapper', 'mb-4');
                div.innerHTML = html + '<button type="button" class="btn btn-danger remove-guardian-btn mt-2 mb-2 w-100">Remove</button>';
                container.appendChild(div);
                guardianIndex++;

                if (window.Livewire) {
                    window.Livewire.rescan();
                }
            });
    });

	document.querySelectorAll('[data-kt-password-meter="true"]').forEach(function (element) {
    // Avoid duplicate init
    if (!element.hasAttribute("data-kt-password-meter-initialized")) {
        new KTPasswordMeter(element);
        element.setAttribute("data-kt-password-meter-initialized", "true");
    }
});


    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-guardian-btn')) {
            e.target.closest('.guardian-form-wrapper').remove();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
				let studentIndex = 1;
				const addBtn = document.getElementById('add-student-btn');
				const container = document.getElementById('student-forms-container');
				addBtn.addEventListener('click', function() {
					fetch('/student-form-partial?index=' + studentIndex)
						.then(response => response.text())
						.then(html => {
							const div = document.createElement('div');
							div.classList.add('student-form-wrapper');
							div.innerHTML = html + '<button type="button" class="btn btn-danger remove-student-btn mt-2 mb-2 w-100">Remove</button>';
							container.appendChild(div);
							studentIndex++;
							if (window.Livewire) {
								window.Livewire.rescan();
							}
						});
				});
				container.addEventListener('click', function(e) {
					if (e.target.classList.contains('remove-student-btn')) {
						e.target.closest('.student-form-wrapper').remove();
					}
				});
			});





</script>

		@livewireScripts
	</body>
	<!--end::Body-->
</html>