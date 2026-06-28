@extends('frontend.layouts.master')

@section('title', '404 - Page Not Found')

@section('content')
<div class="error-area pt-120 pb-120">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-xl-8 col-lg-10">
            <div class="error-content text-center">
               <div class="error-img mb-50">
                  <img src="{{ asset('frontend/assets/img/login/error.png') }}" alt="">
               </div>
               <div class="error-text">
                  <h4 class="error-title-sm">Oops! Page not found</h4>
                  <p>Whoops, this is embarrassing. <br> Looks like the page you were looking for wasn't found.</p>
                  <a class="tp-btn-inner mt-30" href="{{ route('frontend.home') }}">Back to Home</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
