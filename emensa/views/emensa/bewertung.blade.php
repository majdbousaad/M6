@extends('emensa.layout')

@section('title')
    Bewertung
@endsection

@section('head')
    <link href="css/bewertung.css" rel="stylesheet" />
@endsection

@section('main')
    <div class="grid-bewertung-element">
        <h1>Bewertung zu {{$name}}</h1>
        <img id="bild_bewertung" src="/img/gerichte/{{$bildname}}"
             alt="Gericht" width="100" height="100">
        <form method="post" action="/bewertung_verarbeitung?gerichtid={{$gerichtid}}">
            <label>Sternebewertung</label>
            <select name="sternebewertung" id="sternebewertung">
                <option value="sehr gut">Sehr gut</option>
                <option value="gut">gut</option>
                <option value="schlecht">Schlecht</option>
                <option value="sehr schlecht">Sehr schlecht</option>
            </select><br>
            <label>Bemerkung</label>
            <textarea id="bemerkung" name="bemerkung" rows="4" cols="50"></textarea><br>
            <input type="submit" value="Abschicken" name="submit">
        </form>
        <strong style="color: red">{{$_SESSION['error_message']}}</strong>

        <p><h2>Die Letzten 30 Bewertungen:</h2>
        <table>
            <tr class="heading">
                <td>Gericht</td>
                <td>Erfasst am</td>
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
                    <td>@if($bewertung['benutzer_id'] == $_SESSION['cookie'])
                            <a href="/bewertungloeschen?bewertungsid={{$bewertung['id']}}">Löschen</a>@endif</td>
                </tr>
            @endforeach
        </table>
        </p>
    </div>

@endsection