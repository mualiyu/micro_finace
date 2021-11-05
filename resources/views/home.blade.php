@extends('layouts.index')

@section('content')
<div class="container-fluid gtco-banner-area">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">
                    {{ __('Dashboard') }}
                    <span style="float: right; color:white; font-weight:bold;">₦{{Auth::user()->amount ?? '0'}}</span>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5>Transaction History ({{count($transactions)}})</h5>

                    <ul class="list-group" style="background: rgba(247,247,247,0.3)">
                        @foreach ($transactions as $t)   
                        <li class="list-group-item">
                            <h6>@if ($t->from == Auth::user()->account)
                                    Debit!
                                @else
                                    Credit!
                                @endif
                                {{-- {{$t->type}} --}}
                            </h6>
                            <span style="float: right">₦{{$t->amount}}</span>
                            <p style="margin: 0;">status: <span style="color: green;">{{$t->status}}</span></p>
                            {{-- <p style="margin: 0;">Trans ID: <span>shfg34t3fh53hj46</span></p> --}}
                            <p style="margin: 0;">Date: <span>{{$t->created_at}}</span></p>
                            <p style="margin: 0;">From: <span>{{$t->from_account->name}}<small>({{$t->from_account->account}})</small></span>, 
                                To: <span>{{$t->to_account->name}}<small>({{$t->from_account->account}})</small></span></p>
                        </li>
                        @endforeach
                    </ul>
                    {{-- {{ __('You are logged in!') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
