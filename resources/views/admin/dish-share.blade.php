@extends('admin.layout')
<style>

.share-btn{
    margin-left: 20px !important;
}

</style>
@section('content')
<div class="container-fluid my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h1 class="h4 mb-1">Dish Sharing</h1>
                    <p class="text-muted small mb-0">
                        Share all your dishes with another registered admin. They’ll get
                        <strong>read-only</strong> access once they accept your request.
                    </p>
                </div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success mb-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            Please fix the errors below and try again.
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('admin.init-share') }}"
                          class="search-dishes-div">
                        @csrf

                        <div class="mb-3">
                            <label for="share-email" class="form-label">
                                Admin email to share with
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input
                                    id="share-email"
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="admin@example.com"
                                    value="{{ old('email', request('email')) }}"
                                    required
                                >
                                <button type="submit" class="btn btn-primary share-btn">
                                    Send share request
                                </button>

                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-text">
                                You can’t start a new share if you’re already receiving dishes
                                from another admin.
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
