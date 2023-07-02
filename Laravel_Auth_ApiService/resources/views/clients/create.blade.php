@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header h5">{{ __('Create new client') }}</div>
                
                <div class="card-body">
                    <form action="{{route('passport.clients.store')}}" method="POST" class="form-group">
                        <label for="name" class='mt-4 mb-1 h6'> Client's name:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name ..." value="{{old('name')}}">
                        @error('name')
                            <div class="text-danger"> {{$message}} </div>
                        @enderror

                        <label for="redirect" class='mt-4 mb-1 h6'> Redirect URL </label>
                        <input type="text" class="form-control" name="redirect" id="redirect" placeholder="Validated Url ..." value="{{old('redirect')}}">
                        @error('redirect')
                            <div class="text-danger"> {{$message}} </div>
                        @enderror

                        <input type="submit" value="Create client" class="btn btn-primary mt-4">

                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
