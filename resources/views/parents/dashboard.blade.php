@if(isset($parent))
<x-app-layout>
	@slot ('title')
	Dashboard
	@endslot
	<x-card title=''>
		@if ($parent->ic == NULL && $parent->address == NULL)
		<x-profile-notice />
		@endif

		<div class="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center gap-2 me-3">
				<!--begin::Title-->
				<div class="mb-3 text-center">
</div>


				<h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Dashboard</h1>
				<!--end::Title-->
			</div>
			<!--end::Page title-->
		</div>
		<!--begin::Row-->
		<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
			<!--begin::Chart widget 22-->
			<div class="card h-xl-100">
				<div class="card-body pb-3">
					<!--begin::Tab Content-->
					<div class="tab-content">
						<!--begin::Tap pane-->
						<div class="tab-pane fade show active">
							<!--begin::Wrapper-->
							<div class="d-flex flex-wrap flex-md-nowrap">
								@php $chartCounter = 0; @endphp
								@forelse($students as $id => $name)
								<!--begin::Container-->
								<div class="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pt-3 pb-10">
									<!--begin::Title-->
									<div class="fs-4 fw-bold text-gray-900 text-center mb-5">{{ __('messages.parent_attendance', ['name' => $name]) }}
<br /></div>
									<!--end::Title-->
									<div class="mx-auto mb-4" id="pie_chart_{{$id}}"></div>
									<div class="mx-auto">
										<!--begin::Label-->
										<div class="card" style="width: 10rem;">
											<div class="card-body">
												<div class="d-flex align-items-center mb-2">
													<!--begin::Bullet-->
													<div class="bullet bullet-dot w-8px h-7px bg-success me-2" style="color:#50cd89;"></div>
													<!--end::Bullet-->
													<!--begin::Label-->
													<div class="fs-8 fw-semibold text-muted">{{ __('messages.present') }}</div>
													<!--end::Label-->
												</div>
												<!--end::Label-->
												<!--begin::Label-->
												<div class="d-flex align-items-center mb-2">
													<!--begin::Bullet-->
													<div class="bullet bullet-dot w-8px h-7px bg-danger me-2" style="color:#f1416c;"></div>
													<!--end::Bullet-->
													<!--begin::Label-->
													<div class="fs-8 fw-semibold text-muted">{{ __('messages.absent') }}</div>
													<!--end::Label-->
												</div>
											</div>
										</div>
										<!--end::Label-->
									</div>
									<!--end::Labels-->
								</div>
								<!--end::Container-->

								@php $chartCounter++; @endphp
								@if ($chartCounter === 3)
							</div> <!-- Close current row -->
							<div class="d-flex flex-wrap flex-md-nowrap"> 
								@php $chartCounter = 0; @endphp
								@endif

								@empty
								<span class="text-center text-muted">{{ __('messages.nostudents') }}
									<a href="{{ route('student.show') }}"> {{ __('messages.addstudents') }}</a>
								</span>
								@endforelse
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Tap pane-->
					</div>
				</div>
				<!--end::Chart widget 22-->
			</div>
			<!--end::Row-->
			<!--begin::Row-->
			<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
				<!--begin::Chart widget 22-->
				<div class="card h-xl-100">
					<!--begin::Header-->
					<div class="card-header position-relative py-0 border-bottom-2">
						<!--begin::Nav-->
						<ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
						</ul>
						<!--end::Nav-->
					</div>
					<!--begin::Statements-->
					<div class="card">
						<!--begin::Card header-->
						<div class="card-header cursor-pointer">
							<!--begin::Card title-->
							<div class="card-title m-0">
								<h3 class="fw-bold m-0">{{ __('messages.listattendance') }}</h3>
							</div>
							<!--end::Card title-->
							<!--begin::Action-->
							<a href="{{route('attendance.show')}}" class="btn btn-sm btn-primary align-self-center">{{ __('messages.viewmore') }}</a>
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
												<th class="min-w-125px ps-9">{{ __('messages.date') }}</th>
												<th class="min-w-125px ps-9">{{ __('messages.checkin') }}</th>
												<th class="min-w-125px ps-9">{{ __('messages.checkout') }}</th>
												<th class="min-w-150px px-0">{{ __('messages.name') }}</th>
												<th class="min-w-150px px-0">{{ __('messages.school') }}</th>
												<th class="min-w-150px ps-5">{{ __('messages.status') }}</th>
												<th class="min-w-150px ps-5">{{ __('messages.remarks') }}</th>
											</tr>
										</thead>
										<!--end::Thead-->
										<!--begin::Tbody-->
										<tbody class="fs-6 fw-semibold text-gray-600">
											@forelse($attendanceList as $attendance)
											<tr>
												<td class="ps-9">{{$attendance->date}}</td>
												<td class="ps-9">{{$attendance->check_in}}</td>
												<td class="ps-9">{{$attendance->check_out}}</td>
												<td class="ps-0">{{ $attendance->student->name ?? '-' }}</td>
												<td class="ps-0">{{ $attendance->student->school->name ?? '-' }}</td>



												@if ($attendance->status == 'attend')
												<td class="text-center">
													<a class="badge status-badge" style="background-color:#50cd89;">{{ __('messages.present') }}</a>
												</td>
												@else
												<td class="text-center">
													<a class="badge status-badge" style="background-color:#f1416c;">{{ __('messages.absent') }}</a>
												</td>
												@endif
												@if ($attendance->remarks == NULL)
												<td class="ps-9">-</td>
												@else
												<td class="ps-9">{{$attendance->remarks}}</td>
												@endif
												<td class="ps-9">
											</tr>
											@empty
											<tr>
												<td class="text-center" colspan="8">{{ __('messages.noattendance') }}</td>
											</tr>
											@endforelse
										</tbody>
										<!--end::Tbody-->
									</table>
									<!--end::Table-->
								</div>
							</div>
							<!--end::Tab panel-->
						</div>
						<!--end::Card body-->
					</div>
					<!--end::details View-->
				</div>
			</div>
		</div>
		<!--end::Row-->
		@push('scripts')
		<script>
			var attendanceData = @json($attendanceData);
			// The above line converts the PHP array $attendanceData to a JavaScript object
			// and stores it in the attendanceData variable, which you can use in your JavaScript code.

			var options = {};
			var charts = {};

			// Loop through the attendanceData object and create the chart options for each student
			for (var key in attendanceData) {
				var data = JSON.parse(attendanceData[key]); // Parse the JSON string to an array [attend, absent]
				console.log(data);
				var options1 = {
					series: data,
					chart: {
						type: 'donut',
						width: '230', // Adjust the width of the chart
						height: '200', // Adjust the height of the chart
					},
					colors: ['#50cd89', '#f1416c'], //green, red
					legend: {
						show: false, // Hide the legend
					},
					plotOptions: {
						pie: {
							size: '50%', // Set the size of the pie chart (radius)
							//customScale: 1.0,
						},
					},
					responsive: [{
						breakpoint: 480,
						options: {
							chart: {
								width: 150, // Adjust the width of the chart for smaller screens
								height: 150, // Adjust the height of the chart for smaller screens

							},
						}
					}]
				};

				options[key] = options1;
			}

			// Render the charts for each student
			document.addEventListener("DOMContentLoaded", function() {
				for (var key in options) {
					charts[key] = new ApexCharts(document.querySelector("#pie_chart_" + key), options[key]);
					charts[key].render();
				}
			});
		</script>
		@endpush
	</x-card>
</x-app-layout>
@endif