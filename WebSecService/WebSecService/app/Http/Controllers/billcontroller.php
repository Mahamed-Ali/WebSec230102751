<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    public function showBill()
    {
        $items = [
            ['name' => 'Milk', 'quantity' => 2, 'price' => 1.50],
            ['name' => 'Bread', 'quantity' => 1, 'price' => 2.00],
            ['name' => 'Eggs', 'quantity' => 12, 'price' => 0.20],
            ['name' => 'Apple', 'quantity' => 6, 'price' => 0.50],
        ];

        return view('bill', compact('items'));
    }
}
