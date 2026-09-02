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
                equalWeightButtons: true,
                flipButtons: true,
            },
            preferencesModal: {
                layout: 'box',
                position: 'right',
                equalWeightButtons: true,
                flipButtons: true,
            },
        },
        categories: {
            necessary: {
                readOnly: true,
            },
            analytics: {
                autoClear: {
                    cookies: [
                        {
                            name: /^_ga/,
                        },
                    ],
                },
            },
            marketing: {
                autoClear: {
                    cookies: [
                        {
                            name: '_fbp',
                        },
                        {
                            name: '_cfuvid',
                        },
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
                            },
                            {
                                title: 'Analytics Cookies',
                                description:
                                    'Analytical cookies help us to improve our website by collecting and reporting information on its usage.',
                                linkedCategory: 'analytics',
                            },
                            {
                                title: 'Marketing Cookies',
                                description:
                                    'We use marketing cookies to help us improve the relevancy of advertising campaigns you receive.',
                                linkedCategory: 'marketing',
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
