@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $person->first_name }} {{ $person->last_name }}</h1>
    <p><strong>Birth Name:</strong> {{ $person->birth_name ?? 'N/A' }}</p>
    <p><strong>Date of Birth:</strong> {{ $person->date_of_birth ?? 'N/A' }}</p>

    <h3>Parents</h3>
    <ul>
        @forelse($person->parents as $relationship)
            <li>{{ $relationship->parent->first_name }} {{ $relationship->parent->last_name }}</li>
        @empty
            <p>No parents listed.</p>
        @endforelse
    </ul>

    <h3>Children</h3>
    <ul>
        @forelse($person->children as $relationship)
            <li>{{ $relationship->child->first_name }} {{ $relationship->child->last_name }}</li>
        @empty
            <p>No children listed.</p>
        @endforelse
    </ul>
</div>
@endsection
