


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
		<!-- PositiveSSL TrustLogo -->
<div style="position:absolute; top:10px; left:10px; z-index:9999;">
  <script type="text/javascript">
    var tlJsHost = ((window.location.protocol == "https:") ? 
      "https://secure.trust-provider.com/" : "http://www.trustlogo.com/");
    document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
  <script type="text/javascript">
    TrustLogo("https://www.positivessl.com/images/seals/positivessl_trust_seal_lg_222x54.png", "POSDV", "none");
  </script>
</div>
<!-- End PositiveSSL TrustLogo -->

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
                                        
										<!-- Main Parent / Guardian (Required) -->
									<div id="parent-form">
										<h3>{{ __('messages.parentdetails') }}</h3>
										<div class="card-body border-top p-9">

											<!-- Full Name -->
											<div class="row mb-6">
												<label class="col-lg-4 col-form-label required">{{ __('messages.fullname') }}</label>
												<div class="col-lg-8 fv-row">
													<input type="text" name="name" class="form-control" placeholder="{{ __('messages.fullname') }}" required>
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
												<label class="col-lg-4 col-form-label required">{{ __('messages.phonenum') }}</label>
												<div class="col-lg-8 fv-row">
													<input type="text" name="phone_num" class="form-control" placeholder="{{ __('messages.phonenum') }}" required>
												</div>
											</div>

											<!-- IC -->
											<div class="row mb-6">
												<label class="col-lg-4 col-form-label required">{{ __('messages.ic') }}</label>
												<div class="col-lg-8 fv-row">
													<input type="text" id="parent_ic" name="ic" class="form-control" placeholder="{{ __('messages.ic') }}" required>
												</div>
											</div>

											<!-- Address -->
											<div class="row mb-6">
												<label class="col-lg-4 col-form-label required">{{ __('messages.address') }}</label>
												<div class="col-lg-8 fv-row">
													<input type="text" name="address" class="form-control" placeholder="{{ __('messages.address') }}" required>
												</div>
											</div>

											<!-- State, City, Postcode -->
											<div class="row mb-6">
													@livewire('malaysia-state')
											</div>



											<!-- Occupation -->
											<div class="row mb-6">
												<label class="col-lg-4 col-form-label required">{{ __('messages.occupation') }}</label>
												<div class="col-lg-8 fv-row">
													<input type="text" name="occupation" class="form-control" placeholder="{{ __('messages.occupation') }}" required>
												</div>
											</div>

											<!-- Relationship -->
									<div class="row mb-6">
										<label class="col-lg-4 col-form-label required">{{ __('messages.relay') }}</label>
										<div class="col-lg-8 fv-row">
											<select name="relationship" class="form-control" required>
												<option value="">{{ __('messages.selectrelay') }}</option>
												<option value="Father">{{ __('messages.father') }}</option>
												<option value="Mother">{{ __('messages.mother') }}</option>
												<option value="Guardian">{{ __('messages.guardian') }}</option>
											</select>
										</div>
									</div>


                                    <!-- Password -->
									<div class="row mb-6">
										<label class="col-lg-4 col-form-label required">{{ __('messages.password') }}</label>
										<div class="col-lg-8 fv-row" data-kt-password-meter="true">
											<!--begin::Wrapper-->
											<div class="mb-1">
												<!--begin::Input wrapper-->
												<div class="position-relative mb-3">
													<input type="password" placeholder="{{ __('messages.password') }}" name="password" class="form-control bg-transparent" required autocomplete="new-password" />
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
											<div class="text-muted">{{ __('messages.hintpass') }}</div>
											<!--end::Hint-->
										</div>
									</div>

									<!-- Repeat Password -->
									<div class="row mb-6">
										<label class="col-lg-4 col-form-label required">{{ __('messages.repeatpass') }}</label>
										<div class="col-lg-8 fv-row">
											<input type="password" placeholder="{{ __('messages.repeatpass') }}" name="password_confirmation" class="form-control bg-transparent" required autocomplete="new-password" />
										</div>
									</div>

									



										<!-- Additional Guardians (Optional) -->
										<div id="additional-guardians-container"></div>
										<button type="button" class="btn btn-secondary w-100 mb-4" id="add-guardian-btn">
											{{ __('messages.addguardian') }}
										</button>


                                    {{-- 
									<div class="mb-5">
										<h3 class="fw-bold mb-3">{{ __('messages.studentprofiledetails') }}</h3>

										<div id="student-forms-container">
											@include('student._student-form', ['prefix' => 'students[0]', 'key' => 'student-0'])
										</div>

										<hr>

										<button type="button" 
												class="btn btn-secondary mt-1 w-100" 
												id="add-student-btn">
											{{ __('messages.addmore') }}
										</button>

										<hr> 
									</div> 
									--}}

									<div id="student-forms-container"></div>

									<!--begin::Submit button-->
									<div class="d-grid mb-10">
										<button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
											<!--begin::Indicator label-->
											<span class="indicator-label">{{ __('messages.register') }}</span>
											<!--end::Indicator label-->
											<!--begin::Indicator progress-->
											<span class="indicator-progress">Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											<!--end::Indicator progress-->
										</button>
									</div>
									<!--end::Submit button-->
									<!--begin::Sign up-->
									<div class="text-gray-500 text-center fw-semibold fs-6">{{ __('messages.alreadyhave') }}
									<a href="/login" class="link-primary fw-semibold">{{ __('messages.login') }}</a></div>
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

    /* ==============================
       GUARDIAN ADD / REMOVE
    ============================== */

    let guardianIndex = 1;
    const addGuardianBtn = document.getElementById('add-guardian-btn');
    const guardianContainer = document.getElementById('additional-guardians-container');

    if (addGuardianBtn && guardianContainer) {
        addGuardianBtn.addEventListener('click', function() {
            fetch('/guardian-form-partial?index=' + guardianIndex)
                .then(response => response.text())
                .then(html => {
                    const div = document.createElement('div');
                    div.classList.add('guardian-form-wrapper', 'mb-4');
                    div.innerHTML = html + 
                        '<button type="button" class="btn btn-danger remove-guardian-btn mt-2 mb-2 w-100">Remove</button>';
                    guardianContainer.appendChild(div);
                    guardianIndex++;

                    if (window.Livewire) {
                        window.Livewire.rescan();
                    }
                });
        });

        guardianContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-guardian-btn')) {
                e.target.closest('.guardian-form-wrapper').remove();
            }
        });
    }

    /* ==============================
       PASSWORD METER INIT
    ============================== */

    document.querySelectorAll('[data-kt-password-meter="true"]').forEach(function (element) {
        if (!element.hasAttribute("data-kt-password-meter-initialized")) {
            new KTPasswordMeter(element);
            element.setAttribute("data-kt-password-meter-initialized", "true");
        }
    });

    /* ==============================
       AUTO FETCH STUDENTS BY IC
    ============================== */

    const icInput = document.getElementById('parent_ic');
    const studentContainer = document.getElementById('student-forms-container');

    if (icInput && studentContainer) {

        let timeout = null;

        icInput.addEventListener('input', function() {

            clearTimeout(timeout);

            timeout = setTimeout(() => {

                let icValue = icInput.value;

                // If Malaysian IC must be 12 digits
                if (icValue.length !== 12) {
                    studentContainer.innerHTML = '';
                    return;
                }

                fetch('/get-students-by-parent-ic?ic=' + icValue)
                    .then(response => response.json())
                    .then(data => {

                        studentContainer.innerHTML = '';

                        if (data.length === 0) {
                            studentContainer.innerHTML = `
                                <div class="alert alert-warning">
                                    No children found for this IC.
                                </div>
                            `;
                            return;
                        }

                        data.forEach((student, index) => {

                            let html = `
                                <div class="card p-4 mb-3">
                                    <h5>Child ${index + 1}</h5>
                                    <p><strong>Name:</strong> ${student.name}</p>
                                    <p><strong>IC:</strong> ${student.ic}</p>
                                    <p><strong>Grade:</strong> ${student.grade}</p>
                                    <p><strong>Class:</strong> ${student.class_name}</p>
                                </div>
                            `;

                            studentContainer.innerHTML += html;
                        });
                    });

            }, 500);

        });
    }

});
</script>


		@livewireScripts
	</body>
	<!--end::Body-->
</html>