@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header h5">{{ __('Edit the client') }}</div>
                
                <div class="card-body">
                    <form action="{{ route('passport.clients.update', ['client_id'=>$client->id]) }}" method="POST" class="form-group">
                        <label for="name" class='mt-4 mb-1 h6'> Client's name:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name ..." value="{{old('name') ?? $client->name}}">
                        @error('name')
                            <div class="text-danger"> {{$message}} </div>
                        @enderror

                        <label for="redirect" class='mt-4 mb-1 h6'> Redirect URL </label>
                        <input type="text" class="form-control" name="redirect" id="redirect" placeholder="Validated Url ..." value="{{old('redirect') ?? $client->redirect}}">
                        @error('redirect')
                            <div class="text-danger"> {{$message}} </div>
                        @enderror

                        <input type="submit" value="Edit client" class="btn btn-primary mt-4">

                        @csrf
                        @method('put')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection