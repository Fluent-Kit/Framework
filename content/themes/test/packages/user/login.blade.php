@extends('layouts.login')

@section('content')
    {{ BootForm::open(array('url' => 'login')) }}
        {{ BootForm::token() }}
        {{ BootForm::email('email', Input::get('email')) }}
        {{ BootForm::password('password', 'Password') }}
        {{ BootForm::submit('Login') }}
    {{ BootForm::close() }}
@stop