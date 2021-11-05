<!doctype html>
<!--
	Solution by GetTemplates.co
	URL: https://gettemplates.co
-->
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- awesone fonts css-->
    <link href="{{asset('main/css/font-awesome.css')}}" rel="stylesheet" type="text/css">
    <!-- owl carousel css-->
    <link rel="stylesheet" href="{{asset('mail/owl-carousel/assets/owl.carousel.min.css')}}" type="text/css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('main/css/bootstrap.min.css')}}">
    <!-- custom CSS -->
    <link rel="stylesheet" href="{{asset('main/css/style.css')}}">
    <title>MircoFinance Bank</title>
    <style>

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light bg-transparent" id="gtco-main-nav">
    <div class="container"><a class="navbar-brand" href="{{url('/')}}">MicroFinance</a>
        <button class="navbar-toggler" data-target="#my-nav" onclick="myFunction(this)" data-toggle="collapse"><span
                class="bar1"></span> <span class="bar2"></span> <span class="bar3"></span></button>
        <div id="my-nav" class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('about')}}">About</a></li>
		@if (Auth::user())
		<li class="nav-item"><a class="nav-link" href="{{route('show_transfer')}}">Transfer</a></li>
                <li class="nav-item">
			{{-- <a class="nav-link" href="">Logout</a> --}}
			<a class="nav-link" href="{{ route('logout') }}"
        	       onclick="event.preventDefault();
        	                     document.getElementById('logout-form').submit();">
        	        {{ __('Logout') }}
        	    </a>
        	    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        	        @csrf
        	    </form>
		</li>
		@endif
            </ul>
	    @guest
            <form class="form-inline my-2 my-lg-0">
                <a href="{{route('login')}}" class="btn btn-outline-dark my-2 my-sm-0 mr-3 text-uppercase">login</a> 
		<a href="{{route('register')}}" class="btn btn-info my-2 my-sm-0 text-uppercase">sign up</a>
            </form>
	    @else
	    	<a id="navbarDropdown" class="nav-link dropdown-toggle btn btn-outline-dark my-2 my-sm-0 mr-3 text-uppercase" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button"  aria-haspopup="true"  v-pre>
        	    {{ Auth::user()->name }}
        	</a>
        	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        	    <a class="dropdown-item" href="{{ route('logout') }}"
        	       onclick="event.preventDefault();
        	                     document.getElementById('logout-form').submit();">
        	        {{ __('Logout') }}
        	    </a>
        	    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        	        @csrf
        	    </form>
        	</div>
	    @endguest
        </div>
    </div>
</nav>

@yield('content')
<br>
@if (Auth::user())
    
@endif


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{asset('main/js/jquery-3.3.1.slim.min.js')}}"></script>
<script src="{{asset('main/js/popper.min.js')}}"></script>
<script src="{{asset('main/js/bootstrap.min.js')}}"></script>
<!-- owl carousel js-->
<script src="{{asset('main/owl-carousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('main/js/main.js')}}"></script>

@yield('script')
</body>
</html>
