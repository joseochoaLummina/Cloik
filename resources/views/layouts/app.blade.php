<?php
if (!isset($seo)) {
    $seo = (object) array('seo_title' => $siteSetting->site_name, 'seo_description' => $siteSetting->site_name, 'seo_keywords' => $siteSetting->site_name, 'seo_other' => '');
}
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns="http://www.w3.org/1999/xhtml" lang="{{ app()->getLocale() }}" class="{{ (session('localeDir', 'ltr'))}}" dir="{{ (session('localeDir', 'ltr'))}}" prefix="http://ogp.me/ns#" itemscope itemtype="http://schema.org/WebPage">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Security-Policy" content="connect-src 'self' file: data: blob: https://*  filesystem: * http://clocaapi.herokuapp.com/* ;">
        <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-eval' data: 'unsafe-inline' blob: *;">
        <meta http-equiv="Content-Security-Policy" content="style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/css;">
        <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' 'unsafe-eval' https://sdk.amazonaws.com/js/* http://www.google.com https://cloik-262019.appspot.com/api/v1/test-idiomas https://www.gstatic.com/ https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js;">
        <meta http-equiv="Content-Security-Policy" content="img-src 'self' data: https://*; object-src *;">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php 
            if(!isset($_SESSION)) 
            { 
                session_start(); 
            }
            if(isset($_SESSION["metaOG"])) {
                echo $_SESSION["metaOG"];
            }
        ?>
        {!! $seo->seo_other !!}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{__($seo->seo_title) }}</title>
        <meta name="Keywords" content="{!! $seo->seo_keywords !!}">
        <meta name="Description" content="{!! $seo->seo_description !!}">
        <!-- Fav Icon -->
        <link rel="shortcut icon" href="{{asset('/')}}favicon.ico">
        <!-- Slider -->
        <link href="{{asset('/')}}js/revolution-slider/css/settings.css" rel="stylesheet">
        <!-- Owl carousel -->
        <link href="{{asset('/')}}css/owl.carousel.css" rel="stylesheet">
        <!-- Bootstrap -->
        {{-- <link href="{{asset('/')}}css/bootstrap.min.css" rel="stylesheet"> --}}
        <link href="{{asset('/')}}css/bootstrap.minold.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{asset('/')}}css/font-awesome.css" rel="stylesheet">
        <!-- ChartJS Style -->
        <link href="{{asset('/')}}css/Chart.min.css" rel="stylesheet">
        <!-- Custom Style -->
        <link href="{{asset('/')}}css/main.css" rel="stylesheet">
        @if((session('localeDir', 'ltr') == 'rtl'))
        <!-- Rtl Style -->
        <link href="{{asset('/')}}css/rtl-style.css" rel="stylesheet">
        @endif
        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/') }}admin_assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/') }}admin_assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="{{asset('/')}}js/html5shiv.min.js"></script>
          <script src="{{asset('/')}}js/respond.min.js"></script>
        <![endif]-->
        @stack('styles')
    </head>
    <body>
        @yield('content') 
        <!-- Bootstrap's JavaScript --> 
        {{-- <script src="{{asset('/')}}js/jquery-2.1.4.min.js"></script> --}}
        <script src="{{asset('/')}}js/jquery-3.4.1.min.js"></script>
       <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="{{asset('/')}}js/bootstrap.min.js"></script> 
        
        <!-- Owl carousel --> 
        <script src="{{asset('/')}}js/owl.carousel.js"></script> 
        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script> 
        <script src="{{ asset('/') }}admin_assets/global/plugins/Bootstrap-3-Typeahead/bootstrap3-typeahead.min.js" type="text/javascript"></script> 
        <!-- END PAGE LEVEL PLUGINS --> 
        <script src="{{ asset('/') }}admin_assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery.scrollTo.min.js" type="text/javascript"></script>
        <!-- Revolution Slider --> 
        {{-- <script type="text/javascript" src="{{ asset('/') }}js/revolution-slider/js/jquery.themepunch.tools.min.js"></script> --}}
        <script type="text/javascript" src="{{ asset('/') }}js/revolution-slider/js/jquery.themepunch.plugins.min.js"></script>
        <script type="text/javascript" src="{{ asset('/') }}js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>
        <!-- ChartJS -->        
        <script type="text/javascript" src="{{ asset('/') }}js/Chart.bundle.min.js"></script>
        <script type="text/javascript" src="{{ asset('/') }}js/external_api.js"></script>
        <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({selector:'textarea'});</script> -->
        {!! NoCaptcha::renderJs() !!}
        @stack('scripts')
        <!-- Custom js --> 
        <script src="{{asset('/')}}js/script.js"></script>
        <script type="text/JavaScript">
            $(document).ready(function(){
            $(document).scrollTo('.has-error', 2000);
            });
            function showProcessingForm(btn_id){		
            $("#"+btn_id).val( 'Processing .....' );
            $("#"+btn_id).attr('disabled','disabled');		
            }
        </script>
        
        <link href="{{asset('/')}}css/video-media.css" rel="stylesheet">
    </body>
</html>
