jQuery(document).ready(function ($) {
    // Map slugs to village names
    const villageMap = {
        "homewood-grove-care": "Homewood Grove Care",
        "homewood-grove": "Homewood Grove",
        "mickle-hill": "Mickle Hill",
        "siddington-park": "Siddington Park",
        "strawberry-fields": "Strawberry Fields",
        "wadswick-green": "Wadswick Green",
        "bramston-park-hampshire": "Bramston Park, Hampshire",
        "east-grinstead-west-sussex": "East Grinstead, West Sussex",
        "elstree-hertfordshire": "Elstree, Hertfordshire"
    };

    // Target the hidden field in your CF7 form
    const villageHidden = $('#wpcf7-f637-p20184-o3 input[name="your-field-name"]');

    function setVillageFromURL() {
        const path = window.location.pathname;
        if (path.includes('/villages/')) {
            const villagePath = path.split('/villages/')[1].replace(/\/$/, '');
            if (villageMap[villagePath]) {
                villageHidden.val(villageMap[villagePath]);
                console.log("Hidden village field set to: " + villageMap[villagePath]);
            } else {
                console.log("No mapping found for: " + villagePath);
            }
        }
    }

    // Run on page load
    setVillageFromURL();



    // Brochure buttons: open the popup tied to data-popmake (avoids 10766 stealing clicks from 32688).
    $('.single-brochure-view-button').on('click', function(e) {
        e.preventDefault();
        setVillageFromURL();
        window.lastClickedBrochureButton = $(this);

        var popupId = $(this).data('popmake');
        if (popupId && typeof PUM !== 'undefined' && typeof PUM.open === 'function') {
            e.stopImmediatePropagation();
            e.stopPropagation();
            PUM.open(popupId);
            return false;
        }
    });

    // var postIdBrochure = localStorage.getItem('post_id_brochure_id'); // Retrieve from localStorage

    // if (postIdBrochure) {
    //     // Create a hidden field and set its value using jQuery
    //     var hiddenField = $('<input>').attr({
    //         type: 'hidden',
    //         name: 'post_id_brochure_id',
    //         value: postIdBrochure
    //     });

    //     // Append the hidden field to the form or body
    //     $('body').append(hiddenField);

    //     // After using the postIdBrochure, remove it from localStorage
    //     //localStorage.removeItem('post_id_brochure');
    // }
    
// Get the current page URL
var postIdBrochure = localStorage.getItem('post_id_brochure_id'); // Get the stored ID
    console.log('Brochure ID: ' + postIdBrochure);
if (postIdBrochure) {
    // Create a hidden field and set its value
    var hiddenField = $('<input>').attr({
        type: 'hidden',
        id: 'post_id_brochure_id', // Set an ID for easy access
        value: postIdBrochure
    });



    // Append the hidden field to the body or a specific form
    $('body').append(hiddenField);
    $('#loader').show();

    // Trigger an AJAX call or update the page content with PHP
    $.ajax({
        url: script_data.ajax_url,
        method: 'POST',
        data: {
            action: 'get_brochure_link',
            post_id_brochure: postIdBrochure
        },
        success: function(response) {
            $('#wbrochure-link-container').html(response);

            // Remove the item from localStorage after successful response

                localStorage.removeItem('post_id_brochure_id');
           
        },
        complete: function() {
            // Hide the loader after the request completes
            $('#loader').hide();
        }
    });
}
     //$('.amenities1 label[for="all"]').addClass('checked-label');
    jQuery('.amenities1 label:has(input:checked)').addClass('checked-label');

    // Attach click event to radio buttons
    $('.amenities1 input[name="development[]"]').click(function(){
        // Remove 'active' class from all labels
        $('label').removeClass('checked-label');

        // Add 'active' class to the clicked label
        $(this).closest('label').addClass('checked-label');
    });
    /*Light Box */
    // var $slider = $(".slider-popup-image");
    $(".slider-popup-image:not(.property-slider-popup-image)").slick({
        // Slick slider settings go here
        slidesToShow: 1,
        infinite: true,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 6000,
        arrows: true,
        pauseOnHover:true,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2024/01/left-slide.svg'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='/wp-content/uploads/2024/01/right-slide.svg'>"
      });
      jQuery(".slider-popup-image:not(.property-slider-popup-image) + .property-slider-thumbs").slick({
        // Slick slider settings go here
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 6000,
        arrows: true,
        pauseOnHover:true,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2024/01/left-slide.svg'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='/wp-content/uploads/2024/01/right-slide.svg'>",
        asNavFor: '.slider-popup-image:not(.property-slider-popup-image)',
        focusOnSelect: true,
        responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 5
            }
        },
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 2
            }
        }
    ]
    });

    $(".slider-popup-image:not(.property-slider-popup-image)").on('afterChange', function(event, slick, currentSlide){
        console.log(jQuery(".property-slider-thumbs .slick-slide"));
        console.log(jQuery(".property-slider-thumbs .slick-slide").eq(currentSlide))
        jQuery(".property-slider-thumbs .slick-slide").removeClass('slick-current');
        jQuery(".property-slider-thumbs .slick-slide[data-slick-index='" + currentSlide + "']").addClass('slick-current'); 
    });
    //   $(".gallery-images .gallery-item img").on('click', function(e) {
    //     e.preventDefault();
    //     $slider[0].slick.refresh();      
    //   });
      $('.slider-gallery').slick({
        // Slick slider settings go here
        slidesToShow: 3,
        infinite: true,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
        prevArrow: "<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2023/12/Group-108.png'>",
        nextArrow: "<img class='a-right control-c next slick-next' src='/wp-content/uploads/2023/12/Group-91.png'>"
      })
      // Show the lightbox when the button is clicked
      $('.open-lightbox').on('click', function(){
        $('.lightbox').fadeIn();
      });
      // Show the lightbox when the button is clicked
      $('.masonry-gallery .light-box-btn, .second-image, .gallery-images .elementor-image-gallery .gallery-item img,.gallery-images .gallery-item .gallery-child-images img').on('click', function(){
        var index = $(this).index('.gallery-child-images img');
        console.log(index);
        // Set the slider to show the clicked image first
        $(".slider-popup-image").slick('slickGoTo', index);
    
            $('.lightbox').fadeIn();

            setTimeout(function(){
                jQuery(".property-slider-thumbs").slick('refresh');
                jQuery(".slider-popup-image").slick('refresh');
            }, 500);
      });
      $('.play-icon').on('click', function(){
        $('.lightbox-video').fadeIn();
      });
    //   $('.gallery-images .elementor-image-gallery .gallery-item img').on('click', function(){
    //     $('.lightbox').fadeIn();
    //   });
      // Close the lightbox when clicking outside the slider or on the close icon
         // Click handler for the back-property button
    $('.back-property').on('click', function() {
        $('.lightbox').fadeOut();
    });
    $(document).on('click', function(event) {
        
    });
    // Click handler for opening the lightbox (for example, when clicking on gallery images)
    

    // Click handler for clicks outside the slider-popup-image to trigger back-property
    // $(document).on('click', function(event) {
    //     if ($(event.target).closest('.slider-popup-image').length === 0) {
    //         // Clicked outside the slider-popup-image, trigger back-property action
    //         $('.back-property').trigger('click');
    //     }
    // });
    
      // Close the lightbox when clicking outside the slider
    //   $('.lightbox').on('click', function(event){
    //     if ($(event.target).hasClass('lightbox')) {
    //       $(this).fadeOut();
    //     }
    //   });
    //   $('.lightbox-video').on('click', function(event){
    //     if ($(event.target).hasClass('lightbox-video')) {
    //       $(this).fadeOut();
    //     }
    //   });
    /*accrodian */
    $('.accordion-header').click(function() {
        $(this).next('.accordion-content').slideToggle();
        $(this).find('.toggle-icon').text(function(_, text) {
          return text === '+' ? '-' : '+';
        });
      });
    $('.amenities label:gt(2)').hide();
        // Handle "Read More" click event
        $('.read-more').on('click', function() {
            // Toggle the visibility of hidden categories beyond the first three
            $('.amenities label:gt(2)').toggle();
            // Update the text of the "Read More" button
            $(this).text(function(i, text) {
                return text === "Show more" ? "Show Less" : "Show more";
            });
        });
    $(".more-filter").click(function () {
        $(this).toggleClass("active");
        var $span = $(".more-filter span");
        var $moreFilterData = $(".more-filter-data");

        if ($span.text() === "Open Filter") {
            $span.text("Close Filter");
        } else {
            $span.text("Open Filter");
        }

        $moreFilterData.slideToggle();
    });

    // Function to initialize Slick slider
    function initSlick() {
        $(".slick-slider-image").slick({
            slidesToShow: 1,
            infinite: true,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            arrows: true,
            prevArrow: "<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2023/12/Group-108.png'>",
            nextArrow: "<img class='a-right control-c next slick-next' src='/wp-content/uploads/2023/12/Group-91.png'>"
        });
    }

    // Call the Slick initialization function
     initSlick();
     updateWishlistIconssingle();

     $('#load-more-btn').on('click', function() {

        let propertyIds = [];
        $('.wishlist-icon-section').each(function() {
            // Get the value of data-property-id and push it to the array
            const propertyId = $(this).data('property-id');
            if (propertyId) {
                propertyIds.push(propertyId);
            }
        });

        var button = $(this);
        var offset = button.data('offset');
        var postsPerPage = button.data('posts-per-page');
        var ajaxUrl = button.data('ajax-url');
        // var propertyIds = button.data('property-ids');
        
        // Get the current post title
        var currentPostTitle = $('#current-post-title').text(); // Ensure this is where you set the title
        var currentPostId = jQuery('body').attr('class').match(/postid-([0-9]*)/)
    
        $.ajax({
            url: script_data.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_properties',
                offset: offset,
                posts_per_page: postsPerPage,
                property_ids: propertyIds,
                current_post_title: currentPostTitle,
                current_post_id: currentPostId[1] // Send the current post ID
            },
            beforeSend: function() {
                button.text('Loading...');
            },
            success: function(response) {
                $('.loadmore-village-properties').append(response);
                // Initialize Slick Slider on newly added elements
                $('.slick-slider-image').not('.slick-initialized').slick({
                    slidesToShow: 1,
                    infinite: true,
                    slidesToScroll: 1,
                    autoplay: false,
                    autoplaySpeed: 2000,
                    arrows: true,
                    prevArrow: "<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2023/12/Group-108.png'>",
                    nextArrow: "<img class='a-right control-c next slick-next' src='/wp-content/uploads/2023/12/Group-91.png'>"
                });
                button.data('offset', offset + postsPerPage);
                button.text('Load More');
            },
            error: function() {
                button.text('No more properties found');
            }
        });
    });

    var ajaxurl = script_data.ajax_url;

    var currentPage = 1; // Initialize current page
    var minrangePrice = null;
    var maxrangePrice = null;
    // Function to handle slider changes
    function handleSliderChange() {
            minrangePrice = $('#minPriceSlider').val();
            maxrangePrice = $('#maxPriceSlider').val();
        }

    // Function to handle pagination
    function loadProperties(page = 1, readFromUrl = false) {
        let selectedValue, selectedLocation, selectedPropertyType, minPrice, maxPrice,
            minBed, maxBed, condition, hideReserved, savedproperties, selectedAmenities = [];
    
        if (readFromUrl) {
            const urlParams = new URLSearchParams(window.location.search);
            selectedValue = urlParams.get('sort') || '';
            selectedLocation = urlParams.get('location') || '';
            selectedPropertyType = urlParams.get('property_type') || '';
            minPrice = urlParams.get('min_price') || '';
            maxPrice = urlParams.get('max_price') || '';
            if (minPrice) urlParams.set('min_price', minPrice);
            if (maxPrice) urlParams.set('max_price', maxPrice);
            minBed = urlParams.get('min_bed') || '';
            maxBed = urlParams.get('max_bed') || '';
            condition = urlParams.get('condition') || '';
            hideReserved = urlParams.get('hide_reserved') === 'true';
            savedproperties = urlParams.get('saved_properties') === 'true';
            selectedAmenities = urlParams.getAll('amenities');
    
            // Pre-fill form inputs
            $('#price_sorting').val(selectedValue);
            $('select[name="location_dropdown"]').val(selectedLocation);
            $('select[name="property_type_dropdown"]').val(selectedPropertyType);
            $('#min_price').val(minPrice);
            $('#max_price').val(maxPrice);
            minPrice = $('#minPriceSlider').val();
            maxPrice = $('#maxPriceSlider').val();
            $('#min_bed').val(minBed);
            $('#max_bed').val(maxBed);
            $(`input[name="condition"][value="${condition}"]`).prop('checked', true);
            $('#reserved').prop('checked', hideReserved);
            $('#savedproperties').prop('checked', savedproperties);
            $('input[name="amenities[]"]').prop('checked', false);
            selectedAmenities.forEach(val => {
                $(`input[name="amenities[]"][value="${val}"]`).prop('checked', true);
            });
        } else {
            selectedValue = $('#price_sorting').val();
            selectedLocation = $('select[name="location_dropdown"]').val();
            selectedPropertyType = $('select[name="property_type_dropdown"]').val();
            minPrice = $('#min_price').val();
            maxPrice = $('#max_price').val();
            minPrice = $('#minPriceSlider').val();
            maxPrice = $('#maxPriceSlider').val();
            minBed = $('#min_bed').val();
            maxBed = $('#max_bed').val();
            condition = $('input[name="condition"]:checked').val() || '';
            hideReserved = $('#reserved').is(':checked');
            savedproperties = $('#savedproperties').is(':checked');
            $('input[name="amenities[]"]:checked').each(function () {
                selectedAmenities.push($(this).val());
            });
    
            // Update URL
            const urlParams = new URLSearchParams();
            if (selectedValue) urlParams.set('sort', selectedValue);
            if (selectedLocation) urlParams.set('location', selectedLocation);
            if (selectedPropertyType) urlParams.set('property_type', selectedPropertyType);
            if (minPrice) urlParams.set('min_price', minPrice);
            if (maxPrice) urlParams.set('max_price', maxPrice);
            if (minBed) urlParams.set('min_bed', minBed);
            if (maxBed) urlParams.set('max_bed', maxBed);
            if (condition) urlParams.set('condition', condition);
            if (hideReserved) urlParams.set('hide_reserved', 'true');
            if (savedproperties) urlParams.set('saved_properties', 'true');
            selectedAmenities.forEach(val => urlParams.append('amenities', val));
            urlParams.set('page', page);
    
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, '', newUrl);
        }
    
        const data = {
            action: 'sort_properties',
            sort_value: selectedValue,
            location: selectedLocation,
            property_type: selectedPropertyType,
            minrangePrice: minPrice,
            maxrangePrice: maxPrice,
            min_bed: minBed,
            max_bed: maxBed,
            condition: condition,
            hide_reserved: hideReserved,
            saved_properties: savedproperties,
            amenities: selectedAmenities,
            page: page
        };
    
        $('.loader').show();
    
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function (response) {
                $('.loader').hide();
                $('.property-tiles-container').html(response);
                $('html, body').animate({
                    scrollTop: $('.properties-filter-row').offset().top - 400
                }, 400);
                initSlick();
                updateWishlistIcons();
            },
            error: function () {
                $('.loader').hide();
            }
        });
    }

    // Sorting and Pagination on select change
    $('#price_sorting').change(function () {
        loadProperties(currentPage);
    });

    // Wishlist icon click event
    $(document).on('click', '.wishlist-icon-section,.wishlist-icon-section-single', function (e) {
        e.preventDefault();
        var $wishlistIcon = $(this);
        var propertyId = $wishlistIcon.data('property-id');

        // Toggle between wishlist icons
        $wishlistIcon.find('.without-fill, .fill-whish-list').toggle();

        // Update wishlist state in Local Storage
        updateLocalStorage(propertyId);

        // Ajax call to handle_wishlist
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'handle_wishlist',
                property_id: propertyId,
            },
            success: function (response) {
                // Update the wishlist count in the header
                $('.wishlist-count').text(response);
            },
            error: function () {
                // Handle error if needed
            }
        });
    });

    // Pagination click event
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page/')[1];
        loadProperties(page);
    });
    // Add change event listener for the first set of "Min Price" and "Max Price" dropdowns
    $('#min_price, #max_price').change(function () {
        loadProperties(currentPage);
    });
    $('#minPriceSlider, #maxPriceSlider').change(function () {
        handleSliderChange();
        loadProperties(currentPage);
    });

    $('#min_bed, #max_bed').change(function () {
        loadProperties(currentPage);
    });

    // Add change event listener for the second set of "Min Price" and "Max Price" dropdowns
    $('#min_bed, #max_bed').change(function () {
        loadProperties(currentPage);
    });
    // Add change event listener for the radio buttons (New and Resale)
    $('input[name="condition"]').change(function () {
        loadProperties(currentPage);
    });
    $('input[name="reserved"]').change(function () {
        loadProperties(currentPage);
    });
    $('input[name="savedproperties"]').change(function () {
        loadProperties(currentPage);
    });
    $('input[name="amenities[]"]').change(function () {
        loadProperties(currentPage);
    });

    // Initial load
    if($('body.page-template-proprty-template-php').length){
        loadProperties(1, true);
    }
    updateWishlistIcons();

     // Update wishlist icons on initial load


    // Function to update wishlist icons based on Local Storage
    function updateWishlistIcons() {
        $('.wishlist-icon-section').each(function () {
            var propertyId = $(this).data('property-id');
            var wishlist = getWishlistFromLocalStorage();
            if (wishlist.includes(propertyId)) {
                $(this).find('.without-fill, .fill-whish-list').toggle();
            }
        });
    }


    function updateWishlistIconssingle() {
        var wishlistIcon = $('.wishlist-icon-section-single');
        var propertyId = wishlistIcon.data('property-id');
        var wishlist = getWishlistFromLocalStorage();
        if (wishlist.includes(propertyId)) {
            wishlistIcon.find('.without-fill, .fill-whish-list').toggle();
            
        }
       
    }
    
    // Add change event listener for "Select villages" dropdown
    $('select[name="location_dropdown"]').change(function () {
        loadProperties(currentPage);
    });

    // Add change event listener for "Property type" dropdown
    $('select[name="property_type_dropdown"]').change(function () {
        loadProperties(currentPage);
    });

    // Function to update Local Storage with wishlist items
    function updateLocalStorage(propertyId) {
        var wishlist = getWishlistFromLocalStorage();
        var expirationTimestamp = new Date().getTime() + 3 * 30 * 24 * 60 * 60 * 1000; // Three months expiration time

        if (wishlist.includes(propertyId)) {
            wishlist = wishlist.filter((id) => id !== propertyId);
        } else {
            wishlist.push(propertyId);
        }

        var dataToStore = {
            wishlist: wishlist,
            expirationTimestamp: expirationTimestamp,
        };

        localStorage.setItem('wishlistData', JSON.stringify(dataToStore));
    }

    // Function to get wishlist items from Local Storage
    function getWishlistFromLocalStorage() {
        var wishlistData = localStorage.getItem('wishlistData');

        if (wishlistData) {
            var parsedData = JSON.parse(wishlistData);

            // Check if the session has expired
            if (parsedData.expirationTimestamp < new Date().getTime()) {
                // Session has expired, return an empty array
                return [];
            } else {
                return parsedData.wishlist ? parsedData.wishlist : [];
            }
        }

        return [];
    }

    $('#resetFiltersBtn').click(function () {
        // Reset all filter values to their default or initial states
        $('#price_sorting').val(''); // Reset sorting dropdown
        $('select[name="location_dropdown"]').val(''); // Reset location dropdown
        $('select[name="property_type_dropdown"]').val(''); // Reset property type dropdown
        //$('#min_price').val(''); // Reset min price input
        //$('#max_price').val(''); // Reset max price input
        $('#minPriceSlider').val('');
        $('#maxPriceSlider').val('');
        $('#min_bed').val(''); // Reset min bed input
        $('#max_bed').val(''); // Reset max bed input
        $('input[name="condition"]').prop('checked', false); // Uncheck condition radio buttons
        $('#reserved').prop('checked', false); // Uncheck reserved checkbox
        $('#savedproperties').prop('checked', false); // Uncheck saved properties checkbox
        $('input[name="amenities[]"]').prop('checked', false); // Uncheck amenities checkboxes

        // Reload properties with default filter values
        loadProperties(1);
    });
    
