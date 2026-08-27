// submenu jquary 
jQuery(document).ready(function () {
// script for add class on body while mega menu visible 
        // jQuery('#mega-menu-menu-1>li.mega-menu-item-has-children').hover(
        //     function () {
        //         // Add class when the menu item is hovered over
        //         jQuery(this).addClass('mega-toggle-on');
        //         jQuery('body').addClass('menu-open');
        //     },
        //     function () {
        //         // Remove class when the menu item is no longer hovered over
        //         jQuery(this).removeClass('mega-toggle-on');
                
        //         // Check if any other menu item is still hovered
        //         if (!jQuery('#mega-menu-menu-1>li').hasClass('mega-toggle-on')) {
        //             jQuery('body').removeClass('menu-open');
        //         }
        //     }
        // );


        jQuery('#mega-menu-menu-1>li.mega-menu-item-has-children').click(function(event) {
            event.stopPropagation(); // Prevent event from bubbling up
    
            // Toggle the class on click
            jQuery(this).toggleClass('mega-toggle-on');
    
            // Check if the clicked item now has the class
            if (jQuery(this).hasClass('mega-toggle-on')) {
                jQuery('body').addClass('menu-open');
            } else {
                // Check if any other menu item is still toggled on
                if (!jQuery('#mega-menu-menu-1>li').hasClass('mega-toggle-on')) {
                    jQuery('body').removeClass('menu-open');
                }
            }
        });

 
    // Event delegation for the submenu click
    // jQuery(document).on('click', '.has-submenu', function () {
    //     jQuery(this).closest('.menu-item-has-children').toggleClass('show');
    // });

    // // Event delegation for the back button click
    // jQuery(document).on('click', '.back-btn', function () {
    //     jQuery(this).closest('.menu-item-has-children').removeClass('show');
    // });


    jQuery(document).on('click', '.sub-arrow', function () {
        // alert("clicked");
        jQuery(this).parent('.menu-item-has-children').toggleClass('show');
    });

    // Event delegation for the back button click
    jQuery(document).on('click', '.back-btn', function () {
        jQuery(this).closest('.menu-item-has-children').removeClass('show');
    });



    jQuery(document).ready(function () {
        //  Wait for the DOM to be ready
        jQuery('.menu-item-has-children').append('<span class="sub-arrow"><svg viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1.5L6 6.5L1 11.5"  stroke-linecap="square"/></svg></span>');
    });






    // Add class to parent label when checkbox is checked 
    // at properties listing page 
    jQuery(' .properties-filter-row .amenities input[type="checkbox"]').change(function () {
        if (jQuery(this).prop('checked')) {
            jQuery(this).parent('label').addClass('checked-label');
        } else {
            jQuery(this).parent('label').removeClass('checked-label');
        }
    });




});


jQuery(document).ready(function () {


    jQuery(document).on('click', '.menu-popup-btn', function () {
        jQuery(this).toggleClass('active');

        if (jQuery('.menu-popup-btn').hasClass('active')) {
            jQuery('.menu-popup-btn').find('.elementor-button-text').text('Close');
        } else {
            jQuery('.menu-popup-btn').find('.elementor-button-text').text('Menu');

        }





    });
});

// jQuery(document).ready(function () {
//     var menuButton = jQuery('.menu-popup-btn');
//     // Function to toggle the menu state
//     function toggleMenu() {
//         var isDialogPreventScroll = jQuery('body').hasClass('dialog-prevent-scroll');
//         menuButton.toggleClass('active', isDialogPreventScroll);
//         var buttonText = isDialogPreventScroll ? 'Close' : 'Menu';
//         menuButton.find('.elementor-button-text').text(buttonText);
//     }
//     // Initial state after 2500 milliseconds (2.5 seconds)
//     setTimeout(toggleMenu, 2500);
//     // Click event to toggle the menu state
//     menuButton.click(function () {
//         toggleMenu();
//     });
// });





// Home hero slider 

jQuery(document).ready(function () {

    //for slick slider
    jQuery('.slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 8000,
    });

    jQuery(".slider .slick-dots").append("<li class='animated-dot'><li>");

    jQuery(".slick-dots .animated-dot").click(function () {
        jQuery(this).toggleClass("play");
        if (jQuery(this).hasClass("play")) {
            isPause = true;
            jQuery(this).css('background-image', 'url(https://img.icons8.com/plasticine/100/000000/pause.png)');
            jQuery('.slider').slick('slickPause');
            $bar.css({
                width: 100 + "%"
            });
        } else {
            isPause = false;
            jQuery(this).css('background-image', '');
            jQuery('.slider').slick('slickPlay');
        }
    });


    var time = 8;
    var $bar,
        isPause,
        tick,
        percentTime;

    $bar = jQuery('.slider-progress .progress');

    function startProgressbar() {
        resetProgressbar();
        percentTime = 0;
        isPause = false;
        tick = setInterval(interval, 10);
    }

    function interval() {
        if (jQuery(".slick-dots .animated-dot").hasClass("play")) {
            isPause = true;
        }
        if (isPause === false) {
            percentTime += 1 / (time + 0.1);
            $bar.css({
                height: percentTime + "%"
            });
            if (percentTime >= 100) {
                jQuery(".slider").slick('slickNext');
                startProgressbar();
            }
        }
    }

    function resetProgressbar() {
        $bar.css({
            height: 0 + '%'
        });
        clearTimeout(tick);
    }

    startProgressbar();

    jQuery(".slider").on("beforeChange", function () {
        resetProgressbar();
        startProgressbar();
        $bar.css({
            height: 100 + "%"
        });
    });

});


// script for right side megamenu button 

jQuery(document).ready(function () {
    // Click event for '.magamenubtn'
    jQuery('.magamenubtn').on('click', function () {
        // Check if '.e-n-menu-content' has the class 'e-active'
        if (jQuery('.e-n-menu-content').hasClass('e-active')) {
            // Add 'menu-open' class to body if 'e-active' is present
            jQuery('body').addClass('menu-open');
            jQuery('#e-n-menu-title-1721').find('.e-n-menu-title-text').text('Close');
        } else {
            // Remove 'menu-open' class from body if 'e-active' is not present
            jQuery('body').removeClass('menu-open');
            jQuery('#e-n-menu-title-1721').find('.e-n-menu-title-text').text('More');
        }
    });

    // Click event for the body
    jQuery('body').on('click', function (event) {
        // Check if the clicked element is not '.magamenubtn' or its children
        if (!jQuery(event.target).closest('.magamenubtn').length) {
            // Remove 'menu-open' class from body
            jQuery('body').removeClass('menu-open');
            jQuery('#e-n-menu-title-1721').find('.e-n-menu-title-text').text('More');
        }
    });


});






jQuery('.villages-slider .elementor-grid').slick({
    dots: false,
    infinite: false,
    autoplay: true,
    autoplaySpeed: 2000,
    pauseOnHover: true,
    speed: 300,
    slidesToShow: 3,
    infinite: true,
    // centerPadding: '60px',
    // centerMode: true,
    slidesToScroll: 1,
    responsive: [

        {
            breakpoint: 2200,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,



            }
        },

        {
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                centerPadding: '30px',
                centerMode: true,
                arrows: false

            }
        },

    ]
});
