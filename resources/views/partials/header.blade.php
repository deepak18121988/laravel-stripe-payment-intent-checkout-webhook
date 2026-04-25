<nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
  <div class="container">

    <!-- Brand -->
    <a class="navbar-brand waves-effect" href="{{ route('home') }}">
      <strong class="blue-text">Stripe Demo</strong>
    </a>

    <!-- Collapse -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <!-- Left -->
      <ul class="navbar-nav mr-auto">

        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
          <a class="nav-link waves-effect" href="{{ route('home') }}">
            Home
          </a>
        </li>

        <li class="nav-item {{ request()->routeIs('checkout') ? 'active' : '' }}">
          <a class="nav-link waves-effect" href="{{ route('checkout', 1) }}">
            Checkout Demo
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link waves-effect" href="{{ route('stripe-checkout.processing') }}">
            Payment Flow
          </a>
        </li>
      </ul>

      <!-- Right -->
      <ul class="navbar-nav nav-flex-icons">

        <li class="nav-item">
          <a class="nav-link waves-effect" href="{{ route('checkout', 1) }}">
            <span class="badge green z-depth-1 mr-1"> Live </span>
            <i class="fas fa-credit-card"></i>
            <span class="clearfix d-none d-sm-inline-block"> Payment Demo </span>
          </a>
        </li>

        <li class="nav-item">
          <a href="https://github.com/deepak18121988/laravel-stripe-payment-intent-checkout-webhook"
             class="nav-link waves-effect" target="_blank">
            <i class="fab fa-github"></i>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('checkout', 1) }}" class="nav-link border border-light rounded waves-effect">
            <i class="fas fa-bolt mr-2"></i>Try Demo
          </a>
        </li>

      </ul>

    </div>

  </div>
</nav>