/*Siddington Park Js */
jQuery('.custom-slider-park').slick({
    centerMode: true,
    centerPadding: '0px',
    slidesToShow: 3,
    autoplay: true,
    loop:true,
    arrows: true,
    prevArrow:"<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2024/01/Group-111.png'>",
    nextArrow:"<img class='a-right control-c next slick-next' src='/wp-content/uploads/2024/01/Group-82.png'>",
    responsive: [
      {
        breakpoint: 992,
        settings: {
        centerMode: true,
         centerPadding: '100px',
         slidesToShow: 1,
		 arrows: true,
        }
      },
		{
       breakpoint: 767,
        settings: {
          arrows: true,
          centerMode: true,
          centerPadding: '40px',
           slidesToShow: 1,
        }
      },
      {
        breakpoint: 480,
        settings: {
			   arrows: true,
          centerMode: true,
			 slidesToShow: 1,
          centerPadding: '20px',
        
        }
      }
    ]
  });

/*Form submit after redirect broucher archive page */    
// document.addEventListener('wpcf7mailsent', function(event) {
//         if (window.lastClickedBrochureButton) {
//             var termUrl = window.lastClickedBrochureButton.data('term-url');
//             if (termUrl) {
//                 window.open(termUrl, '_blank'); // Opens the link in a new tab
//                 var redirectUrl1 = '/thank-you';
//                 location = redirectUrl1 ;
//                 $('#popmake-10766 .wpcf7-form sent .wpcf7-response-output').hide();
//                 $('#popmake-10766').find('.popmake-close').trigger('click');
               
             
//             }
//         }
//     }, false);

    /*For library */
    document.addEventListener('wpcf7mailsent', function(event) {
        if (window.lastClickedBrochureButton) {
            var termpdf = window.lastClickedBrochureButton.data('pdf-url');
            if (termpdf) {
                window.location.href = termpdf;
            }
        }
    }, false); 
    

    jQuery(document).ajaxComplete(function(event, xhr, settings) {
        // Check if the completed AJAX request matches your desired action name
        if (settings.data && settings.data.indexOf('action=sort_properties') !== -1) {
            // Your custom jQuery code here
            var valueToStore = $('.total-properties').text(); // Get the text from the p element
            $('.properties-totalfilter').text(valueToStore + ' Properties'); // Store the text into another div
            // Your additional code here
        }
    });
    /*Remove comma from the tag list */
 
    $('.single-post-tag .elementor-post-info__terms-list').contents().filter(function() {
        return this.nodeType === 3 && this.nodeValue.includes(',');
    }).remove(); 
   
});
/*Village page megamenu toggle */
jQuery(window).load(function() {
jQuery("#mega-menu-item-1857").hover(function() {
    // Check if the body has a specific class
    if (jQuery("body").hasClass("page-id-631")) {
      // Add the class 'mega-toggle-on' to #mega-menu-item-5063
      jQuery("#mega-menu-item-5063").addClass("mega-toggle-on");
    }
  }, function() {
    if (jQuery("body").hasClass("page-id-631")) {
      // Remove the class 'mega-toggle-on' when the hover ends
      jQuery("#mega-menu-item-5063").removeClass("mega-toggle-on");
    }
  });
});
 


  


