{{-- resources/views/emails/password-reset.blade.php --}}
@extends('emails.layout')

@section('title', 'Reset Your Password — ' . ($settings['site_name'] ?? 'Luxe Estate'))

@section('content')
    <!-- Golden Status Tag -->
    <p style="margin: 0 0 15px 0; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #C5A880; font-weight: bold;">
        Security Notification
    </p>

    <!-- Heading -->
    <h1 style="margin: 0 0 20px 0; font-family: 'Times New Roman', Georgia, serif; font-size: 26px; font-weight: normal; line-height: 1.4; color: #FFFFFF;">
        Password Reset Request
    </h1>

    <!-- Body Text -->
    <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.6; color: #AFB6C2;">
        Hello,
    </p>
    <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 1.6; color: #AFB6C2;">
        A request has been received to restore access or reset the password associated with your premium account on {{ $settings['site_name'] ?? 'Luxe Estate' }}. Please proceed by using the button below.
    </p>

    <!-- Premium Action Button -->
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 35px;">
        <tr>
            <td align="center">
                <a href="{{ $url }}" class="action-btn" style="display: inline-block; background-color: #C5A880; color: #0B0E14; font-size: 13px; font-weight: bold; text-decoration: none; text-transform: uppercase; letter-spacing: 1.5px; padding: 16px 40px; border-radius: 6px; box-shadow: 0 6px 20px rgba(197, 168, 128, 0.25);">
                    Reset My Password
                </a>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 25px 0; font-size: 14px; line-height: 1.6; color: #AFB6C2;">
        <strong style="color: #FFFFFF;">Security Notice:</strong> This secure recovery link expires automatically in <span style="color: #C5A880; font-weight: bold;">60 minutes</span>. If you did not initiate this action, your account details remain entirely secure and you can safely disregard this transmission.
    </p>

    <hr style="border: none; border-top: 1px solid #222B3C; margin: 30px 0;" />

    <!-- Fallback URL -->
    <p style="margin: 0; font-size: 12px; line-height: 1.6; color: #717D90; word-break: break-all;">
        If the button above is not working, paste this secure URL directly into your web browser:<br />
        <a href="{{ $url }}" style="color: #C5A880; text-decoration: underline;">{{ $url }}</a>
    </p>
@endsection