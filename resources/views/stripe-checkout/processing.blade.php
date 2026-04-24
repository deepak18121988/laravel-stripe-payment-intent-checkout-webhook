@extends('layouts.app')

@section('content')
<div class="container" style="min-height:60vh; display:flex; align-items:center; justify-content:center;">
    <div class="text-center">
        <div class="spinner-border text-primary mb-3"></div>
        <h4>Processing your payment</h4>
        <p class="text-muted">
            Please wait while we confirm your payment.<br>
            Do not refresh or close this page.
        </p>
    </div>
</div>

<script>
(function () {
    const params = new URLSearchParams(window.location.search);
    const paymentIntent = params.get('payment_intent');

    if (!paymentIntent) {
        window.location.href = "{{ route('stripe-checkout.failed') }}";
        return;
    }

    let attempts = 0;
    const maxAttempts = 10;

    const checkStatus = async () => {
        attempts++;

        try {
            const res = await fetch("{{ route('stripe-checkout.status') }}?payment_intent=" + paymentIntent);
            const data = await res.json();

            if (data.status === 'succeeded') {
                window.location.href = "{{ route('stripe-checkout.success') }}";
                return;
            }

            if (data.status === 'failed') {
                window.location.href = "{{ route('stripe-checkout.failed') }}";
                return;
            }

            if (attempts >= maxAttempts) {
                window.location.href = "{{ route('stripe-checkout.pending') }}";
                return;
            }

        } catch (e) {
            console.error(e);
        }

        setTimeout(checkStatus, 3000);
    };

    setTimeout(checkStatus, 2000);
})();
</script>
@endsection