// tootip popup in budget calculator 
// function toggleTooltip() {
//     var tooltip = document.querySelector('.tooltip');
//     // var tooltipText = document.getElementById('tooltipText');
//     var isActive = tooltip.classList.contains('active');

//     if (isActive) {
//         // tooltipText.innerHTML = 'Tooltip text';
//         tooltip.classList.remove('active');
//     } else {
//         // tooltipText.innerHTML = 'Close';
//         tooltip.classList.add('active');
//     }
// }
function toggleTooltip(tooltip) {
    var isActive = tooltip.classList.contains('active');

    if (isActive) {
        tooltip.classList.remove('active');
    } else {
        tooltip.classList.add('active');
    }
}





// custo range slider in budget calculator 
var sliders = document.querySelectorAll(".slider");
var progresses = document.querySelectorAll(".progress");
var values = document.querySelectorAll(".value");

sliders.forEach((slider, index) => {
    slider.addEventListener("input", function() {
        progresses[index].style.width = this.value + "%";
        values[index].innerHTML = this.value;
    });
});







function updateValue($slider) {
    var $rangeWrap = $slider.closest('.range-wrap');
    var $valueElement = $rangeWrap.next('.slider-value').find('.range-slider-value');
    var $rangeValueElement = $rangeWrap.find('.range-value');

    var min = $slider.attr('min');
    var max = $slider.attr('max');
    var value = $slider.val();

    $valueElement.text(value);

    var percentage = ((value - min) / (max - min)) * 100;
    $rangeValueElement.css('width', percentage + '%');
}

