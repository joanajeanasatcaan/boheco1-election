<?php

namespace App\Http\Controllers;
use App\Models\OnlineVotersReceipts;

use Illuminate\Http\Request;

class OnlineVotersReceiptsController extends Controller
{
    public function index(){
        $online_voters_receipts = OnlineVotersReceipts::all();
        return view ('ecom.online-voters-receipts', ['online_voters_receipts' => $online_voters_receipts]);
    }
}
