@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create a New Person</h1>

    <!-- 当用户输入数据不符合验证规则时，Laravel 会自动将用户重定向回上一页，并附带错误信息。 -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('people.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="middle_names">Middle Names</label>
            <input type="text" name="middle_names" id="middle_names" class="form-control">
        </div>

        <div class="form-group">
            <label for="birth_name">Birth Name</label>
            <input type="text" name="birth_name" id="birth_name" class="form-control">
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-3">Create</button>
    </form>
</div>
@endsection