jQuery(document).ready(function () {
    jQuery('.range-slider').on('input', function () {
        updateValue(jQuery(this));
    });

    // Initialize slider values
    jQuery('.range-slider').each(function () {
        updateValue(jQuery(this));
    });
});




// Pie chart js 

// jQuery(document).ready(function () {
//     const ctx = jQuery('#ChartOne')[0].getContext('2d');
//     const pieChart = new Chart(ctx, {
//       type: 'pie',
//       data: {
//         labels: ['Home', 'Home Maintanance', 'Transport', 'Discretionary Spend', 'Services Charge', 'Care Cost'],
//         datasets: [{
//           label: 'Values',
//           data: [
//             jQuery('#Home').val(),
//             jQuery('#HomeMaintanance').val(),
//             jQuery('#Transport').val(),
//             jQuery('#DiscretionarySpend').val(),
//             jQuery('#ServicesCharge').val(),
//             jQuery('#CareCost').val()
//           ],
//           backgroundColor: ['#6294a8', '#958bc9', '#5c9182', '#e88770', '#c96dc9', '#e5d16e']
//         }]
//       }
//     });
  
//     function updateChart() {
//       pieChart.data.datasets[0].data = [
//         jQuery('#Home').val(),
//         jQuery('#HomeMaintanance').val(),
//         jQuery('#Transport').val(),
//         jQuery('#DiscretionarySpend').val(),
//         jQuery('#ServicesCharge').val(),
//         jQuery('#value3').val()
        
