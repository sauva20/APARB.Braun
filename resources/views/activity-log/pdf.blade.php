<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Audit Trail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #009B77;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #009B77;
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #555;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background-color: #e8f5f3;
            color: #009B77;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            text-align: center;
        }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .bg-green { background-color: #d1fae5; color: #059669; }
        .bg-red { background-color: #fee2e2; color: #dc2626; }
        .bg-orange { background-color: #fef3c7; color: #d97706; }
        
        .footer {
            margin-top: 40px;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Audit Trail PFE Monitoring Control System</h1>
        <p>Comprehensive Record of System Activities</p>
    </div>

    @php
        $activeFilters = [];
        // Add filters here if Activity Log filtering is implemented in the future
    @endphp

    <table style="width: 100%; margin-bottom: 8px; border: none;">
        <tr>
            <td style="text-align: left; font-size: 11px; border: none !important; padding: 0 !important; background: transparent;">
                Identification Date: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}
            </td>
            <td style="text-align: right; font-size: 11px; border: none !important; padding: 0 !important; background: transparent;">
                @if(count($activeFilters) > 0)
                    {{ implode(' | ', $activeFilters) }}
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">{{ __('Time') }}</th>
                <th style="width: 20%;">{{ __('User') }}</th>
                <th class="text-center" style="width: 15%;">{{ __('Action') }}</th>
                <th style="width: 15%;">{{ __('Module') }}</th>
                <th style="width: 30%;">{{ __('Description') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td class="font-bold">{{ $activity->created_at->format('d M Y H:i:s') }}</td>
                    <td>{{ $activity->causer->name ?? __('System / Guest') }}</td>
                    <td class="text-center font-bold">{{ strtoupper($activity->event) }}</td>
                    <td>{{ __(preg_replace('/([a-z])([A-Z])/s', '$1 $2', class_basename($activity->subject_type))) }}</td>
                    <td>{{ __($activity->description) }}</td>
                </tr>
            @endforeach
            
            @if($activities->isEmpty())
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">{{ __('No activity log yet.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
