<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    public function edit()
    {
        $settings = CompanySetting::get();
        return view('settings.company', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'    => 'required|string|max:255',
            'company_email'   => 'nullable|email|max:255',
            'company_phone'   => 'nullable|string|max:50',
            'invoice_prefix'  => 'required|string|max:10|alpha_num',
            'currency_symbol' => 'required|string|max:10',
            'currency_code'   => 'required|string|max:5',
            'tax_percentage'  => 'nullable|numeric|min:0|max:100',
            'logo'            => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $settings = CompanySetting::get();

        // company_id / id ko kabhi overwrite na hone do (security)
        $data = $request->except(['_token', '_method', 'logo', 'company_id', 'id']);

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $settings->update($data);
        $data['tax_percentage'] = $request->tax_percentage ?? 0;

        return redirect()->route('settings.company.edit')
            ->with('success', 'Company settings saved successfully!');
    }
}
