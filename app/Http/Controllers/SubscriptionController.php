<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationForm;
use Illuminate\Http\Request;


class SubscriptionController extends Controller
{
    public function store(RegistrationForm $form)
    {
        try {
            $form->save();
        } catch (\Exception $e) {
            return response()->json(['status' => $e->getMessage()], 422);
        }

        return ['status' => 'Success!'];
    }

    public function destroy()
    {
        auth()->user()->subscription()->cancel();

        return back();
    }
}
