<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SearchController extends Controller
{
    public function searchAjax(Request $request)
    {
        if ($request->has('search') && $request->get('search') != '') {
            $users = User::query()
                ->with('profile')
                ->where('name', 'like', '%' . $request->get('search') . '%')
                ->orWhere('email', 'like', '%' . $request->get('search') . '%')
                ->get();

            return response()->json($users);
        } elseif ($request->has('search') && is_null($request->get('search'))) {
            $users = User::query()
                ->with('profile')
                ->get();

            return response()->json($users);
        }
        throw new BadRequestHttpException();
    }

    public function search()
    {
        return view('search');
    }
}
