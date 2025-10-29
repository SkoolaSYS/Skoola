<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Class Attendance - {{ $class_name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        h3, p { text-align: center; margin: 0; }
    </style>
</head>
<body>
    <h3>Class Attendance Report</h3>
    <p>
        <strong>Grade:</strong> {{ $grade }} |
        <strong>Class:</strong> {{ $class_name }} |
        <strong>Date:</strong> {{ $date }}
    </p>
    <p>
        <strong>Teacher:</strong> {{ $teacher }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Student Name</th>
                @foreach($subjects as $subject)
                    <th>{{ $subject }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->name }}</td>
                    @foreach($subjects as $subject)
                        <td>{{ $attendanceRecords[$student->id][$subject] ?? '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
