<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }

        .reference-box {
            background: #f0f9ff;
            border: 2px dashed #0d6efd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .reference-number {
            font-size: 28px;
            font-weight: bold;
            color: #0d6efd;
            letter-spacing: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .info-table th,
        .info-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
            width: 40%;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .items-table th {
            background: #e8f4fd;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
        }

        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-warning {
            background: #fff3cd;
            color: #664d03;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
        }

        .note-box {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 12px 16px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0;">{{ config('app.name', 'Relief') }}</h1>
        <p style="margin: 5px 0 0;">Donation Drive Management System</p>
    </div>

    <div class="content">
        <span class="badge badge-warning">Pledge Received</span>

        <h2 style="margin-top: 10px;">Thank you for your pledge, {{ $user->name }}!</h2>

        <p>Your donation pledge has been received and is now <strong>pending verification</strong> by our admin team.
            Below are the details of your pledge:</p>

        <div class="reference-box">
            <p style="margin: 0 0 5px; color: #666; font-size: 14px;">Your Reference Number</p>
            <div class="reference-number">{{ $pledge->reference_number }}</div>
            <p style="margin: 5px 0 0; color: #888; font-size: 12px;">Please keep this for your records and present it
                at the drop-off point.</p>
        </div>

        <h3 style="color: #333; border-bottom: 2px solid #e0e0e0; padding-bottom: 8px;">Pledge Details</h3>

        <table class="info-table">
            <tr>
                <th>Drive</th>
                <td>{{ $drive->name }}</td>
            </tr>
            @if ($drive->address)
                <tr>
                    <th>Drop-off Location</th>
                    <td>{{ $drive->address }}</td>
                </tr>
            @endif
            <tr>
                <th>Date Submitted</th>
                <td>{{ $pledge->created_at->format('F j, Y \a\t g:i A') }}</td>
            </tr>
            @if ($pledge->contact_number)
                <tr>
                    <th>Contact Number</th>
                    <td>{{ $pledge->contact_number }}</td>
                </tr>
            @endif
            <tr>
                <th>Status</th>
                <td><span class="badge badge-warning">Pending Verification</span></td>
            </tr>
        </table>

        @if ($items->count())
            <h3 style="color: #333; border-bottom: 2px solid #e0e0e0; padding-bottom: 8px;">Items Pledged</h3>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Quantity</th>
                        <th style="text-align: center;">Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td style="text-align: center;">{{ number_format($item->quantity) }}</td>
                            <td style="text-align: center;">{{ $item->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($pledge->details)
            <div class="note-box">
                <strong>Additional Details:</strong><br>
                {{ $pledge->details }}
            </div>
        @endif

        @if ($pledge->notes)
            <div class="note-box">
                <strong>Notes:</strong><br>
                {{ $pledge->notes }}
            </div>
        @endif

        <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; margin: 20px 0;">
            <h4 style="margin: 0 0 8px; color: #166534;">What happens next?</h4>
            <ol style="margin: 0; padding-left: 20px; color: #555;">
                <li>Our admin team will review and verify your pledge.</li>
                <li>You'll receive a notification once your pledge is verified.</li>
                <li>Bring your donation to the drop-off location and show your reference number.</li>
                <li>Once distributed, you'll receive an impact report.</li>
            </ol>
        </div>

        <p style="color: #6c757d; font-size: 14px;">
            This notification was sent on {{ now()->format('F j, Y \a\t g:i A') }}.
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Relief') }}. All rights reserved.</p>
        <p>
            Department of Social Welfare and Development (DSWD)<br>
            Disaster Relief Coordination System
        </p>
        <p style="margin-top: 15px;">
            <small>You received this email because you submitted a pledge on Relief.
                If you believe this was sent in error, please contact support.</small>
        </p>
    </div>
</body>

</html>
