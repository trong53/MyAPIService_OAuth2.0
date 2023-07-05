<?php
use Illuminate\Support\Facades\Auth;

function checkAuthUser() {
    // return Auth::user();
    return request()->user();
}