<?php

namespace Leertaak5\Services;

class EventsRenderer {
    private static $IMAGES = [ 'freeze', 'rain', 'snow', 'hail', 'thunder', 'cyclone' ];
    public function render($events)
    {
        return collect(array_combine(self::$IMAGES, str_split($events)))
            ->filter(function ($event) { return $event; })
            ->map(function ($event, $image) {
                return '<img src="' . asset('img/' . $image . '.png') . '"
                             class="weather-event weather-event--' . $image . '"
                             alt="' . title_case($image) . '"
                             title="' . title_case($image) . '" />';
            })
            ->implode(' ');
    }
}
