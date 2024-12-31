// resources/views/modification.blade.php

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modification Proposals</h1>

    @foreach($modifications as $modification)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Proposal ID: {{ $modification->id }}</h5>
                <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $modification->type)) }}</p>
                <p><strong>Content:</strong> {{ json_encode($modification->content) }}</p>
                <p><strong>Status:</strong>
                    @if($modification->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($modification->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </p>

                @if($modification->status == 'pending')
                    <form action="{{ route('modifications.vote', $modification->id) }}" method="POST">
                        @csrf
                        <button type="submit" name="vote" value="approve" class="btn btn-success">Approve</button>
                        <button type="submit" name="vote" value="reject" class="btn btn-danger">Reject</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection