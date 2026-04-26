@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://js.stripe.com/v3/"></script>

<style>
.alert { padding:12px; border-radius:4px; margin-top:8px; }
.alert-danger { background:#f8d7da; color:#842029; border:1px solid #f5c2c7; }
#submitBtn:disabled { opacity:0.5; cursor:not-allowed; }
.loader { display:none; margin-top:10px; }

</style>

  <!--Main layout-->
    <div class="container wow fadeIn">

      <!-- Heading -->
      <h2 class="my-5 h2 text-center">Checkout form</h2>

      <!--Grid row-->
      <div class="row">

        <!--Grid column-->
        <div class="col-md-8 mb-4">

          <!--Card-->
          <div class="card">

            <!--Card content-->
            <form class="card-body" autocomplete="off" name="checkout" id="payment-form" method="POST" novalidate="novalidate" action="">  
              <h3>Personal Details</h3>
              <!--Grid row-->
              <div class="row">

                <!--Grid column-->
                <div class="col-md-6 mb-2">

                  <!--firstName-->
                  <div class="md-form ">
                    <input type="text" class="form-control details_first_name" id="first_name" name="first_name" required=""
                        value="Deepak" />
                      <label class="form-control-placeholder" for="name">First Name</label>
                      <label id="first_name_error"  class="error text-danger small" for="first_name" ></label>

                  </div>

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-md-6 mb-2">

                  <!--lastName-->
                  <div class="md-form">
                    <input value="Lohani" type="text" class="form-control details_last_name" id="last_name" name="last_name"
                        required="" />
                      <label class="form-control-placeholder" for="name">Last Name</label>
                      <label id="last_name_error" class="error text-danger small" for="last_name" ></label>
                  </div>

                </div>
                <!--Grid column-->

              </div>
              <!--Grid row-->

              <!--email-->
              <div class="md-form mb-5" id="link-authentication-element">
              </div>

              <h3>Payment Details</h3>

              <div class="row" id="credit-form">
                <div class="col-md-12 md-form mb-5" id="payment-element">
                  
                </div>
              </div>
              <h5>Billing Information</h5>
              <div class="row" id="billing-form">
                <div class="col-md-12 md-form mb-5" id="address-element"></div>
                
                <div class="col-md-12">
                  <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block waves-effect waves-light">Confirm and
                    pay</button>
                  <div class="loader">Processing Payment...</div>
                </div>
              </div>
            </form>

          </div>
          <!--/.Card-->

        </div>
        <!--Grid column-->

        <!--Grid column-->
        <div class="col-md-4 mb-4">

          <!-- Heading -->
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Your cart</span>
            <span class="badge badge-secondary badge-pill">3</span>
          </h4>

          <!-- Cart -->
          <ul class="list-group mb-3 z-depth-1">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Product name</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$80</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Second product</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$20</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Third item</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$5</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light">
              <div class="text-success">
                <h6 class="my-0">Promo code</h6>
                <small>EXAMPLECODE</small>
              </div>
              <span class="text-success">-$5</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Total (USD)</span>
              <strong>$100</strong>
            </li>
          </ul>
          <!-- Cart -->

          <!-- Promo code -->
          <form class="card p-2">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Promo code" aria-label="Recipient's username" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-secondary btn-md waves-effect m-0" type="button">Redeem</button>
              </div>
            </div>
          </form>
          <!-- Promo code -->

        </div>
        <!--Grid column-->

      </div>
      <!--Grid row-->

    </div>
  <!--Main layout-->

<script>


(async function () {

    /* =========================================================
       STRIPE INITIALIZATION
    ========================================================== */
    // Initialize Stripe with publishable key
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");

    /* =========================================================
       STEP 1: CREATE PAYMENT INTENT (SERVER SIDE)
    ========================================================== */
    let intent;

    try {

        const transactionId = "{{ $data['transaction']->Transaction_ID }}";

        // Convert to cents for Stripe
        //const amount = "{{ $data['transaction']->Sale_Price }}";
        const amount = Number("{{ $data['transaction']->Sale_Price }}");

        const currency = 'eur';
        // Call Laravel API to create PaymentIntent
        const resp = await fetch("{{ route('stripe-checkout.create-intent') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },
            body: JSON.stringify({
                transaction_id: transactionId, // Internal transaction ID
                amount: amount,       // Amount in major units
                currency: currency
            })
        });
        // Parse response
        intent = await resp.json();

        // Handle backend error
        if (intent.error) {
            alert(intent.error);
            return;
        }

    } catch (err) {
        console.error(err);
        alert("Failed to initialize payment: " + err.message);
        return;
    }

    /* =========================================================
       STEP 2: INITIALIZE STRIPE ELEMENTS
    ========================================================== */
    // Create Elements instance using client secret
    const elements = stripe.elements({
        clientSecret: intent.clientSecret
    });

    // Email / Link authentication element
    const linkAuth = elements.create("linkAuthentication");
    linkAuth.mount("#link-authentication-element");

    // Billing address element (Germany only)
    const address = elements.create("address", {
        mode: "billing",
        allowedCountries: ['DE']
    });
    address.mount("#address-element");

    // Payment element (Card / Wallets / etc.)
    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");

    /* =========================================================
       FORM HANDLING
    ========================================================== */
    const form   = document.getElementById('payment-form');
    const btn    = document.getElementById('submitBtn');
    const loader = document.querySelector('.loader');

    // Error containers
    const firstNameErrDiv = document.getElementById("first_name_error");
    const lastNameErrDiv  = document.getElementById("last_name_error");

    /* =========================================================
       FORM SUBMIT EVENT
    ========================================================== */
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Reset previous errors
        firstNameErrDiv.innerText = "";
        lastNameErrDiv.innerText  = "";

        // Collect user input
        const firstName = document.getElementById('first_name').value.trim();
        const lastName  = document.getElementById('last_name').value.trim();

        /* ---------------------------------------------------------
           FRONTEND VALIDATION
        ---------------------------------------------------------- */
        if (!firstName) {
            firstNameErrDiv.innerText = "Please enter First Name.";
        }

        if (!lastName) {
            lastNameErrDiv.innerText = "Please enter Last Name.";
        }

        if (!firstName || !lastName) return;

        if (firstName.length < 2) {
            firstNameErrDiv.innerText = "First Name must be at least 2 characters.";
            return;
        }

        if (lastName.length < 2) {
            lastNameErrDiv.innerText = "Last Name must be at least 2 characters.";
            return;
        }

        // Disable button & show loader
        btn.disabled = true;
        loader.style.display = 'block';

        try {

            /* =====================================================
               STEP 3: PRE-VALIDATION IN LARAVEL
               (NO STRIPE CALL — DB & DATA VALIDATION ONLY)
            ====================================================== */
            const validateResp = await fetch("{{ route('stripe-checkout.pre-validate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector("meta[name='csrf-token']")
                        .content
                },
                body: JSON.stringify({
                    payment_intent_id: intent.paymentIntentId,
                    first_name: firstName,
                    last_name: lastName
                })
            });

            const validateData = await validateResp.json();

            // Handle Laravel validation errors
            if (validateData.errors) {
                if (validateData.errors.first_name) {
                    firstNameErrDiv.innerText = validateData.errors.first_name[0];
                }
                if (validateData.errors.last_name) {
                    lastNameErrDiv.innerText = validateData.errors.last_name[0];
                }

                btn.disabled = false;
                loader.style.display = 'none';
                return;
            }

            /* =====================================================
               STEP 4: STRIPE ELEMENTS SUBMISSION
            ====================================================== */
            // Trigger Stripe Elements validation
            const { error: submitError } = await elements.submit();
            if (submitError) throw submitError;

            /* =====================================================
               STEP 5: CONFIRM PAYMENT WITH STRIPE
            ====================================================== */
            const result = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Redirects only if required (3DS, etc.)
                    return_url: "{{ route('stripe-checkout.processing') }}"
                }
            });

            /* =====================================================
               HANDLE STRIPE RESULT
            ====================================================== */

            // Stripe error
            if (result.error) {
                alert(result.error.message);
                btn.disabled = false;
                loader.style.display = 'none';
                return;
            }

            // Immediate success (no redirect)
            if (
                result.paymentIntent &&
                result.paymentIntent.status === "succeeded"
            ) {
                window.location.href =
                    "{{ route('stripe-checkout.processing') }}?payment_intent=" +
                    result.paymentIntent.id;
                return;
            }

        } catch (err) {
            console.error(err);

            // Stripe validation or incomplete details
            if (
                err.type === "validation_error" ||
                err.code === "incomplete_payment_details"
            ) {
                alert(err.message);
                btn.disabled = false;
                loader.style.display = 'none';
                return;
            }

            // Generic failure
            alert("Payment failed. Please try again.");
            window.location.href = "{{ route('stripe-checkout.failed') }}";

        } finally {
            // Reset UI state
            btn.disabled = false;
            loader.style.display = 'none';
        }
    });

})();

</script>
@endsection
