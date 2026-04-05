<!DOCTYPE html>
<html>
<head>
    <title>Feedback Penumpang Report</title>
    <style>
        /* Tambahkan font DejaVu Sans */
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url({{ storage_path('fonts/dejavu-sans/DejaVuSans.ttf') }}) format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif; /* Gunakan font DejaVu Sans */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Feedback Penumpang</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Pesan</th>
                <th>Tanggal</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_feedback as $raw)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $raw->user_input }}</td>
                <td>{{ $raw->tipe }}</td>
                <td>{{ $raw->pesan }}</td>
                <td>{{ date('d-m-Y H:i:s', strtotime($raw->created_at)) }}</td>
                <td>
                    {{ str_repeat('★', $raw->rating) . str_repeat('☆', 5 - $raw->rating) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>