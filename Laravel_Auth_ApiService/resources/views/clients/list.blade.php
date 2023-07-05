@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header h5">{{ __('All clients') }}</div>

                @if (session('message'))
                    <div class="alert alert-success mt-3 text-center"> {{session('message')}} </div>
                @endif

                @if (session('delete-message'))
                    <div class="alert alert-warning mt-3 text-center"> {{session('delete-message')}} </div>
                @endif

                <div> 
                    <a href="{{route('client.create')}}" class="btn btn-primary mt-3 mx-3"> {{ __('Create new client') }} </a>
                </div>
                <hr>
                <div class="card-body">
                    <table class="table table-bordered table-hover mt-3">
                        <thead class="text-center">
                            <tr>
                                <th width="10%"> ID </th>
                                <th> Name </th>
                                <th> Secret </th>
                                <th> Redirect URL </th>
                                <th colspan="2" width="20%"> Actions </th>             
                            </tr>
                        </thead>
                        <tbody>
                            @if ($clients->count() > 0)
                                @foreach ($clients as $client )
                                <tr>
                                    <td width="10%" class="pt-3 text-center"> {{ $client->id }} </td>
                                    <td class="pt-3 "> {{ $client->name }} </td>
                                    <td class="pt-3"> {{ $client->secret }} </td>
                                    <td class="pt-3"> {{ $client->redirect }} </td>

                                    <td width="10%" class="text-center">
                                        <a href="{{ route('client.edit', ['id'=>$client->id]) }}" class="btn btn-primary">Edit</a>                                        
                                    </td>

                                    <td width="10%" class="text-center"> 
                                        <form action="{{ route('passport.clients.destroy', ['client_id'=>$client->id]) }}" method="post"
                                            onsubmit="return confirm('Are you sure to delete this client ?')">
                                        
                                            <input type="submit" value="Delete" class="btn btn-danger">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </td>             
                                </tr>
                                @endforeach
                            @endif
                            
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
