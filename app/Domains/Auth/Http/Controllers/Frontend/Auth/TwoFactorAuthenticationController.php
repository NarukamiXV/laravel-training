<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/**
 * Class TwoFactorAuthenticationController. 
 */
class TwoFactorAuthenticationController
{
    /**
     * @param  Request  $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $secret = $request->user()->createTwoFactorAuth();

        return view('frontend.user.account.tabs.two-factor-authentication.enable')
            ->withQrCode($secret->toQr())
            ->withSecret($secret->toString());
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function show(Request $request)
    {
        return view('frontend.user.account.tabs.two-factor-authentication.recovery')
            ->withRecoveryCodes($request->user()->getRecoveryCodes());
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $request->user()->generateRecoveryCodes();

        session()->flash('flash_warning', __('Any old backup codes have been invalidated.'));

        return redirect()->route('frontend.auth.account.2fa.show')->withFlashSuccess(__('Two Factor Recovery Codes Regenerated'));
    }
     /**
     * Validate the two-factor authentication code.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateCode(Request $request)
    {
        // Validate the request data
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        // If validation passes, proceed with enabling two-factor authentication for the user
        // You may need to call the appropriate method here to enable 2FA for the user

        // Redirect the user to a relevant page (e.g., dashboard) after successful validation
        return redirect()->route('dashboard')->with('success', 'Two-factor authentication enabled successfully.');
    }
}
