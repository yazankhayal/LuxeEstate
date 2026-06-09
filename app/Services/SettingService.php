<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public function all(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    public function getGroup(string $group): array
    {
        return Setting::group($group);
    }

    public function updateMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }

    public function uploadLogo($file): string
    {
        $old = Setting::get('logo');
        if ($old) {
            Storage::disk('public')->delete($old);
        }

        $path = $file->store('settings', 'public');
        Setting::set('logo', $path);

        return $path;
    }

    public function uploadFavicon($file): string
    {
        $old = Setting::get('favicon');
        if ($old) {
            Storage::disk('public')->delete($old);
        }

        $path = $file->store('settings', 'public');
        Setting::set('favicon', $path);

        return $path;
    }

    public function getForFrontend(): array
    {
        return Cache::rememberForever('frontend_settings', function () {
            return [
                'site_name'       => Setting::get('site_name', 'Real Estate Agency'),
                'site_description'=> Setting::get('site_description', ''),
                'logo'            => Setting::get('logo') ? Storage::disk('public')->url(Setting::get('logo')) : null,
                'favicon'         => Setting::get('favicon') ? Storage::disk('public')->url(Setting::get('favicon')) : null,
                'phone'           => Setting::get('phone', ''),
                'email'           => Setting::get('email', ''),
                'address'         => Setting::get('address', ''),
                'whatsapp'        => Setting::get('whatsapp', ''),
                'facebook'        => Setting::get('facebook', ''),
                'instagram'       => Setting::get('instagram', ''),
                'twitter'         => Setting::get('twitter', ''),
                'linkedin'        => Setting::get('linkedin', ''),
                'youtube'         => Setting::get('youtube', ''),
                'default_locale'  => Setting::get('default_locale', 'en'),
            ];
        });
    }
}
