<?php

namespace App\Http\Controllers\Bookvi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function toLogin(){
	    return view('book/login');
    }

	public function toRegister() {
		return view('book/register');
    }
}
