@extends('emensa.layout')

@section('title')
    Anmeldung
@endsection

@section('main')
    <grid class="grid-main-element">
        <form method="post" action="/verifizierung">
            <label>E-Mail</label><br>
            <input type="email" name="email"><br>
            <label>Passwort</label><br>
            <input type="password" name="password"><br>
            <input type="submit" value="Anmeldung" name="submit">
        </form>
        <strong style="color: red">{{$msg}}</strong>

    </grid>

@endsection