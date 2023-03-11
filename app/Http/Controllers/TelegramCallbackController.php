<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pschocke\TelegramLoginWidget\Facades\TelegramLoginWidget;

class TelegramCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        if (!$telegramUser = TelegramLoginWidget::validate($request)) {
            notify()->error('Please, try again!', position: 'topRight');
            return redirect()->route('account.index');
        }

//        dd($telegramUser);

        auth()->user()->update([
            'telegram_id' => $telegramUser->get('id')
        ]);

        notify()->success('Thank you!', position: 'topRight');
        return redirect()->route('account.index');
    }
}
