<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\Language;
use App\Models\Setting;
use App\Services\SettingService;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function __construct(private readonly SettingService $settingService)
    {
    }

    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings'  => $this->settingService->all(),
            'languages' => Language::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateSettingRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $this->settingService->uploadLogo($request->file('logo'));
            unset($data['logo']);
        }

        if ($request->hasFile('favicon')) {
            $this->settingService->uploadFavicon($request->file('favicon'));
            unset($data['favicon']);
        }

        $this->settingService->updateMany($data);
        Setting::clearCache();

        return back()->with('success', 'Settings saved successfully.');
    }
}
