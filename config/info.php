<?php 

return [
  'maanden' => ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
  'prijs' => 333,
  'schema_start' => new DateTime('08:00'),
  'schema_eindig' => new DateTime('22:00'),
  'duur_intake' => new DateTime('01:00'),
  'admin_email' => env('ADMIN_EMAIL', 'welkom@meit.nl'),
];

?>