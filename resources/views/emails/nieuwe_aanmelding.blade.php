<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nieuwe Aanmelding</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Quattrocento:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: Verdana, Georgia, serif; 
            line-height: 1.6; 
            color: #ffffff; 
            background-color: #474f42;
            margin: 0;
            padding: 0;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            padding: 30px 20px; 
        }
        h1 { 
            font-family: Verdana, Georgia, serif;
            color: #ffffff; 
            font-size: 32px;
            margin: 0 0 20px;
        }
        h3 { 
            font-family: Verdana, Georgia, serif;
            color: #eb7b26; 
            font-size: 22px;
            margin: 0 0 10px;
        }
        .info { 
            background: #393f35; 
            padding: 20px; 
            border-radius: 12px; 
            margin: 20px 0; 
        }
        .info p { 
            margin: 8px 0; 
            color: #ffffff;
        }
        .dates { 
            background: #393f35; 
            /* 6D8462 */
            padding: 20px; 
            border-radius: 12px; 
            margin: 20px 0; 
        }
        .date-item { 
            padding: 12px 0; 
            border-bottom: 1px solid #474f42; 
            color: #ffffff;
            background: #393f35;
        }
        .last-item { 
            border-bottom: none; 
        }
        .date-item strong {
            color: #ffffff;
        }
        .label { 
            font-weight: bold; 
            color: #ffffff; 
        }
        a { 
            color: #eb7b26; 
            text-decoration: underline;
        }
        a:hover {
            color: #dd6b16;
        }
        .button {
            display: inline-block;
            background-color: #eb7b26;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-family: Verdana, Georgia, serif;
            text-transform: uppercase;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #dd6b16;
        }
        .status-paid { color: #90a87a; font-weight: bold; }
        .status-partial { color: #eb7b26; font-weight: bold; }
        .status-unpaid { color: #d53039; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nieuwe Aanmelding!</h1>
        
        <p>Er is zojuist een nieuwe aanmelding binnengekomen voor het MEIT. Traject.</p>
        
        <div class="info">
            <h3>Deelnemer gegevens</h3>
            <p><span class="label">Naam:</span> {{ $deelnemer->voornaam }} {{ $deelnemer->tussenvoegsel }} {{ $deelnemer->achternaam }}</p>
            <p><span class="label">E-mail:</span> {{ $deelnemer->email }}</p>
            @if($deelnemer->telefoon_nummer)
                <p><span class="label">Telefoon:</span> {{ $deelnemer->telefoon_nummer }}</p>
            @endif
            @if($deelnemer->geboorte_datum)
                <p><span class="label">Geboortedatum:</span> {{ \Carbon\Carbon::parse($deelnemer->geboorte_datum)->format('d-m-Y') }}</p>
            @endif
            @if($deelnemer->geboorte_tijd)
                <p><span class="label">Geboortetijd:</span> {{ $deelnemer->geboorte_tijd }}</p>
            @endif
            @if($deelnemer->geboorte_plaats)
                <p><span class="label">Geboorteplaats:</span> {{ $deelnemer->geboorte_plaats }}</p>
            @endif
        </div>
        
        <div class="dates">
            <h3>Traject info</h3>
            @php
                $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
                $momenten = array_filter([
                    $training->start_moment ?? null,
                    $training->start_moment_2 ?? null,
                    $training->start_moment_3 ?? null,
                    $training->start_moment_4 ?? null,
                ]);
                $laatsteIndex = count($momenten) - 1;
            @endphp
            @foreach($momenten as $index => $moment)
                @if($moment)
                    @php
                        $datetime = new DateTime($moment);
                        $maand = (int)$datetime->format('m') - 1;
                        $startTijd = $datetime->format('H:i');
                        $eindTijd = date('H:i', strtotime($startTijd) + 3 * 60 * 60);
                        $isLaatste = ($index === $laatsteIndex);
                    @endphp
                    <div class="date-item {{ $isLaatste ? 'last-item' : '' }}">
                        <strong>{{ $datetime->format('j') }} {{ $maanden[$maand] }} {{ $datetime->format('Y') }}</strong><br>
                        {{ $startTijd }} - {{ $eindTijd }} uur
                    </div>
                @endif
            @endforeach
        </div>
        
        <div class="info">
            <h3>Betaalstatus</h3>
            @if($aanmelding->betaal_status == 2)
                <p class="status-paid">Volledig betaald</p>
            @elseif($aanmelding->betaal_status == 1)
                <p class="status-partial">1e termijn betaald</p>
            @else
                <p class="status-unpaid">Nog niet betaald (wachtlijst)</p>
            @endif
        </div>
        
        <a href="{{ url('/deelnemers/' . $deelnemer->id) }}" class="button">Bekijk in dashboard</a>
        
        <p style="margin-top: 30px;">Met vriendelijke groet,<br><em>MEIT. Systeem</em></p>
    </div>
</body>
</html>
