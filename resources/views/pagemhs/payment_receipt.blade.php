<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembayaran</title>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan */
    </style>
</head>
<body>
    <h1>Bukti Pembayaran</h1>
    <p>Nomor Order: {{ $order->number }}</p>
    <p>Status Pembayaran: {{ $order->payment_status }}</p>
    <p>Total Pembayaran: {{ number_format($order->total_price, 2) }}</p>
    <p>Tanggal Pembayaran: {{ $order->updated_at }}</p>
    <!-- Tambahkan informasi lain yang relevan -->
</body>
</html>