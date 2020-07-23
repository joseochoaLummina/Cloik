@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Dashboard')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">@include('flash::message')
        <div class="row"> @include('includes.company_dashboard_menu')
            <div class="col-md-9 col-sm-8"> 
                <div class="col-md-8 col-sm-12">
                    @include('includes.company_dashboard_stats')
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="more-visited">
                        <div>{{__('More visited Jobs')}}</div>
                        <div>
                            @foreach($array as $key => $value)
                            <div class="visited-card">
                                <div>
                                    <div class="visited-card-title"><a href="{{route('job.detail', [$value->slug])}}" title="{{$value->title}}">{{$value->title}}</a></div>
                                    <div class="visited-card-duration">{{str_replace('-', '/',substr($value->created_at,0,10))}} - {{str_replace('-', '/',substr($value->expiry_date,0,10))}}</div>
                                </div>
                                <div class="visited-card-value">
                                    <div>{{$value->cantidad}}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        
                <?php
                if((bool)config('company.is_company_package_active')){        
                $packages = App\Package::where('package_for', 'like', 'employer')->get();
                $package = Auth::guard('company')->user()->getPackage();
                if(null !== $package){
                $packages = App\Package::where('package_for', 'like', 'employer')->where('id', '<>', $package->id)->where('package_price', '>=', $package->package_price)->get();
                }
                ?>
                
                <?php if(null !== $package){ ?>
                @include('includes.company_package_msg')
                @include('includes.company_packages_upgrade')
                <?php }elseif(null !== $packages){ ?>
                @include('includes.company_packages_new')
                <?php }} ?>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
@include('includes.immediate_available_btn')
@endpush
