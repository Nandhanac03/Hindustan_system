<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity & Audit Report - Hindustan ERP</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background: #ffffff;
        }
        .toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 14px;
        }
        .btn {
            padding: 7px 14px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #1e293b;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-primary {
            background: #a38c29;
            border-color: #a38c29;
            color: #ffffff;
        }
        .header {
            border-bottom: 2px solid #3e372e;
            padding-bottom: 10px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .title {
            font-size: 18px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 3px;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }
        .meta {
            text-align: right;
            font-size: 10px;
            color: #475569;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #3e372e;
            color: #ffffff;
            text-align: left;
            padding: 7px 8px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 10px;
        }
        tr:nth-child(even) td {
            background: #faf8ef;
        }
        .mono {
            font-family: Consolas, monospace;
            font-size: 9.5px;
        }
        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
            @page { size: A4 landscape; margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="btn" onclick="window.close()">Close</button>
        <button type="button" class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <div class="header">
        <div>
            <h1 class="title">Hindustan ERP — Activity &amp; Audit Report</h1>
            <p class="subtitle">
                Employee: <strong>{{ $selectedEmployee ? $selectedEmployee->name . ' (' . $selectedEmployee->email . ')' : 'All Employees' }}</strong>
                &nbsp;|&nbsp;
                Period: <strong>{{ $fromDate }}</strong> to <strong>{{ $toDate }}</strong>
            </p>
        </div>
        <div class="meta">
            <div>Generated (IST): <strong>{{ $generatedAt }}</strong></div>
            <div>Total Records: <strong>{{ $logs->count() }}</strong></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Observed Time (IST)</th>
                <th>Employee</th>
                <th>Category</th>
                <th>Action</th>
                <th>Module</th>
                <th>Screen</th>
                <th>Description</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $idx => $log)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td style="white-space: nowrap;">{{ $log->created_at?->copy()->setTimezone('Asia/Kolkata')->format('d M Y, H:i:s') ?? '-' }}</td>
                    <td>
                        <strong>{{ $log->user?->name ?? 'System' }}</strong><br>
                        <span style="color:#64748b;">{{ $log->user?->email ?? '-' }}</span>
                    </td>
                    <td>{{ strtoupper((string) ($log->category ?? 'action')) }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->module ?? 'General' }}</td>
                    <td class="mono">{{ $log->screen ?? '-' }}</td>
                    <td>{{ $log->description }}</td>
                    <td class="mono">{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #64748b;">No activity records found for the selected filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 300);
        });
    </script>
</body>
</html>

