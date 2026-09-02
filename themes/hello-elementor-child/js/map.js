/*
 * Google Map for village/property pages. Recovered verbatim from Elementor
 * Pro Custom Code post #19421; the external maps-api loader that preceded
 * it is enqueued separately in inc/enqueue.php with callback=initMap,
 * matching the original tag. Expects the mapData/mainLocation globals
 * printed by the maps shortcode (inc/shortcodes/maps.php).
 */
let map; 
let markers = [];

    

function initMap() {
    map = new google.maps.Map(document.getElementById('custom-google-map'), {
        zoom: 14,
        center: { lat: mainLocation.lat, lng: mainLocation.lng },
				/*
				 styles: [
								{
										"featureType": "all",
										"elementType": "labels.text.fill",
										"stylers": [
												{
														"saturation": "0"
												},
												{
														"color": "#003655"
												},
												{
														"lightness": "0"
												}
										]
								},
								{
										"featureType": "all",
										"elementType": "labels.text.stroke",
										"stylers": [
												{
														"visibility": "on"
												},
												{
														"color": "#ffffff"
												},
												{
														"lightness": 16
												}
										]
								},
								{
										"featureType": "all",
										"elementType": "labels.icon",
										"stylers": [
												{
														"visibility": "on"
												}
										]
								},
								{
										"featureType": "administrative",
										"elementType": "geometry.fill",
										"stylers": [
												{
														"color": "#fefefe"
												},
												{
														"lightness": 20
												}
										]
								},
								{
										"featureType": "administrative",
										"elementType": "geometry.stroke",
										"stylers": [
												{
														"color": "#fefefe"
												},
												{
														"lightness": 17
												},
												{
														"weight": 1.2
												}
										]
								},
								{
										"featureType": "landscape",
										"elementType": "geometry",
										"stylers": [
												{
														"color": "#fefaf1"
												},
												{
														"lightness": "6"
												}
										]
								},
								{
										"featureType": "poi",
										"elementType": "geometry",
										"stylers": [
												{
														"color": "#c2bb86"
												},
												{
														"lightness": 21
												}
										]
								},
								{
										"featureType": "poi.park",
										"elementType": "geometry",
										"stylers": [
												{
														"color": "#f3fdf7"
												},
												{
														"lightness": 21
												}
										]
								},
								{
										"featureType": "road",
										"elementType": "geometry.fill",
										"stylers": [
												{
														"color": "#f7f3eb"
												},
												{
														"lightness": "1"
												}
										]
								},
								{
										"featureType": "road",
										"elementType": "labels",
										"stylers": [
												{
														"color": "#1d1f1b"
												},
												{
														"weight": "0.01"
												}
										]
								},
								{
										"featureType": "road.highway",
										"elementType": "geometry.fill",
										"stylers": [
												{
														"color": "#f7f3eb"
												},
												{
														"lightness": "-4"
												}
										]
								},
								{
										"featureType": "road.highway",
										"elementType": "geometry.stroke",
										"stylers": [
												{
														"color": "#ffffff"
												},
												{
														"lightness": 29
												},
												{
														"weight": 0.2
												}
										]
								},
								{
										"featureType": "road.arterial",
										"elementType": "geometry",
										"stylers": [
												{
														"color": "#ead4a8"
												},
												{
														"lightness": "16"
												},
												{
														"saturation": "0"
												}
										]
								},
								{
										"featureType": "road.local",
										"elementType": "geometry",
										"stylers": [
												{
														"color": "#ead4a8"
												},
												{
														"lightness": 16
												}
										]
								},
								{
										"featureType": "road.local",
										"elementType": "labels",
										"stylers": [
												{
														"color": "#1d1f1b"
												},
												{
														"gamma": "1"
												},
												{
														"weight": "0.01"
												}
										]
								},
								{
										"featureType": "transit",
										"elementType": "geometry",
										"stylers": [
												{
														"color": "#c2bb86"
												},
												{
														"lightness": 19
												}
										]
								},
								{
										"featureType": "water",
										"elementType": "geometry",
										"stylers": [
												{
														"color": "#003655"
												},
												{
														"lightness": 17
												}
										]
								},
								{
										"featureType": "water",
										"elementType": "geometry.fill",
										"stylers": [
												{
														"color": "#003655"
												}
										]
								}
						]
						*/
    });


  
     var infoWindow = new google.maps.InfoWindow({
        content: '<div class="mainLocation locationMarker"><span>'+ mainLocation.label +'</span></div>', // Add the desired label text
        position: { lat: mainLocation.lat, lng: mainLocation.lng }, // Position of the label
			 	pixelOffset: new google.maps.Size(0, 12)
    });


     infoWindow.open(map);
		

    google.maps.event.addListener(infoWindow, 'domready', function () {
        var closeButton = document.querySelector('.gm-ui-hover-effect');
        if (closeButton) {
            closeButton.style.display = 'none';
        }
    });
		mapData.shift();

	
    mapData.forEach(function (location) {
			if(location.category != "Select Category"){
				var markerIcon = createCustomMarkerIcon(getIcon(location.category), 37, 50);
// 					var markerIcon = "https://rangeford-villages.local/wp-content/uploads/2025/02/marker-icon-blue-mobile.png";
			}
			console.log(markerIcon);
		
        var marker = new google.maps.Marker({
            position: { lat: location.lat, lng: location.lng },
            map: null,
            title: location.label, 
            category: location.category, 
						icon: markerIcon,
        });

        var infowindow = new google.maps.InfoWindow({
					pixelOffset: new google.maps.Size(0, 12)
				});

        marker.addListener('click', function () {
            infowindow.setContent('<div class="otherLocation locationMarker"><span>' + location.label + '</span></div>');
            infowindow.open(map, marker);
        });
        markers.push(marker);
    });
}
function createCustomMarkerIcon(iconUrl, width, height) {
    return {
        url: iconUrl, 
        scaledSize: new google.maps.Size(width, height),
    };
}
function getIcon(types) {
    if (!types) {
        return 'https://rangeford-villages.local/wp-content/uploads/2025/02/marker-icon-blue-mobile.png';
    }
		const slug = types
        .toLowerCase() 
        .replace(/[\s_]+/g, '-') 
        .replace(/[^a-z0-9-]/g, '') 
        .replace(/-+/g, '-'); 
    return "https://rangeford-villages.local/wp-content/uploads/2025/04/" + slug + "-pin-icon.svg";
}
	



