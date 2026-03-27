<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welkom bij MEIT.</title>
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
            font-weight: 700;
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
        .info strong {
            color: #ffffff;
        }
        .dates { 
            background: #393f35; 
            padding: 20px; 
            border-radius: 12px; 
            margin: 20px 0; 
        }
        .date-item { 
            padding: 12px 0; 
            color: #ffffff;
            background: #393f35;
        }
        .date-item strong {
            color: #ffffff;
        }
        a { 
            color: #eb7b26; 
            text-decoration: underline;
        }
        a:hover {
            color: #dd6b16;
        }
        .signature {
            margin-top: 30px;
        }
        .signature em {
            color: #eb7b26;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Je hebt geboekt!</h1>
        
        <p>Hoi {{ $deelnemer->voornaam }},</p>
        
        <p>Wat moedig dat je jouw ceremonie hebt geboekt! Ik kan niet wachten om deze bijzonder dag met jou te ervaren. ♡</p>

        <p>Hierbij bevestig ik jouw boeking.</p>
        
        <div class="info">
            <h3>Jouw gegevens</h3>
            <p><strong>Naam:</strong> {{ $deelnemer->voornaam }} {{ $deelnemer->tussenvoegsel }} {{ $deelnemer->achternaam }}</p>
            <p><strong>E-mail:</strong> {{ $deelnemer->email }}</p>
        </div>
        
        <div class="dates">
            <h3>De datum van jouw ceremonie</h3>
            @php
                $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
                $datetime = new DateTime($ceremonie->datum);
                $maand = (int)$datetime->format('m') - 1;
            @endphp
            <div class="date-item">
                <strong>{{ $datetime->format('j') }} {{ $maanden[$maand] }} {{ $datetime->format('Y') }}</strong><br>
                11:00 - 16:00 uur
            </div>
        </div>
        
        <div class="info">
            <h3>Locatie</h3>
            <p>Schiedam (Het Magische Huisje)</p>
        </div>
        
        <p>Op korte termijn ontvang je van mij verdere informatie over de dag van de ceremonie. </p>
        
        <p>Heb je in de tussentijd vragen? Stuur gerust een mailtje naar <a href="mailto:welkom@meit.nl">welkom@meit.nl</a>.</p>
        
        <p>Ik kijk ernaar uit je te ontmoeten!</p>
        
        <div class="signature">
            <p>Alle Liefde,<br>
            Jacelyn<br>
            <em>MEIT.</em></p>
        </div>
    </div>
</body>
</html>
