<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-order-form/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-order-form/css/all.min.css')}}"/>
    {{-- <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-order-form/css/style.css')}}"/> --}}
	<link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-order-form/scripts/jquery.numpad.css')}}">
	@yield('css')
    <title>{{ ChuckSite::getSite('name') }} POS</title>
</head>
<body>
@yield('content') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/jquery.min.js')}}"></script>

<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/cptable.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/cputils.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/zip-full.min.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/JSESCPOSBuilder.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/JSPrintManager.js') }}"></script>

<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/jquery.numpad.js')}}" type="text/javascript"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/popper.min.js')}}"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/bootstrap.min.js')}}"></script>
<script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/onScan.js')}}"></script>
{{-- <script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/offline.min.js')}}"></script> --}}
@yield('scripts')
</body>
</html>