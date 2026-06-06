@extends('layouts.app')

@section('content')
<div class="stack">
    <div class="flex" style="justify-content: space-between; align-items: flex-start;">
        <div>
            <h1>Purchase Manager</h1>
        </div>
        <div class="flex">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="button button-secondary">Logout</button>
            </form>
        </div>
    </div>

    @livewire('purchase-manager')
</div>
@endsection
