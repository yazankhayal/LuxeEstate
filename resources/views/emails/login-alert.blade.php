{{-- resources/views/emails/login-alert.blade.php --}}
@extends('emails.layout')

@section('title', 'New Login Detected — ' . ($settings['site_name'] ?? 'Luxe Estate'))

@section('content')
    <!-- Crimson/Amber Security Status Tag -->
    <p style="margin: 0 0 15px 0; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #E57373; font-weight: bold;">
        ⚠️ Account Security Alert
    </p>

    <!-- Heading -->
    <h1 style="margin: 0 0 20px 0; font-family: 'Times New Roman', Georgia, serif; font-size: 26px; font-weight: normal; line-height: 1.4; color: #FFFFFF;">
        New Login Detected
    </h1>

    <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.6; color: #AFB6C2;">
        Hello {{ $user->name }},
    </p>
    <p style="margin: 0 0 25px 0; font-size: 15px; line-height: 1.6; color: #AFB6C2;">
        A successful login to your premium account was registered. Please review the security parameters below to verify this action was intentional:
    </p>

    <!-- Metadata Details Table -->
    <div style="background: #0B0E14; border: 1px solid #222B3C; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="padding-bottom: 10px; font-size: 12px; color: #717D90; text-transform: uppercase; font-weight: bold; width: 30%;">Account:</td>
                <td style="padding-bottom: 10px; font-size: 14px; color: #FFFFFF;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; font-size: 12px; color: #717D90; text-transform: uppercase; font-weight: bold;">Time (UTC):</td>
                <td style="padding-bottom: 10px; font-size: 14px; color: #FFFFFF;">{{ now()->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; font-size: 12px; color: #717D90; text-transform: uppercase; font-weight: bold;">IP Address:</td>
                <td style="padding-bottom: 10px; font-size: 14px; color: #C5A880; font-weight: bold;">{{ $ipAddress }}</td>
            </tr>
            <tr>
                <td style="font-size: 12px; color: #717D90; text-transform: uppercase; font-weight: bold; vertical-align: top;">Device/Agent:</td>
                <td style="font-size: 14px; color: #AFB6C2; font-family: monospace;">{{ $userAgent }}</td>
            </tr>
        </table>
    </div>

    <p style="margin: 0 0 25px 0; font-size: 14px; line-height: 1.6; color: #AFB6C2;">
        <strong style="color: #FFFFFF;">What should you do?</strong> If this login was performed by you, no further action is necessary. If you do not recognize this activity, we highly advise changing your security password immediately via your profile dashboard to safe-keep your client information.
    </p>

    <hr style="border: none; border-top: 1px solid #222B3C; margin: 30px 0;" />

    <p style="margin: 0; font-size: 11px; line-height: 1.6; color: #717D90;">
        You receive these automatic operational tracking messages to maximize data security on your associated luxury real estate profile dashboard.
    </p>
@endsection