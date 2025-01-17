@extends('layout.app')

@section('css')
@endsection

@section('body')
    <x-page-heading title="FrontEnd Menu Config" subtitle="Manage frontend menu for user" />
    <section class="section">
        <div class="card col-md-7">
            <div class="card-header"><span class="h4 text-capitalize">{{ $method }} Front End Menu</span></div>
            <div class="card-body d-flex flex-column gap-2">
                @if ($method === 'edit')
                    <form action="{{ route('front-end-menu.update', $fe_menu->id) }}" method="post">
                        @method('PUT')
                    @else
                        <form action="{{ route('front-end-menu.store') }}" method="post">
                @endif
                @csrf
                <div class="col-md-12">
                    @if ($method !== 'edit')
                        @if ($parent)
                            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                            <label for="basicInput" class="mb-2 fw-bold">Parent Menu : {{ $parent->title }}</label>
                        @endif
                    @endif
                    <div class="form-group">
                        <label for="basicInput" class="mb-2">Menu Name</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="basicInput"
                            name="title" placeholder="Enter menu name"
                            @if ($method === 'edit') value="{{ $fe_menu->title }}" @endif />
                        @error('title')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="basicInput" class="mb-2">Position</label>
                        <select class="form-select" multiple aria-label="multiple select example" name="position[]">
                            <option value="header"
                                @if ($method === 'edit') @if (in_array('header', json_decode($fe_menu->position, true)))
                                    selected @endif
                                @endif>Header</option>
                            <option
                                @if ($method === 'edit') @if (in_array('footer', json_decode($fe_menu->position, true)))
                                selected @endif
                                @endif
                                value="footer">Footer</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end gap-3 mt-3">
                        <a href="{{ route('front-end-menu.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Back to Table Menu">Back</a>
                        <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Create Menu">Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
@endsection
