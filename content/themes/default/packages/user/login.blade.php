@extends('layouts.login')

@section('content')
    {{ BootForm::open()->action('/login') }}
        {{ BootForm::token() }}
        {{ BootForm::email('Email', 'email') }}
        {{ BootForm::password('Password', 'password') }}
        {{ BootForm::submit('Login') }}
    {{ BootForm::close() }}
@stop