document.addEventListener('DOMContentLoaded', function () {
    const filterContainer = document.getElementById('map-filters');
    let activeCategories = []; // Start with no active categories

    if (filterContainer) {
        filterContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('filter-button')) {
                const selectedCategory = e.target.getAttribute('data-category');

                // Handle "All" button separately
                if (selectedCategory === 'all') {
                    // Clear all active filters and reset to "All"
                    activeCategories = ['all'];
                    resetFilters();
                    updateActiveClasses(); // Add 'active' class to "All"
                    filterMarkers(activeCategories);
                } else {
                    // Remove "All" from activeCategories if any other filter is selected
                    activeCategories = activeCategories.filter(cat => cat !== 'all');

                    // Toggle the selected category
                    const index = activeCategories.indexOf(selectedCategory);
                    if (index === -1) {
                        // Add the category if it's not already active
                        activeCategories.push(selectedCategory);
                    } else {
                        // Remove the category if it's already active
                        activeCategories.splice(index, 1);
                    }

                    // If no categories are selected, hide all markers
                    if (activeCategories.length === 0) {
                        activeCategories = [];
                    }

                    // Update button active classes
                    updateActiveClasses();

                    // Filter the markers based on the selected categories
                    filterMarkers(activeCategories);
                }
            }
        });
    } else {
        console.error("Filter container not found. Ensure the HTML contains an element with id 'map-filters'.");
    }

    // Reset all filters to default state (no category selected)
    function resetFilters() {
        document.querySelectorAll('#map-filters .filter-button').forEach(function (button) {
            button.classList.remove('active');
        });
    }

    // Update the active classes for filter buttons
    function updateActiveClasses() {
        document.querySelectorAll('#map-filters .filter-button').forEach(function (button) {
            const category = button.getAttribute('data-category');
            if (category === 'all' && activeCategories.includes('all')) {
                // Add active class to "All" button
                button.classList.add('active');
            } else if (activeCategories.includes(category)) {
                // Add active class to selected categories
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }

    // Filter the markers based on the selected categories
    function filterMarkers(selectedCategories) {
        markers.forEach(function (marker) {
            if (selectedCategories.includes('all')) {
                // Show all markers if "All" is selected
                marker.setMap(map);
            } else if (selectedCategories.includes(marker.category)) {
                // Show markers matching the selected categories
                marker.setMap(map);
            } else {
                // Hide markers not matching the selected categories
                marker.setMap(null);
            }
        });
    }
});
