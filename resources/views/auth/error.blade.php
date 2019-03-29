@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{__('auth.registerFailed')}}</div>

                <div class="card-body">
                    {{session('message-reg')}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
