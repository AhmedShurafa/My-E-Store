@extends('layouts.admin')

@section('title')
<div class="d-flex justify-content-between">
    <h2>Roles</h2>
    <div class="">
        @can('roles.create')
        <a class="btn btn-sm btn-outline-primary" href="{{ route('roles.create') }}">Create</a>
        @endcan
    </div>
</div>
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Roles</li>
</ol>
@endsection

@section('content')

    <x-alert />

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Roles</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td><img src="{{ $role->image_url }}" width="60" alt=""></td>
                <td>{{ $role->name }}</td>

                <td width="50%">
                    @foreach($role->abilities as $text)
                        <span class="badge badge-secondary m-1" style="font-size: 100%"> {{$text}} </span>
                    @endforeach
                </td>

                <td>{{ $role->created_at }}</td>
                <td>
                    @can('roles.update')
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-dark">{{ __('app.edit') }}</a>
                    @endcan

                    @if (Auth::user()->can('roles.delete'))
                    <form action="{{ route('roles.destroy', $role->id) }}" class="d-inline-block" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


    {{ $roles->links() }}


@endsection

