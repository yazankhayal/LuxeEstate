{{-- resources/views/emails/layout.blade.php --}}
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>@yield('title', $settings['meta_title'] ?? 'Luxe Estate')</title>
    <style>
        @media only screen and (max-width: 620px) {
            .wrapper { width: 100% !important; padding: 15px 0 !important; }
            .container { width: 100% !important; padding: 0 10px !important; }
            .content-card { padding: 30px 20px !important; }
            .action-btn { width: 100% !important; text-align: center !important; display: block !important; box-sizing: border-box; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #0B0E14; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-text-size-adjust: none;">

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #0B0E14; width: 100%; margin: 0; padding: 40px 0;">
    <tr>
        <td align="center">
            <table class="container" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width: 600px; margin: 0 auto;">

                <!-- 1. DYNAMIC BRAND HEADER -->
                <tr>
                    <td align="center" style="padding-bottom: 35px;">
                        <span style="font-family: 'Times New Roman', Georgia, serif; font-size: 28px; font-weight: bold; color: #C5A880; letter-spacing: 3px; text-transform: uppercase;">
                            {{ $settings['site_name'] ?? 'Luxe Estate' }}
                        </span>
                        <div style="font-size: 10px; color: #8F9CAE; letter-spacing: 2px; text-transform: uppercase; margin-top: 6px;">
                            {{ $settings['site_description'] ?? 'Premium Real Estate Agency' }}
                        </div>
                    </td>
                </tr>

                <!-- 2. DYNAMIC CONTENT INSERTION -->
                <tr>
                    <td class="content-card" style="background-color: #161B26; border: 1px solid #222B3C; border-radius: 12px; padding: 45px; text-align: left; box-shadow: 0 12px 35px rgba(0,0,0,0.6);">
                        @yield('content')
                    </td>
                </tr>

                <!-- 3. DYNAMIC BRAND FOOTER WITH DETAILED CONTACT INFO -->
                <tr>
                    <td align="center" style="padding-top: 40px; text-align: center;">
                        <p style="margin: 0 0 12px 0; font-size: 13px; color: #8F9CAE; font-weight: 500; letter-spacing: 0.5px;">
                            {{ $settings['site_name'] ?? 'Luxe Estate' }} — {{ $settings['site_description'] ?? 'Premium Real Estate Agency' }}
                        </p>

                        <!-- Contact Meta Line -->
                        <p style="margin: 0 0 20px 0; font-size: 12px; color: #717D90; line-height: 1.5;">
                            @if(!empty($settings['phone'])) Tel: {{ $settings['phone'] }} &nbsp;|&nbsp; @endif
                            @if(!empty($settings['email'])) {{ $settings['email'] }} <br /> @endif
                            @if(!empty($settings['address'])) {{ $settings['address'] }} @endif
                        </p>

                        <p style="margin: 0; font-size: 11px; color: #58667A; line-height: 1.6;">
                            This is an automated security transmission regarding your account credentials.<br />
                            &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Luxe Estate' }}. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>