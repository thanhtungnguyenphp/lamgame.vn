@extends('shop::layouts.master')
@section('content-wrapper')
<div class="container" style="text-align:center;padding:60px 20px">
    <h2>Đang xử lý thanh toán...</h2>
    <p id="msg">Vui lòng chờ trong giây lát.</p>
</div>
<script>
(function() {
    let attempts = 0;
    const timer = setInterval(function() {
        if (++attempts > 30) {
            clearInterval(timer);
            document.getElementById('msg').textContent = 'Thanh toán đang được xử lý. Bạn sẽ nhận email xác nhận.';
            return;
        }
        fetch('{{ $checkUrl }}?cart_id={{ $cartId }}')
            .then(r => r.json())
            .then(d => { if (d.ready) { clearInterval(timer); window.location = '{{ route("shop.checkout.onepage.success") }}'; }});
    }, 2000);
})();
</script>
@endsection
