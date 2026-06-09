{{-- resources/views/emails/contact-inquiry.blade.php --}}
@extends('emails.layout')

@section('title', $contact->property_id ? "Property Inquiry — Luxe Estate" : "General Inquiry — Luxe Estate")

@section('content')
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 20px;">
        <tr>
            <td>
                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; background: rgba(197, 168, 128, 0.15); color: #C5A880;">
                    @if($contact->property_id) 🏢 Property Inquiry @else ✉️ General Lead @endif
                </span>
            </td>
            <td align="right" style="font-size:12px; color: #717D90;">
                {{ now()->format('d M Y, H:i') }}
            </td>
        </tr>
    </table>

    <h1 style="margin: 0 0 25px 0; font-family: 'Times New Roman', Georgia, serif; font-size: 24px; font-weight: normal; line-height: 1.4; color: #FFFFFF; border-bottom: 1px solid #222B3C; padding-bottom: 15px;">
        @if($contact->property_id) New Property Inquiry @else New Contact Inquiry @endif
    </h1>

    <div style="margin-bottom: 25px;">

        @if($contact->property_id)
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 11px; text-transform: uppercase; tracking: 1.5px; color: #717D90; font-weight: bold; margin-bottom: 4px;">Target Property</label>
                <p style="margin: 0; font-size: 15px; color: #C5A880; font-weight: 500;">
                    {{ $contact->property?->trans('title') ?? 'N/A' }}
                </p>
            </div>
        @endif

        <div style="margin-bottom: 18px;">
            <label style="display: block; font-size: 11px; text-transform: uppercase; tracking: 1.5px; color: #717D90; font-weight: bold; margin-bottom: 4px;">Inquirer Name</label>
            <p style="margin: 0; font-size: 15px; color: #FFFFFF;">{{ $contact->name }}</p>
        </div>

        <div style="margin-bottom: 18px;">
            <label style="display: block; font-size: 11px; text-transform: uppercase; tracking: 1.5px; color: #717D90; font-weight: bold; margin-bottom: 4px;">Email Address</label>
            <p style="margin: 0; font-size: 15px; color: #AFB6C2;">
                <a href="mailto:{{ $contact->email }}" style="color: #C5A880; text-decoration: none;">{{ $contact->email }}</a>
            </p>
        </div>

        @if($contact->phone)
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 11px; text-transform: uppercase; tracking: 1.5px; color: #717D90; font-weight: bold; margin-bottom: 4px;">Phone Number</label>
                <p style="margin: 0; font-size: 15px; color: #AFB6C2;">{{ $contact->phone }}</p>
            </div>
        @endif

        @if($contact->subject)
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 11px; text-transform: uppercase; tracking: 1.5px; color: #717D90; font-weight: bold; margin-bottom: 4px;">Subject</label>
                <p style="margin: 0; font-size: 15px; color: #FFFFFF; font-style: italic;">{{ $contact->subject }}</p>
            </div>
        @endif

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 11px; text-transform: uppercase; tracking: 1.5px; color: #717D90; font-weight: bold; margin-bottom: 6px;">Submitted Message</label>
            <div style="background: #0B0E14; border-left: 3px solid #C5A880; padding: 16px; border-radius: 6px;">
                <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #AFB6C2; white-space: pre-line;">{{ $contact->message }}</p>
            </div>
        </div>
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: 25px; margin-bottom: 15px;">
        <tr>
            <td align="center">
                <a href="{{ route('admin.contacts.show', $contact) }}" class="action-btn" style="display: inline-block; background-color: #C5A880; color: #0B0E14; font-size: 12px; font-weight: bold; text-decoration: none; text-transform: uppercase; letter-spacing: 1.5px; padding: 14px 32px; border-radius: 6px; box-shadow: 0 6px 20px rgba(197, 168, 128, 0.15);">
                    Review Inquiry Dashboard &rarr;
                </a>
            </td>
        </tr>
    </table>
@endsection