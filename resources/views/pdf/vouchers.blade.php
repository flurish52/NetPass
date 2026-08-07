<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .grid { display: flex; flex-wrap: wrap; }
        .voucher {
            width: 30%;
            border: 1px dashed #999;
            border-radius: 6px;
            padding: 10px;
            margin: 1.5%;
            text-align: center;
        }
        .code { font-size: 16px; font-weight: bold; letter-spacing: 1px; margin: 6px 0; }
        .plan { font-size: 11px; color: #555; }
        .brand { font-size: 10px; color: #888; }
    </style>
</head>
<body>
<div class="grid">
    @foreach ($vouchers as $voucher)
        <div class="voucher">
            <div class="brand">{{ $business->name }}</div>
            <div class="code">{{ $voucher->code }}</div>
            <div class="plan">{{ $voucher->plan->name }} &middot; ₦{{ number_format($voucher->plan->price / 100, 2) }}</div>
        </div>
    @endforeach
</div>
</body>
</html>
