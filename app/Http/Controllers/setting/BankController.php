<?php

namespace App\Http\Controllers\setting;

use App\model\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    public function index()
    {
        $bank = Bank::all();
        return $bank;
    }
}
