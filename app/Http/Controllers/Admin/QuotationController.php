<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationMail;
use App\Mail\QuotationReceived;

class QuotationController extends Controller {


    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'country' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'message' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.name' => 'required|string',
            'products.*.code' => 'required|string',  // Validate product code
            'products.*.image' => 'required|string', // Validate product image URL
        ]);

        // Encode the products array to JSON before saving
        $data['products'] = json_encode($data['products']);

        // Save quotation
        Quotation::create($data);

        // Send email with quotation details
        Mail::to('admin@rasthal.store')->send(new QuotationMail($data));
        // Enviar email al usuario
    Mail::to($data['email'])->send(new QuotationReceived($data['email']));

        return response()->json(['message' => 'Quotation request submitted successfully'], 201);
    }



}
