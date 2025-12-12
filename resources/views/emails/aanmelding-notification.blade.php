<h2>Nieuwe aanmelding ({{ $type }})</h2>

<p><strong>Traject start:</strong> {{ $training->start_moment ?? ('Traject #'.$training->id) }}</p>
<p><strong>Aanmelding ID:</strong> {{ $aanmelding->id }}</p>

<hr>

<p><strong>Deelnemer:</strong> {{ ($deelnemer->voornaam ?? '') }} {{ ($deelnemer->tussenvoegsel ?? '') }} {{ ($deelnemer->achternaam ?? '') }}</p>
<p><strong>Email:</strong> {{ $deelnemer->email ?? '-' }}</p>
<p><strong>Telefoon:</strong> {{ $deelnemer->telefoon_nummer ?? '-' }}</p>

<hr>

<p><strong>Status:</strong> {{ $type === 'wachtlijst' ? 'Wachtlijst' : 'Betaald / bevestigd' }}</p>
<p><strong>Betaalstatus code:</strong> {{ $aanmelding->betaal_status }}</p>