//       ];
//       pieChart.update();
//     }
  
//     jQuery('#Home').on('input', updateChart);
//     jQuery('#HomeMaintanance').on('input', updateChart);
//     jQuery('#Transport').on('input', updateChart);
//     jQuery('#DiscretionarySpend').on('input', updateChart);
//     jQuery('#ServicesCharge').on('input', updateChart);
//     jQuery('#CareCost').on('input', updateChart);
//   });


jQuery(document).ready(function () {
    // const ctx = jQuery('#Charttwo')[0].getContext('2d');
    // const pieChart = new Chart(ctx, {
    //   type: 'pie',
    //   data: {
    //     labels: ['Home', 'Home Maintanance', 'Transport', 'Discretionary Spend', 'Services Charge', 'Care Cost'],
    //     datasets: [{
    //       label: 'Values',
    //       data: [
    //         jQuery('#Home2').val(),
    //         jQuery('#HomeMaintanance2').val(),
    //         jQuery('#Transport2').val(),
    //         jQuery('#DiscretionarySpend2').val(),
    //         jQuery('#ServicesCharge2').val(),
    //         jQuery('#CareCost2').val()
    //       ],
    //       backgroundColor: ['#6294a8', '#958bc9', '#5c9182', '#e88770', '#c96dc9', '#e5d16e']
    //     }]
    //   }
    // });
  
    // function updateChart() {
    //   pieChart.data.datasets[0].data = [
    //     jQuery('#Home2').val(),
    //     jQuery('#HomeMaintanance2').val(),
    //     jQuery('#Transport2').val(),
    //     jQuery('#DiscretionarySpend2').val(),
    //     jQuery('#ServicesCharge2').val(),
    //     jQuery('#CareCost2').val()
        
    //   ];
    //   pieChart.update();
    // }
  
    // jQuery('#Home2').on('input', updateChart);
    // jQuery('#HomeMaintanance2').on('input', updateChart);
    // jQuery('#Transport2').on('input', updateChart);
    // jQuery('#DiscretionarySpend2').on('input', updateChart);
    // jQuery('#ServicesCharge2').on('input', updateChart);
    // jQuery('#CareCost2').on('input', updateChart);
  });


  document.addEventListener('DOMContentLoaded', function() {
    // Retrieve the post_id_brochure_id from localStorage
    var postIdBrochureId = localStorage.getItem('post_id_brochure_id');

    if (postIdBrochureId) {
        // Set the hidden field with the post_id_brochure_id value
        var hiddenField = document.querySelector('input[name="post_id_brochure_id"]');
        if (hiddenField) {
            hiddenField.value = postIdBrochureId;
        }
    }
});

