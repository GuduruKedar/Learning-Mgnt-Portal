<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="utf-8">
    <title>Bulk Upload Errors Report</title>
    <!--[if gte mso 9]>
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>100</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
    </xml>
    <![endif]-->
    <style>
        body {
            font-family: 'Calibri', 'Arial', sans-serif;
            font-size: 11pt;
            color: #222222;
            margin: 20mm;
        }
        .header-title {
            font-size: 18pt;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .header-sub {
            font-size: 11pt;
            color: #475569;
            margin-bottom: 18px;
        }
        .summary-box {
            background-color: #f8fafc;
            border: 1.5pt solid #cbd5e1;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 10.5pt;
        }
        .badge-fail {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .error-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .error-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            font-size: 10.5pt;
            border: 1pt solid #1e293b;
        }
        .error-table td {
            padding: 8px 10px;
            font-size: 10pt;
            border: 1pt solid #e2e8f0;
            vertical-align: top;
        }
        .error-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .error-text {
            color: #dc2626;
            font-weight: 500;
        }
        .footer-text {
            font-size: 9pt;
            color: #64748b;
            margin-top: 30px;
            border-top: 1pt solid #cbd5e1;
            padding-top: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header-title">Vignan's University - Bulk Upload Error Report</div>
    <div class="header-sub">Learning Management Portal &bull; Validation Diagnostics</div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td style="width: 25%;"><strong>Target Role:</strong></td>
                <td style="width: 25%;">{{ $roleName }}</td>
                <td style="width: 25%;"><strong>Generated On:</strong></td>
                <td style="width: 25%;">{{ now()->format('d M Y, h:i:s A') }}</td>
            </tr>
            <tr>
                <td><strong>Source File:</strong></td>
                <td>{{ $history->file_name ?? 'N/A' }}</td>
                <td><strong>Uploaded By:</strong></td>
                <td>{{ $history->uploader->username ?? 'System Administrator' }}</td>
            </tr>
            <tr>
                <td><strong>Total Rows Processed:</strong></td>
                <td><strong>{{ $history->total_rows ?? count($errors) }}</strong></td>
                <td><strong>Failed Rows:</strong></td>
                <td><span class="badge-fail">{{ count($errors) }} Errors</span></td>
            </tr>
        </table>
    </div>

    <p style="font-size: 10.5pt; color: #334155; margin-bottom: 10px;">
        <strong>Instructions:</strong> Please correct the indicated cells or register numbers in your spreadsheet according to the details below, and then re-upload the file.
    </p>

    <table class="error-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 15%;">Row Reference</th>
                <th style="width: 77%;">Validation Error Message</th>
            </tr>
        </thead>
        <tbody>
            @php $idx = 1; @endphp
            @foreach($errors as $key => $error)
                @if($key !== '__truncated')
                    @php
                        // Extract row number and message if formatted like "Row 5 (...): message"
                        $rowRef = "Item #{$idx}";
                        $errMsg = $error;
                        if (preg_match('/^(Row\s+\d+[^:]*):(.*)$/i', $error, $m)) {
                            $rowRef = trim($m[1]);
                            $errMsg = trim($m[2]);
                        }
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: bold; color: #64748b;">{{ $idx++ }}</td>
                        <td style="font-weight: bold; color: #1e293b;">{{ $rowRef }}</td>
                        <td class="error-text">{{ $errMsg }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    @if(isset($errors['__truncated']))
        <div style="margin-top: 15px; padding: 10px 14px; background-color: #fffbeb; border: 1pt solid #fde68a; color: #92400e; font-size: 10pt; font-weight: bold;">
            &bull; Notice: {{ $errors['__truncated'] }}
        </div>
    @endif

    <div class="footer-text">
        This document was automatically generated by Vignan LMS Validation Engine &bull; {{ now()->format('Y') }}
    </div>

</body>
</html>
