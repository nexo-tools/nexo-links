<?php

// Legal pages (privacy + terms), rendered by legal/show. Translation of the
// Spanish source in lang/es/legal.php — keep the three files in step.
//
// NOT reviewed by a lawyer. Written to describe accurately what this codebase
// actually does, so that a review, if the owner wants one, starts from
// something true rather than from a template full of clauses about data the
// app never collects.
return [
    'updated' => 'Last updated: 28 July 2026',

    // Rendered only when NEXO_LEGAL_OPERATOR / NEXO_LEGAL_CONTACT are set.
    'operator' => [
        'h' => 'Who runs this instance',
        'p' => 'This instance is run by :operator.',
        'contact' => 'For anything about your data you can write to :contact.',
    ],

    'privacy' => [
        'title' => 'Privacy',
        'intro' => 'This instance of Nexo Links is open source and self-hosted. We collect the minimum needed for your links page to work, and nothing else. There are no tracking cookies, no third-party analytics, and no requests to anyone else\'s servers from any page.',
        'sections' => [
            [
                'h' => 'What we store about your account',
                'p' => 'Your name, your email and an encrypted (hashed) version of your password, plus the date you verified your email. The email is used to verify the account and to recover access, and nothing else: we send no newsletters. If this instance has Nexo ID sign-in enabled and you use it, we also store the identifier that service gives us to recognise you.',
            ],
            [
                'h' => 'Your public page is public',
                'p' => 'Your username, bio, photo, banner, links (title and destination) and social icons are published at this site\'s /yourname address for anyone who has it, and the page is listed in the sitemap so search engines can index it. The photo and banner live in a public folder on the server: anyone with their URL can see them, even after you remove the link. Do not put anything there you would not publish — a phone number or an email address added as a social icon is on display too.',
            ],
            [
                'h' => 'What we measure about visits',
                'p' => 'Only the clicks on your links. For each click we store which link it was, when it happened, an anonymous visitor fingerprint and, if it came from another site, only the originating domain (for example "instagram.com", without the path or the query string). Clicks that come from your own page are stored as direct. We do not store the IP address, the browser, the location, or any identifier that persists.',
            ],
            [
                'h' => 'Why the fingerprint cannot follow you',
                'p' => 'The fingerprint is the result of running SHA-256 over four things together: this installation\'s secret key, today\'s date, your IP address and your browser. Only that result is stored; the IP and the browser are discarded immediately and cannot be recovered from it. Because the date goes into the calculation, tomorrow the same person produces a completely different fingerprint and there is no way to match the two: it can count "how many distinct people clicked today" and nothing more. And because the key belongs to this installation, it cannot be cross-referenced with another site\'s either.',
            ],
            [
                'h' => 'Cookies',
                'p' => 'Only the ones the site needs to work: the session cookie and the form-protection cookie (issued when you sign in to your account), and two that remember your language and light/dark theme preference. Those last two travel unencrypted and scoped to the parent domain on purpose, so your choice is honoured across every Nexo tool; they carry no personal data. None of them is used for advertising or tracking, which is why you will not see a consent banner.',
            ],
            [
                'h' => 'While your session is open',
                'p' => 'The server\'s session record stores your IP address and browser while the session is alive, so it can be closed and abuse can be detected. It is deleted when you sign out or when it expires. This only happens if you have an account and signed in: visiting a public page creates no session at all.',
            ],
            [
                'h' => 'Reports',
                'p' => 'Anyone can report a page or a link without signing up. For a report we store the reason, the optional comment you write, which page or link it points at, and the same anonymous daily fingerprint, used only to avoid accepting the same report twice in one day. We ask reporters for no email and no name. The page owner sees the reason and the comment.',
            ],
            [
                'h' => 'Email',
                'p' => 'The only emails we send are account ones: email verification and password recovery. They go out through an external email provider, which necessarily processes the destination address and the message content in order to deliver them.',
            ],
            [
                'h' => 'Nothing external on the pages',
                'p' => 'No page loads third-party fonts, scripts, images or CDNs: everything is served from this domain and the site\'s content security policy blocks it explicitly. That means visiting your page tells nobody else that you visited it. There is an optional Nexo ecosystem counter, off by default, which only sends a "one visit to this tool" signal, identifying neither the visitor nor which page they went to.',
            ],
            [
                'h' => 'How long, and how it is deleted',
                'p' => 'The data lives as long as your account does. Deleting the account cascades: your page, your links, your social icons, the recorded clicks and the reports received are all deleted. When you change or remove your photo or banner, the previous file is deleted from the server.',
            ],
            [
                'h' => 'Your rights',
                'p' => 'You can see and edit your data from your own dashboard, and delete all of it by deleting the account. To request access, correction or deletion another way, write to whoever operates this instance using the contact link at the bottom of this page.',
            ],
            [
                'h' => 'Other instances',
                'p' => 'Nexo Links can be installed on any server. Each installation is independent, has its own key and is responsible for its own data: this policy covers this instance only.',
            ],
        ],
    ],

    'terms' => [
        'title' => 'Terms of use',
        'intro' => 'By using this instance of Nexo Links you accept the following. It is a free service, offered as is.',
        'sections' => [
            [
                'h' => 'What the service is',
                'p' => 'A tool for publishing a page with all your links at an address of your own, of the form /yourname, with click statistics, date-based link scheduling and visual customisation. We do not host your content: we host the links that point to it.',
            ],
            [
                'h' => 'Your account and your username',
                'p' => 'You need an account and a verified email to publish. You are responsible for keeping your password safe and for whatever is done from your account. Some usernames cannot be registered: the ones that collide with the application\'s own addresses and the ones that would be used to impersonate the site itself. Whoever operates this instance may reclaim a username being used to impersonate a person or a brand.',
            ],
            [
                'h' => 'Responsibility for your links',
                'p' => 'The content your links point at is your responsibility, not ours. For the safety of whoever clicks, only http, https, mailto and tel addresses are accepted: any other scheme (javascript:, data:, file: and the like) is rejected on save, because those are the ones used to run code in a visitor\'s browser.',
            ],
            [
                'h' => 'Misuse',
                'p' => 'A page may not be used for phishing, malware, scams, impersonation, spam or illegal content. Any visitor can report a page or a specific link from that page\'s report link, and whoever operates this instance can take the page down: it stops being available and its links stop redirecting.',
            ],
            [
                'h' => 'Availability',
                'p' => 'The service is offered with no availability guarantee. We do what is reasonable to keep it online, but there can be outages, and an address you shared may go unanswered during them.',
            ],
            [
                'h' => 'Limitation of liability',
                'p' => 'Whoever operates this instance is not liable for damages arising from use of the service, including links that stop working, statistics that are lost, or service interruptions.',
            ],
            [
                'h' => 'Free software',
                'p' => 'Nexo Links is distributed under the MIT licence: you can read the code, modify it and host your own instance. The software is provided without warranty, as that licence states.',
            ],
            [
                'h' => 'Changes',
                'p' => 'These terms may change. The date above shows the last update.',
            ],
        ],
    ],
];