jQuery(document).ready(function($) {

    // When the village select changes
    $(document).on('change', 'select[name="your-field-name"]', function() {
        const villageName = $(this).val();
        if (!villageName) return;

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'get_village_thankyou_url',
                village_name: villageName
            },
            success: function(response) {
                console.log('AJAX Response:', response);

                if (response.success && response.data) {

                    const { url, term_id } = response.data;

                    // --- Update thank-you URL hidden field ---
                    if (url) {
                        $('.villages-thank-url').val(url);
                        console.log('Updated thank-you URL:', url);
                    }

                    // --- Update brochure ID localStorage + hidden field ---
                    if (term_id) {
                        console.log('Updated brochure ID:', term_id);

                        // Save to localStorage for later use on brochure page
                        localStorage.setItem('post_id_brochure_id', term_id);

                        // Check if the hidden field exists in the form
                        let hiddenField = $('input[name="post_id_brochure_id"]');
                        if (!hiddenField.length) {
                            // Create it if missing
                            hiddenField = $('<input>').attr({
                                type: 'hidden',
                                name: 'post_id_brochure_id',
                                id: 'post_id_brochure_id',
                                value: term_id
                            });
                            $('form.wpcf7-form').append(hiddenField);
                        } else {
                            // Update existing field
                            hiddenField.val(term_id);
                        }
                    }
                } else {
                    console.warn(response?.data?.message || 'No data returned from server');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });


    // --- On page load, restore hidden input from localStorage if it exists ---
    const storedBrochureId = localStorage.getItem('post_id_brochure_id');
    if (storedBrochureId) {
        console.log('Restored brochure ID from storage:', storedBrochureId);

        let hiddenField = $('input[name="post_id_brochure_id"]');
        if (!hiddenField.length) {
            hiddenField = $('<input>').attr({
                type: 'hidden',
                name: 'post_id_brochure_id',
                id: 'post_id_brochure_id',
                value: storedBrochureId
            });
            $('form.wpcf7-form').append(hiddenField);
        } else {
            hiddenField.val(storedBrochureId);
        }
    }

});
