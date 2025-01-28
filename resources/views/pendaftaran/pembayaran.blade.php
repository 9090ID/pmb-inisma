<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Pembayaran</title>
</head>
<body>
<div class="container mt-5">
    <h1>Pembayaran</h1>
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Kk0iIsDnj51sHt7l"></script>

<script>
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                // Tindakan setelah pembayaran berhasil
                console.log(result);
                alert('Pembayaran berhasil!');
            },
            onPending: function (result) {
                // Tindakan setelah pembayaran tertunda
                console.log(result);
                alert('Pembayaran tertunda!');
            },
            onError: function (result) {
                // Tindakan setelah pembayaran gagal
                console.log(result);
                alert('Pembayaran gagal!');
            }
        });
    };
</script>
</body>
</html>