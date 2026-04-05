<!DOCTYPE html>
<html>
<head>
    <title>Booking Report</title>
    <style>
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
    <h2>Booking Report</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Penumpang</th>
                <th>Bus</th>
                <th>Supir</th>
                <th>Penjemputan</th>
                <th>Tujuan</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_booking as $raw)
            <tr>
                <td>{{ $loop->iteration }}</td>
                 <td>{{ $raw->nama_penumpang }}</td>
                <td>{{ $raw->nama_bus }}</td>
                <td>{{ $raw->nama_supir }}</td>
                <td>{{ $raw->halte_penjemputan }}</td>
                <td>{{ $raw->halte_tujuan }}</td>
                <td>{{ $raw->created_at }}</td>
                <td>
                    @if ($raw->status == 'pending')
                        <span>Pending</span>
                    @elseif ($raw->status == 'aktif')
                        <span>Aktif</span>
                    @elseif ($raw->status == 'selesai')
                        <span>Selesai</span>
                    @elseif ($raw->status == 'cancel')
                        <span>Cancel</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>