@extends('emensa.layout')

@section('title')
    Meine Bewertungen
@endsection

@section("cssextra")
    <style>
        .hervorheben{
            background-color: #669955;
        }
    </style>

@endsection
@section('main')
    <grid class="grid-main-element">

        <form action="{{$_SESSION["target"]}}" method="GET">
            <label for="gerichtsfilter">Gerichts Filter:</label>
            <input id="gerichtsfilter" type="text" name="gerichtsfilter"/>
            <input type="submit" value="filter"/>
        </form>
    <table>
        <tr>
            <td>Gericht</td>
            <td>Bewertungszeitpunkt</td>
            <td>Bemerkung</td>
            <td>Sternebewertung</td>
            @if($_SESSION['admin'] == true)
                <td></td>
                <td></td>
            @elseif($bewertung["benutzer_id"] == $_SESSION['benutzer_id'])
                <td></td>
            @endif
        </tr>
    @foreach($bewertungen as $bewertung)
        <tr @if($bewertung["hervorheben"]) class="hervorheben"  @endif>
            <td>{{$bewertung['name']}}</td>
            <td>{{$bewertung['bewertungszeitpunkt']}}</td>
            <td>{{$bewertung['bemerkung']}}</td>
            <td>{{$bewertung['sternebewertung']}}</td>

            @if($_SESSION['admin'] == true)
                <td>
                @if($bewertung["benutzer_id"] == $_SESSION['benutzer_id'])
                    <a href="/bewertungloeschen?berwertungsid={{$bewertung['id']}}">Löschen</a>
                @endif
                </td>

                <td><a href="/bewertungshervorheben?berwertungsid={{$bewertung['id']}}&wert=@if($bewertung["hervorheben"]) 0 @else 1 @endif">@if($bewertung["hervorheben"]) Hervorhebung abwählen @else Hervorheben @endif </a></td>
            @elseif($bewertung["benutzer_id"] == $_SESSION['benutzer_id'])
                <td><a href="/bewertungloeschen?berwertungsid={{$bewertung['id']}}">Löschen</a></td>
            @endif

        </tr>
        @endforeach
    </table>
    </grid>
@endsection