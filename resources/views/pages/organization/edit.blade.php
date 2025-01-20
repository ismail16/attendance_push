@extends('layouts.app')

@section('title', 'Settings')
@section('content')
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <h5 class="mb-0">Set Device IP</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('organization.update') }}" method="POST">
      @csrf

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label" for="name">Organization Name</label>
          <div class="input-group input-group-merge">
            <span id="company" class="input-group-text"><i class="bx bx-buildings"></i></span>
            <input type="text" id="name" name="name" value="{{ $org->name }}" class="form-control" aria-describedby="company">
          </div>
          @error('name')
          <span class="text-danger">{{ $message }}</span>
          @enderror

        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label" for="url">URL</label>
          <div class="input-group input-group-merge">
            <span id="" class="input-group-text"><i class="bx bx-link"></i></span>
            <input type="text" id="url" name="url" value="{{ $org->url }}" class="form-control" aria-describedby="">
          </div>
          @error('name')
          <span class="text-danger">{{ $message }}</span>
          @enderror

        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label" for="api_key">Api Key</label>
          <div class="input-group input-group-merge">
            <span id="api-key-icon" class="input-group-text"><i class='bx bxs-key'></i></span>
            <input type="text" id="api_key" value="{{ $org->api_key }}" name="api_key" class="form-control" aria-describedby="api-key-icon">
          </div>

          @error('api_key')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>

      <button class="btn btn-primary">Update</button>

    </form>
  </div>
</div>
@endsection