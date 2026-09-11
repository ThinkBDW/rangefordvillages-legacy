/*
 * Cookie consent (vanilla-cookieconsent 3.1.0, js/vendor/cookieconsent.umd.js).
 *
 * Consent drives GTM, not the other way round: the container always loads,
 * and every tag that sets cookies must be triggered inside GTM by the
 * dataLayer events pushed here (cookies_analytics_accept / _revoke,
 * cookies_marketing_accept / _revoke) or gated on the cookies_analytics /
 * cookies_marketing variables. No tracking script may be hardcoded in
 * templates -- it would bypass consent entirely.
 *
 * A menu/footer link wrapped in .cookie-settings-link reopens the
 * preferences modal.
 */
(function () {
    window.dataLayer = window.dataLayer || [];
    var CookieConsent = window.CookieConsent;
    if (!CookieConsent) {
        return;
    }

    var cookieSettingsButton = document.querySelector('.cookie-settings-link a');
    if (cookieSettingsButton) {
        cookieSettingsButton.setAttribute('data-cc', 'show-preferencesModal');
    }

    /*
     * Cookie inventory, from the media agency's declaration
     * (audit/Cookie Control Rangeford Villages - Cookies.csv). Names are as
     * the provider documents them; <wpid> and * are placeholders the provider
     * fills in per property or campaign.
     *
     * The CSV's Security and Functionality labels sit on cookies belonging to
     * Google's advertising and YouTube products, so they are declared under
     * marketing here -- nothing in that list is needed for this site to work.
     * Cell values are inserted as HTML, so escape < > and &.
     */
    var COOKIE_TABLE_HEADERS = {
        name: 'Cookie',
        provider: 'Set by',
        service: 'Service',
    };

    var ANALYTICS_COOKIES = [
        { name: '_ga', provider: 'Google', service: 'Google Analytics' },
        { name: '_ga_&lt;wpid&gt;', provider: 'Google', service: 'Google Analytics 360' },
        { name: '_gid', provider: 'Google', service: 'Google Analytics' },
        { name: '_gat[_&lt;customname&gt;]', provider: 'Google', service: 'Google Analytics' },
        { name: 'FPID', provider: 'Google', service: 'Google Analytics' },
        { name: 'FPLC', provider: 'Google', service: 'Google Analytics' },
        { name: '_dc_gtm_&lt;property-id&gt;', provider: 'Google', service: 'Google Analytics, Google Tag Manager' },
        {
            name: '__utma, __utmb, __utmc, __utmt, __utmv, __utmz',
            provider: 'Google',
            service: 'Google Analytics (legacy)',
        },
        { name: 'FCNEC', provider: 'Google', service: 'Funding Choices' },
    ];

    var MARKETING_COOKIES = [
        { name: '__gads', provider: 'Google', service: 'AdSense, Display &amp; Video 360, Google Ad Manager, Google Ads' },
        { name: '__gpi', provider: 'Google', service: 'AdSense, Google Ad Manager' },
        { name: '__gpi_optout', provider: 'Google', service: 'AdSense, Google Ad Manager' },
        { name: '__gsas', provider: 'Google', service: 'AdSense for Search' },
        {
            name: '__eoi',
            provider: 'Google',
            service: 'AdSense, AdSense for Search, Display &amp; Video 360, Google Ad Manager, Google Ads',
        },
        { name: 'NID', provider: 'Google', service: 'AdSense for Search, Google Ads' },
        {
            name: 'DSID',
            provider: 'Google',
            service: 'AdSense, Campaign Manager, Google Ad Manager, Google Analytics, Display &amp; Video 360, Search Ads 360',
        },
        {
            name: 'id',
            provider: 'Google',
            service: 'AdSense, Campaign Manager, Display &amp; Video 360, Google Ad Manager, Search Ads 360',
        },
        { name: 'GED_PLAYLIST_ACTIVITY', provider: 'Google', service: 'AdSense, Google Ad Manager, YouTube' },
        { name: 'ACLK_DATA', provider: 'Google', service: 'AdSense, Google Ad Manager, YouTube' },
        { name: 'RUL', provider: 'Google', service: 'Display &amp; Video 360, Google Ads' },
        { name: 'FCCDCF', provider: 'Google', service: 'Funding Choices' },
        { name: '1P_JAR*', provider: 'Google', service: 'Google Ads' },
        { name: 'Conversion', provider: 'Google', service: 'Google Ads' },
        { name: 'GCL_AW_P', provider: 'Google', service: 'Google Ads' },
        { name: '_gcl_aw, _gcl_gb, _gcl_gs, _gcl_ag', provider: 'Google', service: 'Google Ads' },
        { name: '_gac_gb_&lt;wpid&gt;', provider: 'Google', service: 'Google Ads' },
        { name: '_gac_&lt;wpid&gt;', provider: 'Google', service: 'Google Analytics' },
        { name: 'FPGCLAW, FPGCLGB, FPGSID', provider: 'Google', service: 'Google Ads' },
        { name: 'YSC', provider: 'Google', service: 'Google Ads, YouTube' },
        {
            name: 'VISITOR_INFO1_LIVE, VISITOR_INFO1_LIVE__k, VISITOR_INFO1_LIVE__default',
            provider: 'Google',
            service: 'Google Ads, YouTube',
        },
        { name: 'fr', provider: 'Facebook', service: 'Facebook advertising (.facebook.com)' },
        { name: 'sa-user-id, sa-user-id-v2, sa-user-id-v3', provider: 'StackAdapt', service: 'StackAdapt' },
        { name: 'sa-camp-*', provider: 'StackAdapt', service: 'StackAdapt' },
        { name: 'sa_aid_pv', provider: 'StackAdapt', service: 'StackAdapt' },
        { name: 'sa-r-date, sa-r-source', provider: 'StackAdapt', service: 'StackAdapt' },
        { name: 'sa-u-date, sa-u-source', provider: 'StackAdapt', service: 'StackAdapt' },
        { name: 'sa_*_sid, sa_*_adurl', provider: 'StackAdapt', service: 'StackAdapt' },
    ];

    function pushConsentState(category, accepted) {
        window.dataLayer.push({ event: 'cookies_' + category + (accepted ? '_accept' : '_revoke') });
        var state = {};
        state['cookies_' + category] = accepted;
        window.dataLayer.push(state);
    }

    CookieConsent.run({
        disablePageInteraction: true,
        hideFromBots: true,
        lazyHtmlGeneration: true,

        guiOptions: {
            consentModal: {
                layout: 'box inline',
                position: 'bottom left',
                equalWeightButtons: false,
                flipButtons: false,
            },
            preferencesModal: {
                layout: 'box',
                position: 'right',
                equalWeightButtons: true,
                flipButtons: false,
            },
        },
        categories: {
            necessary: {
                readOnly: true,
            },
            analytics: {
                // autoClear only reaches cookies on this domain. Most of the
                // Google cookies listed in the preferences modal are set on
                // google.com / doubleclick.net / youtube.com and cannot be
                // deleted from here -- listing them is disclosure, revoking
                // consent stops the tags that set them.
                autoClear: {
                    cookies: [
                        { name: /^_ga/ },       // _ga, _ga_<wpid>
                        { name: /^_gid$/ },
                        { name: /^_gat/ },      // _gat, _gat_<customname>
                        { name: /^__utm/ },     // __utma/b/c/t/v/z
                        { name: /^_dc_gtm_/ },
                        { name: 'FPID' },
                        { name: 'FPLC' },
                        { name: 'FCNEC' },
                    ],
                },
            },
            marketing: {
                autoClear: {
                    cookies: [
                        { name: '_fbp' },
                        { name: '_cfuvid' },
                        { name: /^_gcl_/ },     // _gcl_aw/_gb/_gs/_ag
                        { name: 'GCL_AW_P' },
                        { name: /^_gac_/ },     // _gac_<wpid>, _gac_gb_<wpid>
                        { name: /^FPGCL/ },     // FPGCLAW, FPGCLGB
                        { name: 'FPGSID' },
                        { name: 'FCCDCF' },
                        { name: /^sa[-_]/ },    // StackAdapt first-party set
                    ],
                },
            },
        },
        onConsent: function () {
            if (CookieConsent.acceptedCategory('analytics')) {
                pushConsentState('analytics', true);
            }
            if (CookieConsent.acceptedCategory('marketing')) {
                pushConsentState('marketing', true);
            }
        },
        onChange: function (param) {
            var changed = param.changedCategories;
            if (changed.indexOf('analytics') !== -1) {
                pushConsentState('analytics', CookieConsent.acceptedCategory('analytics'));
            }
            if (changed.indexOf('marketing') !== -1) {
                pushConsentState('marketing', CookieConsent.acceptedCategory('marketing'));
            }
        },
        language: {
            default: 'en',
            translations: {
                en: {
                    consentModal: {
                        title: 'We use cookies',
                        description:
                            'Some of these cookies are essential, while others help us to improve your experience.',
                        acceptAllBtn: 'Accept all',
                        acceptNecessaryBtn: 'Reject all',
                        showPreferencesBtn: 'Manage preferences',
                        footer: '<a href="/privacy/">Privacy Policy</a>\n<a href="/terms-and-conditions/">Terms and conditions</a>',
                    },
                    preferencesModal: {
                        title: 'Consent Preferences Center',
                        acceptAllBtn: 'Accept all',
                        acceptNecessaryBtn: 'Reject all',
                        savePreferencesBtn: 'Save preferences',
                        closeIconLabel: 'Close modal',
                        serviceCounterLabel: 'Service|Services',
                        sections: [
                            {
                                title: 'Cookie Usage',
                                description: 'We use cookies to improve your experience on our site',
                            },
                            {
                                title: 'Strictly Necessary Cookies <span class="pm__badge">Always Enabled</span>',
                                description:
                                    'Necessary cookies enable core functionality. The website cannot function properly without these cookies, and can only be disabled by changing your browser preferences.',
                                linkedCategory: 'necessary',
                                cookieTable: {
                                    headers: COOKIE_TABLE_HEADERS,
                                    body: [
                                        {
                                            name: 'cc_cookie',
                                            provider: 'rangefordvillages.co.uk',
                                            service: 'Cookie consent (records your choices on this banner)',
                                        },
                                    ],
                                },
                            },
                            {
                                title: 'Analytics Cookies',
                                description:
                                    'Analytical cookies help us to improve our website by collecting and reporting information on its usage.',
                                linkedCategory: 'analytics',
                                cookieTable: {
                                    caption: 'Cookies set when analytics is enabled',
                                    headers: COOKIE_TABLE_HEADERS,
                                    body: ANALYTICS_COOKIES,
                                },
                            },
                            {
                                title: 'Marketing Cookies',
                                description:
                                    'We use marketing cookies to help us improve the relevancy of advertising campaigns you receive.',
                                linkedCategory: 'marketing',
                                cookieTable: {
                                    caption: 'Cookies set when marketing is enabled',
                                    headers: COOKIE_TABLE_HEADERS,
                                    body: MARKETING_COOKIES,
                                },
                            },
                            {
                                title: 'More information',
                                description:
                                    'For more details, please read our <a class="cc__link" href="/cookies/">cookie policy</a>. If you have any queries about our policy on cookies or your choices, please <a class="cc__link" href="/contact-us/">contact us</a>.',
                            },
                        ],
                    },
                },
            },
        },
    });
})();
