@extends('layouts.app')

@section('content')
    <main class="main main-genre">
        <aside class="main__aside aside">
            <div class="aside__content">
                <div class="s-genre-side">
                    <h1 class="s-genre-side__title">{{ __('auth.chooseGenre') }}</h1>
                    <p class="s-genre-side__desc">
                        {{ __('auth.genreText') }}
                    </p>
                    <button type="submit" form="select-genre-form" class="button gradient big s-genre-side__complete">{{ __('auth.ready') }}</button>
                    <a href="{{ route('register.skip.genres') }}" class="s-genre-side__next">{{ __('auth.skip') }}</a>
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
                        @if($genre->children()->count() > 0)
                            <a href=".genre-selection" data-id="{{$genre->id}}" class="genre-selection-form s-genre-item__subgenres-link">Выбрать поджанры</a>
                        @endif
                    </div>
                    @endforeach
                @endif
            </form>
        </div>

    </main>

    <div class="genre-selection loading mfp-hide white-popup-block">
      <div class="loader">
        Жанры загружаются...
      </div>
      <div class="genre-selection__body">
        <h2 class="genresForm__header"></h2>
        <hr>
        <ul class="genresForm__list">
        </ul>
        <div class="genresForm__footer">
          <a href="javascript: void(0)" class="button gradient genres-deselect">Очистить</a>
          <a href="javascript: void(0)" class="button gradient invert genres-select">Сохранить</a>
        </div>
      </div>
    </div>

@endsection
