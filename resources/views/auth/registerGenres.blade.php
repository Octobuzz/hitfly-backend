@extends('layouts.app')

@section('content')
    <main class="main main-genre">
        <aside class="main__aside aside">
            <div class="aside__content" data-scroll-speed="10">
                <div class="s-genre-side">
                    <h1 class="s-genre-side__title">{{ __('auth.chooseGenre') }}</h1>
                    <p class="s-genre-side__desc">
                        {{ __('auth.genreText') }}
                    </p>
                    <button type="submit" form="select-genre-form" class="button gradient big s-genre-side__complete">{{ __('auth.ready') }}</button>
                    <a href="{{ route('verification.notice') }}" class="s-genre-side__next">{{ __('auth.skip') }}</a>
                </div>
            </div>
        </aside>
        <div class="main__info">
            <form action="{{ route('register.genres') }}" id="select-genre-form" class="s-genre" method="post">
                @csrf
                @if($genres !== null)
                    @foreach($genres as $genre)
                    <div class="s-genre-item">
                        <input id="id{{$genre->id}}" name="genres[]" value="{{$genre->id}}" class="s-genre-item__input" type="checkbox">
                        <label for="id{{$genre->id}}" class="s-genre-item__label">
                            <img class="s-genre-item__img" src="{{$genre->image}}" alt="{{$genre->name}}">
                            <span class="s-genre-item__text">{{$genre->name}}</span>
                        </label>
                    </div>
                    @endforeach
                @endif

            </form>
        </div>

    </main>

@endsection
