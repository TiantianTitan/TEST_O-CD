@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $person->first_name }} {{ $person->last_name }}</h1>
    <p><strong>Birth Name:</strong> {{ $person->birth_name ?? 'N/A' }}</p>
    <p><strong>Date of Birth:</strong> {{ $person->date_of_birth ?? 'N/A' }}</p>

    <form action="{{ route('people.update', $person->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h3>Edit Details</h3>
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $person->first_name }}" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $person->last_name }}" required>
        </div>

        <div class="form-group">
            <label for="birth_name">Birth Name</label>
            <input type="text" name="birth_name" id="birth_name" class="form-control" value="{{ $person->birth_name }}">
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $person->date_of_birth }}">
        </div>

        <h3>Parents</h3>
        <div id="parents-list">
            @foreach($person->parents as $parent)
                <div class="form-group parent-item">
                    <input type="text" name="parents[]" class="form-control" value="{{ $parent->parent->first_name ?? '' }} {{ $parent->parent->last_name ?? '' }}">
                    <button type="button" class="btn btn-danger remove-parent mt-2">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-parent" class="btn btn-secondary mt-2">Add Parent</button>

        <h3>Children</h3>
        <div id="children-list">
            @foreach($person->children as $child)
                <div class="form-group child-item">
                    <input type="text" name="children[]" class="form-control" value="{{ $child->child->first_name ?? '' }} {{ $child->child->last_name ?? '' }}">
                    <button type="button" class="btn btn-danger remove-child mt-2">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-child" class="btn btn-secondary mt-2">Add Child</button>

        <button type="submit" class="btn btn-primary mt-3">Submit Changes</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add parent input
        document.getElementById('add-parent').addEventListener('click', function () {
            const parentList = document.getElementById('parents-list');
            const div = document.createElement('div');
            div.className = 'form-group parent-item mt-2';
            div.innerHTML = `
                <input type="text" name="parents[]" class="form-control" placeholder="Enter Parent Name" required>
                <button type="button" class="btn btn-danger remove-parent mt-2">Remove</button>
            `;
            parentList.appendChild(div);
        });

        // Add child input
        document.getElementById('add-child').addEventListener('click', function () {
            const childList = document.getElementById('children-list');
            const div = document.createElement('div');
            div.className = 'form-group child-item mt-2';
            div.innerHTML = `
                <input type="text" name="children[]" class="form-control" placeholder="Enter Child Name" required>
                <button type="button" class="btn btn-danger remove-child mt-2">Remove</button>
            `;
            childList.appendChild(div);
        });

        // Remove parent input
        document.getElementById('parents-list').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-parent')) {
                e.target.parentElement.remove();
            }
        });

        // Remove child input
        document.getElementById('children-list').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-child')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
