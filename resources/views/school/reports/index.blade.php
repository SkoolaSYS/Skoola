<x-app-layout>
    @slot('title')
        Reports
    @endslot

    <x-card title="School Reports">

        <!-- Filter Form -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="year" class="form-label">{{ __('messages.yearly') }}</label>
                <select name="year" id="year" class="form-select">
                    <option value="">{{ __('messages.select') }}</option>
                    @foreach(range(date('Y'), 2020) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="month" class="form-label">{{ __('messages.month') }}</label>
                <select name="month" id="month" class="form-select">
                    <option value="">{{ __('messages.select') }}</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="date" class="form-label">{{ __('messages.date') }}</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                <a href="{{ route('school.reports.index') }}" class="btn btn-secondary ms-2">{{ __('messages.reset') }}</a>
            </div>
        </form>

        <!-- Records Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ __('messages.fullname') }}</th>
                    <th>{{ __('messages.cardid') }}</th>
                    <th>{{ __('messages.time') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $record->student_name ?? 'Unknown Student' }}</td>
                        <td>{{ $record->card_id }}</td>
                        <td>{{ $record->time }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('messages.norecords') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
</x-app-layout>
