<!DOCTYPE html>
<html>
<head>
    <title>Class Attendance</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0; padding: 0;
        }
        header {
            background: #4A90E2;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .class-info {
            margin: 15px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .class-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #444;
        }
        table {
            width: 100%;
            margin: 15px;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        th {
            background: #f0f2f5;
            font-weight: bold;
            font-size: 14px;
            text-align: left;
            padding: 12px;
        }
        td {
            padding: 10px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }
        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        tr:hover {
            background: #f9fbff;
        }
    </style>
</head>
<body>
    <header>{{ $class['subject'] }} - {{ $class['class'] }}</header>

    <div class="class-info">
        <p><strong>Day:</strong> {{ $class['day'] }}</p>
        <p><strong>Time:</strong> {{ $class['start'] }} - {{ $class['end'] }}</p>
    </div>

    <table>
        <tr>
            <th>Student Name</th>
            <th>Attendance</th>
        </tr>
        @foreach($students as $student)
            <tr>
                <td>{{ $student['name'] }}</td>
                <td>
                    <select>
                        <option selected>Present</option>
                        <option>Absent</option>
                        <option>MC (Medical Certificate)</option>
                        <option>Unwell</option>
                        <option>Leave</option>
                        <option>School Activity</option>
                        <option>Family Matter</option>
                        <option>Others</option>
                    </select>
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
