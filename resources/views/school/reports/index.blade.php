<x-app-layout>
    @slot('title')
        Reports
    @endslot

    <x-card title="School Reports">
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
