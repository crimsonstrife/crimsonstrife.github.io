<?php
use traq\helpers\Atom;

// Entries
$entries = array();
foreach ($tickets as $ticket) {
    $entries[] = array(
        'title' => $ticket->summary,
        'id' => "tickets:{$ticket->ticket_id}",
        'updated' => Time::date("c", $ticket->created_at),
        'link' => "https://" . $_SERVER['HTTP_HOST'] . Request::base($ticket->href()),
        'author' => array(
            'name' => $ticket->user->name
        ),
        'content' => array(
            'type' => "XHTML",
            'data' => $ticket->body
        ),
    );
}

// Make feed
$feed = new Atom(array(
    'title' => l('x_ticket_feed', $project->name),
    'link' => "https://" . $_SERVER['HTTP_HOST'] . Request::base(),
    'feed_link' => "https://" . $_SERVER['HTTP_HOST'] . Request::requestUri(),
    'updated' => $entries[0]['updated'],
    'entries' => $entries,
));

// Output feed
header("Content-type: text/plain");
print($feed->build());
