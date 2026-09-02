/*
 * Events-archive category list behaviour. Recovered verbatim from Elementor
 * Pro Custom Code post #19743; its display condition (the events archive,
 * category archives included) is reproduced in inc/enqueue.php.
 */
jQuery(document).ready(function () {
   function updateEventCategoryList() {
        var currentUrl = window.location.href;
        // Remove previously added active class to prevent duplicates
        jQuery('.tribe-events-category-list a').removeClass('active');

        // Highlight active category based on URL
        jQuery('.tribe-events-category-list a').each(function () {
            var href = jQuery(this).attr('href'); // Get href attribute

            if (href && href.includes('/category/')) { // Check if href contains '/category/'
                var categorySlug = href.split('/category/')[1]; // Extract category slug
                if (categorySlug && currentUrl.includes('/category/' + categorySlug)) { // Compare with current URL
                    jQuery(this).addClass('active'); // Add 'active' class
                }
            }
        });

        // Ensure the toggle button works after AJAX refresh
        jQuery('.tribe-events-category-list-btn').off('click keydown').on('click keydown', function (event) {
            if (event.type === 'click' || event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                jQuery(this).toggleClass('active');

                // Update ARIA attribute
                var expanded = jQuery(this).attr('aria-expanded') === 'true' ? 'false' : 'true';
                jQuery(this).attr('aria-expanded', expanded);
            }
        });

        // Enable keyboard navigation for category links
        jQuery('.tribe-events-category-list a').off('keydown').on('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                window.location.href = jQuery(this).attr('href'); // Navigate to the category link
            }
        });
    }

    // Run function on page load
    updateEventCategoryList();
		
	
		function updateCategoryURLs() {
        var currentUrl = window.location.href;

        // Check if Month View is active
        var isMonthView = currentUrl.includes('tribe-bar-view=month') || jQuery('.tribe-events-calendar-month').length > 0;

        // Check if URL contains a specific month (YYYY-MM)
        var monthViewMatch = currentUrl.match(/\/(\d{4}-\d{2})\//);
        var monthSegment = monthViewMatch ? monthViewMatch[1] : '';

        // Check if Day View is active
        var dayViewMatch = currentUrl.match(/\/(\d{4}-\d{2}-\d{2}|today)\/$/);
        var isDayView = dayViewMatch !== null;
        var daySegment = isDayView ? dayViewMatch[1] : '';

        jQuery('.tribe-events-category-list a').each(function () {
            var link = jQuery(this).attr('href');

            if (link) {
                if (isMonthView) {
                    if (monthSegment) {
                        // If the URL contains a specific month (YYYY-MM)
                        link = link.replace(/\/$/, '') + '/' + monthSegment + '/';
                    } else {
                        // If it's the current month, use /month/
                        link = link.replace(/\/$/, '') + '/month/';
                    }
                } else if (isDayView) {
                    // Ensure correct day view format
                    link = link.replace(/\/day\/today\//, '/').replace(/\/day\/\d{4}-\d{2}-\d{2}\//, '/');
                    link = link.replace(/\/$/, '') + (daySegment === 'today' ? '/today/' : '/day/' + daySegment + '/');
                } else {
                    // Ensure List View stays clean
                    link = link.replace('/month/', '/').replace(/\/day\/.*?\//, '/');
                }

                // Remove duplicate /month/month/ or /day/day/
                link = link.replace('/month/month/', '/month/').replace('/day/day/', '/day/');

                // Update the href attribute
                jQuery(this).attr('href', link);
            }
        });

        // Show the Clear button if a category is active
        if (currentUrl.match(/\/category\/[^\/]+/)) {
            jQuery('.tribe-events-category-clear-btn').show();
        } else {
            jQuery('.tribe-events-category-clear-btn').hide();
        }
    }
		
		// Run on page load
		updateCategoryURLs();
	
		// Clear Category Filter (remove category from URL)
    jQuery('#clearCategoryFilter').on('click', function () {
        var currentUrl = window.location.href;
        // Remove category from the URL
        var newUrl = currentUrl.replace(/\/category\/[^\/]+/g, '').replace('/?tribe-bar-view=month', '').replace('/month', '');

        // Update the browser's location
        window.location.href = newUrl;
    });
	
		function addLocationSearchHandler() {
        const searchForm = jQuery(".tribe-events-c-events-bar__search-form");
        const locationInput = jQuery("#tribe-events-location");

        if (searchForm.length && locationInput.length) {
            searchForm.off("submit").on("submit", function (event) {
                let locationValue = locationInput.val().trim();

                if (locationValue !== "") {
                    let searchParams = new URLSearchParams(window.location.search);
                    searchParams.set("tribe-bar-location", locationValue);
                    window.location.search = searchParams.toString();
                    event.preventDefault(); // Prevent default form submission
                }
            });
        }
    }
		
    // Run on page load
    addLocationSearchHandler();
	
    // Run after AJAX-based view change (Month, Day, List)
    jQuery(document).on("tribe_events_views_ajax_success", function () {
        addLocationSearchHandler();
				updateEventCategoryList();
    });
		jQuery(document).on('beforeAjaxSuccess.tribeEvents', function () {
			setTimeout(function(){
				updateEventCategoryList();
				updateCategoryURLs();
			},500)
        
    });
		
		jQuery(document).click(function(event) {
			if (!jQuery(event.target).closest(".tribe-events-category-list").length) {
					if(jQuery('.tribe-events-category-list-btn.active').length){
						jQuery('.tribe-events-category-list-btn').removeClass('active');
					}
			}
	});
});
