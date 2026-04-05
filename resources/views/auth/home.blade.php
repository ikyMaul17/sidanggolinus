@extends('layouts_auth.app')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dasbor</div>
            <div class="card-body">
                <div class="alert alert-success">
                    @if ($message = Session::get('success'))
                        {{ $message }}
                    @else
                        Anda telah masuk!
                    @endif
                </div>              
            </div>
        </div>
    </div>    
</div>
    
@endsection