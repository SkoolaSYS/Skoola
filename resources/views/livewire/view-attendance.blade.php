<!--begin::Card body-->
<div class="card-body pt-0">
    <div class="table-responsive">
        <!--begin::Table-->
        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
            <!--begin::Thead-->
            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                <tr>
                    <th class="min-w-125px ps-9">Date</th>
                    <th class="min-w-125px ps-9">Check-In</th>
                    <th class="min-w-125px ps-9">Check-Out</th>
                    <th class="min-w-150px px-0">Name</th>
                    <th class="min-w-150px px-0">School</th>
                    <th class="min-w-150px ps-5">Status</th>
                    <th class="min-w-150px ps-5">Remarks</th>
                    <th></th>

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
                    <td class="ps-0">{{$attendance->student->name}}</td>
                    <td class="ps-0">{{$attendance->student->school->name}}</td>

                    @if ($attendance->status == 'attend')
                    <td class="text-center">
                        <a class="badge status-badge" style="background-color:#50cd89;">Present</a>
                    </td>
                    @else
                    <td class="text-center">
                        <a class="badge status-badge" style="background-color:#f1416c;">Absent</a>
                    </td>
                    @endif
                    @if ($attendance->remarks == NULL)
                    <td class="ps-9">-</td>
                    @else
                    <td class="ps-9">{{$attendance->remarks}}</td>
                    @endif
                    <td class="ps-9">
                        <button class="btn btn-sm btn-primary align-self-center" type="button" onclick='_openModal("Edit Remarks", "edit-remarks", {{ json_encode([$attendance->id]) }}, "lg")'>
                            Edit
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="8">No attendances found.</td>
                </tr>
                @endforelse
            </tbody>
            <!--end::Tbody-->
        </table>
        <!--end::Table-->
    </div>
</div>
<!--end::Card body-->