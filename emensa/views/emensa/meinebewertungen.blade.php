@extends('emensa.layout')

@section('title')
    Meine Bewertungen
@endsection

@section('main')
    <grid class="grid-main-element">
    <table>
        <tr>
            <td>Gericht</td>
            <td>Bewertungszeitpunkt</td>
            <td>Bemerkung</td>
            <td>Sternebewertung</td>
            <td></td>
        </tr>
    @foreach($bewertungen as $bewertung)
        <tr>
            <td>{{$bewertung['name']}}</td>
            <td>{{$bewertung['bewertungszeitpunkt']}}</td>
            <td>{{$bewertung['bemerkung']}}</td>
            <td>{{$bewertung['sternebewertung']}}</td>
            @if($bewertung["benutzer_id"] == $_SESSION['benutzer_id'])
                 <td><a href="/bewertungloeschen?gerichtid={{$bewertung['id']}}">Löschen</a></td>
            @endif
        </tr>
        @endforeach
    </table>
    </grid>
@endsection