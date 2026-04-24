<!--Footer-->
<footer class="page-footer text-center font-small mt-4 wow fadeIn">

  <!--Call to action-->
  <div class="pt-4">
    <a class="btn btn-outline-white" href="/checkout" role="button">
      Try Payment Demo
      <i class="fas fa-credit-card ml-2"></i>
    </a>

    <a class="btn btn-outline-white" href="https://github.com/deepak18121988?tab=repositories" target="_blank" role="button">
      View Source Code
      <i class="fab fa-github ml-2"></i>
    </a>
  </div>
  <!--/.Call to action-->

  <hr class="my-4">

  <!-- Social / Links -->
  <div class="pb-4">

    <a href="https://github.com/deepak18121988?tab=repositories" target="_blank">
      <i class="fab fa-github mr-3"></i>
    </a>

    <a href="#" target="_blank">
      <i class="fas fa-globe mr-3"></i>
    </a>

    <a href="#" target="_blank">
      <i class="fas fa-envelope mr-3"></i>
    </a>

  </div>
  <!-- Social icons -->

  <!-- Description -->
  <div class="pb-3 px-3 text-light">
    <small>
      Laravel + Stripe PaymentIntent integration demo with custom checkout,
      validation, and webhook handling.
    </small>
  </div>

  <!--Copyright-->
  <div class="footer-copyright py-3">
    © {{ date('Y') }} 
    <strong>Deepak Lohani</strong> | Stripe Payment Integration Demo
  </div>
  <!--/.Copyright-->

</footer>
<!--/.Footer-->

<!-- SCRIPTS -->
<script type="text/javascript" src="{{ asset('/js/jquery-3.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/mdb.min.js') }}"></script>

<script>
  new WOW().init();
</script>