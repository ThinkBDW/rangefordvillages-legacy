<?php
/**
 * This class is designed to display an SVG based map showing icons for the various villages at Rangeford.
 * The map markers are setup in "Villages Settings" where more markers can be added. Marker colours will
 * automatically change based on the status of the village, i.e. currently active / coming soon.
 */
class rangefordVillageMap
{
    public function __construct()
    {
        add_shortcode('rangeford_village_map', [$this, 'renderMap']);
    }

    /**
     * Grabs the initials from the name of the village, for use on the marker
     */
    public function getInitials($name)
    {
        $explode = explode(' ', $name);

        $initials = array_map(function($item){
            return strtoupper(substr($item, 0, 1));
        }, $explode);
        
        $initials = [$initials[0], $initials[1]];
        
        if (count($initials) === 4) {
            array_splice($initials, 2, 0, '<br>');
        }

        return implode($initials);
    }

    /**
     * Creates a well formatted array of markers with associated village info for render
     */
    public function getMarkers()
    {
        $markers = get_field('village_markers', 'option');

        $return = array_map(function($item){
            list($x, $y) = explode(',', $item['marker_position']);

            return [
                'marker_x' => $x,
                'marker_y' => $y,
                'village' => [
                    'ID' => $village_id = $item['village']->ID,
                    'name' => $village_name = $item['village']->post_title,
                    'initials' => $this->getInitials($village_name),
                    'permalink' => get_the_permalink($village_id),
                    'future_village' => get_field('future_village', $village_id)
                ]
            ];
        }, $markers);

        return $return;
    }

    public function renderMap()
    {
        $markers = $this->getMarkers();

        include(locate_template('partials/village-map.php'));
    }
}

new rangefordVillageMap();