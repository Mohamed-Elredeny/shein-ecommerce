<?php $active_theme = 1; ?>
@extends('layouts.themes.theme' . $active_theme)
@section('content')


    @include('includes.themes.theme' . $active_theme . '.sections.slider')

    @include('includes.themes.theme' . $active_theme . '.sections.services')

    @include('includes.themes.theme' . $active_theme . '.sections.featuredProducts')

    @include('includes.themes.theme' . $active_theme . '.sections.offersArea')

    @include('includes.themes.theme' . $active_theme . '.sections.products')

    @include('includes.themes.theme' . $active_theme . '.sections.advantageArea')

@endsection
