<?php
use Cosmic\App\Config;

$GLOBALS['language'] = array (
    'website' => array (
        /*     App/View/base.html     */
        'base' => array(
            'nav_home'              => 'Home',

            'nav_community'         => 'Community',
            'nav_news'              => 'Nieuws',
            'nav_jobs'              => 'Jobs',
            'nav_photos'            => 'Foto\'s',
            'nav_rarevalue'         => 'Ruilwaarde',
            'nav_staff'             => 'Staff',
            'nav_team'              => 'Teams',
            'nav_exchange'          => 'Marketplace',

            'nav_shop'              => 'winkel',
            'nav_buy_points'        => 'Bel-Credits kopen',
            'nav_buy_club'          =>  Config::site['shortname'] . ' Club kopen',
            'nav_purchasehistory'   => 'Aankoopgeschiedenis',
            'nav_changename'        =>  Config::site['shortname'] . 'naam veranderen',
            'nav_drawyourbadge'     => 'Maak je badge',
          
            'nav_highscores'        => 'Highscores',

            'nav_forum'             => 'Groepen',

            'nav_helptool'          => 'Help Tool',
            'nav_helptickets'       => 'Help Tickets',

            'nav_housekeeping'      => 'Housekeeping',

            'close'                 => 'Sluit',
            'cookies'               => 'maakt gebruik van eigen cookies en die van derden om zo een betere service te kunnen verlenen en zorgt er daarnaast voor dat de advertenties beter bij jouw voorkeuren aansluiten. Als je gebruik maakt van onze website ga je akkoord met ons cookie-beleid.',
            'read_more'             => 'Lees meer',
            'thanks_for_playing'    => 'Bedankt voor het spelen van',
            'made_with_love'        => 'is gemaakt met heel veel liefde',
            'credits'               => 'Met dank aan Raizer and Metus',
            'and_all'               => 'En alle',

            'login_name'            => 'naam',
            'login_password'        => 'Wachtwoord',
            'login_save_data'       => 'Onthoud mijn gegevens',
            'login_lost_password'   => 'Wachtwoord/naam kwijt?',

            'report_message'        => 'Rapporteer dit bericht',
            'report_certainty'      => 'Je staat op het punt om dit bericht te rapporteren. Weet je zeker dat je dit bericht wilt rapporteren?',
            'report_inappropriate'  => 'Ja, dit is ongepast!',

            'user_to'               => 'Naar',
            'user_profile'          => 'Mijn profiel',
            'user_settings'         => 'instellingen',
            'user_logout'           => 'Log uit',

            'header_slogan'         => 'Virtuele wereld voor jongeren!',
            'header_slogan2'        => 'Word lid van onze community en maak nieuwe vrienden',
            'header_login'          => 'Inloggen',
            'header_register'       => 'Meld je gratis aan!',
            'header_to'             => 'Naar',

            'footer_helptool'       => 'Help Tool',
            'footer_rules'          => 'De '. Config::site['shortname'] . ' Regels',
            'footer_terms'          => 'Algemene voorwaarden',
            'footer_privacy'        => 'Privacyverklaring',
            'footer_cookies'        => 'Cookie-beleid',
            'footer_guide'          => 'Gids voor ouders'
        ),

        /*     public/assets/js/web     */
        'javascript' => array(
            'web_customforms_markedfields'                  => 'Alle velden gemarkeerd met een * zijn verplicht.',
            'web_customforms_loadingform'                   => 'Formulier laden...',
            'web_customforms_next'                          => 'Volgende',
            'web_customforms_close'                         => 'Sluiten',
            'web_customforms_participation'                 => 'Bedankt voor uw deelname!',
            'web_customforms_sent'                          => 'Uw antwoorden zijn verzonden en zullen worden geanalyseerd door de persoon die dit formulier opstart.',
            'web_customforms_answer'                        => 'Uw antwoord',

            'web_dialog_cancel'                             => 'Annuleren',
            'web_dialog_validate'                           => 'Valideren',
            'web_dialog_confirm'                            => 'Bevestig uw keuze',

            'web_forum_first'                               => 'Eerste',
            'web_forum_previous'                            => 'Vorige',
            'web_forum_last'                                => 'Laatste',
            'web_forum_next'                                => 'Volgende',

            'web_hotel_backto'                              => 'Terug naar '. Config::site['shortname'] . ' Hotel',

            'web_fill_pincode'                              => 'Vul de pincode in die je hebt opgegeven tijdens het aanmaken van de extra beveiliging op jouw account. Nou, ik ben deze vergeten? Neem dan contact op via de '. Config::site['shortname'] . ' Help Tool',
            'web_twostep'                                   => 'Twee-staps autorisatie!',
            'web_login'                                     => 'Je moet ingelogd zijn om dit bericht te rapporteren!',
            'web_loggedout'                                 => 'Uitgelogd :(',

            'web_notifications_success'                     => 'Gelukt!',
            'web_notifications_error'                       => 'Error!',
            'web_notifications_info'                        => 'Informatie!',

            'web_page_article_login'                        => 'Je dient ingelogd te zijn om een reactie te plaatsen!',

            'web_page_community_photos_login'               => 'Je moet ingelogd zijn om foto\'s te kunnen liken!',
            'web_page_community_photos_loggedout'           => 'Uitgelogd :(',

            'web_page_forum_change'                         => 'Aanpassen',
            'web_page_forum_cancel'                         => 'Annuleren',
            'web_page_forum_oops'                           => 'Oeps...',
            'web_page_forum_topic_closed'                   => 'Dit topic is gesloten en er kan niet meer gereageerd worden.',
            'web_page_forum_login_toreact'                  => 'Om te kunnen reageren dien je ingelogd te zijn!',
            'web_page_forum_login_tolike'                   => 'Je moet ingelogd zijn om deze post te kunnen liken!',
            'web_page_forum_loggedout'                      => 'Uitgelold :(',

            'web_page_profile_login'                        => 'Je moet ingelogd zijn om foto\'s te kunnen liken!',
            'web_page_profile_loggedout'                    => 'Uitgelogd :(',

            'web_page_settings_namechange_request'          => 'Aanvragen',
            'web_page_settings_namechange_not_available'    => 'Niet beschikbaar',
            'web_page_settings_namechange_choose_name'      => 'Kies '. Config::site['shortname'] . 'naam',

            'web_page_settings_verification_oops'           => 'Oeps...',
            'web_page_settings_verification_fill_password'  => 'Vul je wachtwoord in!',
            'web_page_settings_verification_2fa_on'         => 'Op dit moment staat Google Authenticatie ingesteld op jouw account. Om een ander verificatie middel te gebruiken dien je eerst je oude verificatie te verwijderen!',
            'web_page_settings_verification_2fa_secretkey'  => 'Heb je de QR-code gescand op je mobiel? Vul dan de secretkey in om je account te bevestigen!',
            'web_page_settings_verification_2fa_authcode'   => 'Authenticatie code',
            'web_page_settings_verification_pincode_on'     => 'Op dit moment heb je een pincode ingesteld op jouw account. Om een ander verificatie middel te gebruiken dien je eerst je oude verificatie te verwijderen!',
            'web_page_settings_verification_2fa_off'        => 'Om de Google Authenticatie uit te schakelen vragen wij je om de secretcode uit de generator in te vullen.',
            'web_page_settings_verification_pincode_off'    => 'Om de pincode authenticatie uit te schakelen vragen wij je om je pincode in te vullen.',
            'web_page_settings_verification_pincode'        => 'Pincode code',
            'web_page_settings_verification_switch'         => 'Selecteer de switch button om een authenticatie methode in te schakelen!',

            'web_page_shop_offers_neosurf_name'             => 'Neosurf',
            'web_page_shop_offers_neosurf_description'      => 'Betaal gemakkelijk met Paypal en je Bel-Credits worden direct opgewaardeerd.',
            'web_page_shop_offers_neosurf_dialog'           => 'Vul je onderstaande Paypal e-mailadres in om door te gaan.',
            'web_page_shop_offers_paypal_name'              => 'Paypal',
            'web_page_shop_offers_paypal_description'       => 'Betaal gemakkelijk met Paypal en je Bel-Credits worden direct opgewaardeerd.',
            'web_page_shop_offers_paypal_dialog'            => 'Vul je onderstaande Paypal e-mailadres in om door te gaan.',
            'web_page_shop_offers_sms_name'                 => 'SMS',
            'web_page_shop_offers_sms_description'          => 'Stuur een code per sms en ontvang een Bel-Credits code.',
            'web_page_shop_offers_sms_dialog'               => 'Stuur de onderstaande code in een SMS om een Bel-Credits code te krijgen.',
            'web_page_shop_offers_audiotel_name'            => 'Audiotel',
            'web_page_shop_offers_audiotel_description'     => 'Bel een of meerdere keren een nummer om een Bel-Credits code te krijgen.',
            'web_page_shop_offers_audiotel_dialog'          => 'Bel naar het onderstaande nummer om een Bel-Credits code te krijgen.',
            'web_page_shop_offers_pay_with'                 => 'Betaal via',
            'web_page_shop_offers_points_for'               => 'Bel-Credits voor',
            'web_page_shop_offers_get_code'                 => 'Krijg een Bel-Credits code',
            'web_page_shop_offers_fill_code'                => 'Vul je Bel-Credits code in',
            'web_page_shop_offers_fill_code_desc'           => 'Vul hieronder je Bel-Credits code in om je Bel-Credits te ontvangen.',
            'web_page_shop_offers_submit'                   => 'Bevestigen',
            'web_page_shop_offers_success'                  => 'Aankoop gelukt!',
            'web_page_shop_offers_received'                 => 'Bedankt voor je aankoop. Je hebt',
            'web_page_shop_offers_received2'                => 'Bel-Credits ontvangen.',
            'web_page_shop_offers_close'                    => 'Sluit',
            'web_page_shop_offers_failed'                   => 'Aankoop mislukt!',
            'web_page_shop_offers_failed_desc'              => 'De aankoop is mislukt. Probeer het nog eens of neem contact op via de Help Tool.',
            'web_page_shop_offers_back'                     => 'Terug',
            'web_page_shop_offers_no_card'                  => 'Als je geen Neosurf-prepaidkaart hebt, kun je de',
            'web_page_shop_offers_no_card2'                 => 'verkooppunten zien',
            'web_page_shop_offers_to'                       => 'naar',
            'web_page_shop_offers_buy_code'                 => 'Koop toegangscode',
            'web_page_hotel_loading'                        => 'is aan het opstarten...',
            'web_page_hotel_sometinhg_wrong_1'              => 'Oeps, er is iets misgegaan.',
            'web_page_hotel_sometinhg_wrong_2'              => 'Herlaad de pagina',
            'web_page_hotel_sometinhg_wrong_3'              => 'Of neem contact met ons op',
            'web_page_hotel_welcome_at'                     => 'Welkom op',
            'web_page_hotel_soon'                           => 'Dit ziet er goed uit...',
            'web_hotel_active_flash_1'                      => 'Je bent bijna op '. Config::site['shortname'] . '!',
            'web_hotel_active_flash_2'                      => 'Klik hier',
            'web_hotel_active_flash_3'                      => 'en dan links boven op "Toestaan" om Flash Player aan te zetten.'
        ),

        /*     App/View/Community     */
        'article' => array (
            'reactions'              => 'Reacties',
            'reactions_empty'        => 'Er zijn nog geen reacties.',
            'reactions_fill'         => 'Typ hier je bericht...',
            'reactions_post'         => 'Plaats',
            'latest_news'            => 'Laatste nieuws',
            'reaction_hidden_yes'    => 'Nieuwsreactie is verborgen gemaakt!',
            'reaction_hidden_no'     => 'Nieuwsreactie is zcithbaar gemaakt!',
            'forbidden_words'        => 'Your message contains forbidden words!',
        ),

		'community_rares' => array (
		'desc'        => ' Meest waardevolle meubels',
		'last_clickhere' => 'Klik hier',
		'last_edited'   => 'Laatst bewerkt: ',
		'last_editor'   => 'Laatst bewerkt door: ',
		'last_rares'   => 'Laatste 10 meubels',
		'none_rare_found_desc'   => 'Misschien ben je hier naar op zoek? ',
		'none_rare_found_last'   => 'Laatste 10 rares',
		'none_rare_found_title'   => 'Geen rares gevonden',
		'pages_notfound'   => 'Geen pagina beschikbaar',
        'rares_pages'   => 'Paginas',
		'search'   => 'Zoek',
		'title'       => Config::site['shortname'] . ' Rares',
        'units'   => 'Units'),
        
        'forum' => array (
          /*  Forum/index.html  */
            'index_subject'             => 'Onderwerp',
            'index_topics'              => 'Topics',
            'index_latest_topic'        => 'Laatste topic',
            'index_empty'               => 'Geen topics',
            'index_latest_activities'   => 'Laatste activiteiten',
            'index_by'                  => 'door',

          /*  Forum/category.html  */
            'category_new_topic'        => 'Nieuw topic',
            'category_back'             => 'Terug',
            'category_topics'           => 'Topics',
            'category_posts'            => 'Posts',
            'category_latest_reacts'    => 'Laatste reacties',
            'category_topic_by'         => 'Door',
            'category_no_reacts'        => 'Geen reacties',
            'category_latest_react_by'  => 'Laatste reactie door',
            'category_create_topic'     => 'Maak nieuw topic',
            'category_subject'          => 'Onderwerp',
            'category_description'      => 'Beschrijving',
            'category_create_button'    => 'Maak topic',
            'category_or'               => 'of',
            'category_cancel'           => 'annuleer',

          /*  Forum/topic.html  */
            'topic_react'               => 'Reageren',
            'topic_close'               => 'Sluiten',
            'topic_reopen'              => 'Heropenen',
            'topic_since'               => 'Sinds:',
            'topic_posts'               => 'Posts:',
            'topic_topic'               => 'Topic:',
            'topic_reaction'            => 'Reactie:',
            'topic_closed'              => 'Let op! Dit topic is gesloten, ben je het hier niet mee eens? Neem dan contact op via de',
            'topic_helptool'            => 'helptool',
            'topic_and'                 => 'en',
            'topic_likes_1'             => 'anderen vinden dit leuk!',
            'topic_likes_2'             => 'vindt dit leuk!',
            'topic_likes_3'             => 'vinden dit leuk!'
        ),
        'community_photos' => array (
            'by'          => 'door',
            'photos_by'   => 'Foto\'s van',
            'photos_desc' => 'Bekijk de leukste momenten in ons Hotel, genomen door',
            'load_more'   => 'Bekijk meer foto\'s'
        ),
        'community_staff' => array (
            'title'       => 'Hoe word ik '. Config::site['shortname'] . ' Staff?',
            'desc'        => 'Zij vertegenwoordigen het officiele team dat verantwoordelijk is voor de goede werking van het hotel.',
            'content_1'   => 'Natuurlijk droomt iedereen wel van zo\'n plaats als '. Config::site['shortname'] . ' Stafflid, maar helaas is dit niet voor iedereen weggelegd. Om '. Config::site['shortname'] . ' Staff te kunnen worden moet je solliciteren.',
            'content_2'   => 'Dit kan alleen op momenten wanneer wij vacatures hebben, wanneer we dit hebben, wordt dit vermeldt in het nieuws.'
        ),
        'community_value' => array (
            'title_header'      => 'Ruilwaardes',
            'decs_header'       => 'Alle exclusieve furniture met de waarde hoger dan het type credit vindt je hier!',
            'furni_name'        => 'Meubelnaam',
            'furni_type'        => 'Type',
            'furni_costs'       => 'Waarde',
            'furni_amount'      => 'Aantal',
            'furni_rate'        => 'Koers',
            'looking_for'       => 'Ik ben opzoek naar...',
            'seller'            => 'Verkoper',
            'price'             => 'Prijs',
            'nav_my'            => 'Mijn markplaats',
            'nav_shop'          => 'Marktplaats',
            'nav_catalogue'     => 'Catalogus',
            'marketplace_desc'  => 'Leden van ' . Config::site['shortname'] . ' proberen hier hun items te verkopen op de online marktplaats. Ben jij opzoek naar item waar je al lang naar opzoek was, wellicht vind je hem hier!'
        ),

        /*     App/View/Games     */
        'games_ranking' => array (
            'username' => 'naam'
        ),

        /*     App/View/Help     */
        'help' => array (
          /*  Help/help.html  */
            'help_title'                => 'FAQ',
            'help_label'                => 'Vind hier alle antwoorden over jou vragen!',
            'help_other_questions'      => 'Andere vragen',
            'help_content_1'            => 'Het antwoord op jouw vraag niet gevonden? Aarzel dan niet om contact op te nemen met onze klantenservice zodat we meer informatie kunnen geven.',
            'help_contact'              => 'Contact opnemen',
            'title'                     => 'Help Tool',
            'desc'                      => 'Je kunt hier op zoek naar antwoorden op je vragen. Vind je het antwoord op jouw vraag niet, dien dan een hulpverzoek in.',

          /*  Help/request.html  */
            'request_on'                => 'Op:',
            'request_ticket_count'      => 'Aantal tickets:',
            'request_react_on'          => 'Reactie op:',
            'request_react'             => 'Reageren',
            'request_description'       => 'Beschrijving',
            'request_react_on_ticket'   => 'Reageer op ticket',
            'request_contact'           => 'Neem contact op met '. Config::site['shortname'],
            'request_contact_help'      => 'U kunt contact met ons opnemen door een nieuw ticket te openen.',
            'request_new_ticket'        => 'Nieuw ticket',
            'request_subject'           => 'Onderwerp',
            'request_type'              => 'Type',
            'request_status'            => 'Ticket geopend',
            'request_in_treatment'      => 'In behandeling',
            'request_open'              => 'Open',
            'request_closed'            => 'Gesloten'
        ),
        'help_new' => array (
            'title'         => 'Mijn ticket',
            'subject'       => 'Onderwerp',
            'description'   => 'Beschrijving',
            'open_ticket'   => 'Open een ticket'
        ),

        /*     App/View/Home     */
        'home' => array (
            'to'                      => 'Naar',
            'friends_online'          => 'Online vrienden',
            'now_in'                  => 'Nu in',
            'latest_news'             => 'Laatste nieuws',
            'latest_facts'            => 'De laatste weetjes binnen '. Config::site['shortname'] . '!',
            'popular_rooms'           => 'Populaire kamers',
            'popular_rooms_label'     => 'Weet welke kamers trending zijn binnen '. Config::site['shortname'] . '!',
            'popular_no_rooms'        => 'Er is nog niemand op ons Hotel!',
            'goto_room'               => 'Naar deze kamer',
            'popular_groups'          => 'Populaire groepen',
            'popular_groups_label'    => 'Bij wie wil jij je aansluiten?',
            'popular_no_groups'       => 'Er zijn nog geen groepen aangemaakt!',
            'load_news'               => 'Meer nieuws laden',
            'user_of_the_week'        =>  Config::site['shortname'] . ' van de week',
            'user_of_the_week_label'  => 'Speler of de week'
        ),
        'lost' => array (
            'page_not_found'          => 'Pagina niet gevonden!',
            'page_content_1'          => 'Sorry, de pagina die je probeert te vinden bestaat niet.',
            'page_content_2'          => 'Controleer opnieuw of je de juiste url hebt. Kom je dan weer hier terecht (welkom terug!). Ga dan met de \'Back\' knop terug naar waar je vandaan kwam.',
            'sidebar_title'           => 'Zocht je misschien...',
            'sidebar_stats'           => 'De home van een van je vrienden?',
            'sidebar_stats_label_1'   => 'Misschien staat hij/zij bij de',
            'sidebar_stats_label_2'   => 'Highscores',
            'sidebar_rooms'           => 'Populaire kamers?',
            'sidebar_rooms_label_1'   => 'Blader eens door de',
            'sidebar_rooms_label_2'   => 'Navigator',
            'sidebar_else'            => 'Ik ben mijn slippers kwijt!',
            'sidebar_else_label'      => 'Dan moet je toch echt beter zoeken! :-)'
        ),
        'profile' => array (
            'overlay_search'        => 'Wie zoek je?',
            'since'                 => 'sinds',
            'currently'             => 'Momenteel',
            'never_online'          => 'Nog niet online geweest',
            'last_visit'            => 'Laatste bezoek',
            'guestbook_title'       => 'Gastenboek',
            'guestbook_label'       => 'Laat jij iets achter?',
            'guestbook_input'       => 'Wat ben je aan het doen,',
            'guestbook_input_1'     => 'Wat wil je',
            'guestbook_input_2'     => 'laten weten?',
            'guestbook_load_more'   => 'Meer berichten laden',
            'badges_title'          => 'Badges',
            'badges_label'          => 'Willekeurige badges die ik kan dragen',
            'badges_empty'          => 'Heeft nog geen badges ingesteld',
            'friends_title'         => 'Vrienden',
            'friends_label'         => 'Willekeurige vrienden in mijn lijst',
            'friends_empty'         => 'Heeft nog geen vrienden gemaakt',
            'groups_title'          => 'Groepen',
            'groups_label'          => 'Zie waar ik van houdt!',
            'groups_empty'          => 'Heeft zich nog niet aangesloten bij een groep',
            'rooms_title'           => 'Kamers',
            'rooms_label'           => 'Mijn laatst aangemaakte kamers',
            'rooms_empty'           => 'Heeft nog geen kamers aangemaakt',
            'photos_title'          => 'Foto\'s',
            'photos_label'          => 'Kom jij met mij op de foto?',
            'photos_empty'          => 'Heeft nog geen foto\'s gemaakt'
        ),
        'registration' => array (
            'title'                 => 'Vul je gegevens in!',
            'email'                 => 'E-mailadres',
            'email_fill'            => 'Vul hier je e-mailadres in...',
            'email_help'            => 'We zullen deze informatie nodig hebben om je account te herstellen voor het geval je de toegang verliest.',
            'password'              => 'Wachtwoord',
            'password_fill'         => 'Wachtwoord...',
            'password_repeat'       => 'Wachtwoord nogmaals',
            'password_repeat_fill'  => 'Herhaal wachtwoord...',
            'password_help_1'       => 'Je wachtwoord moet minimaal 6 tekens lang zijn en letters en cijfers bevatten.',
            'password_help_2'       => 'Zorg dat je wachtwoord anders is dan op andere website\'s!',
            'birthdate'             => 'Geboortedatum',
            'day'                   => 'Dag',
            'month'                 => 'Maand',
            'year'                  => 'Jaar',
            'birthdate_help'        => 'We zullen deze informatie nodig hebben om je account te herstellen voor het geval je de toegang verliest.',
            'found'                 => 'Hoe heb je '. Config::site['shortname'] . ' Hotel gevonden?',
            'found_choose'          => 'Maak een keuze...',
            'found_choose_1'        => 'Google',
            'found_choose_2'        => 'Door een vriend(in)',
            'found_choose_3'        => 'Door een ander spel',
            'found_choose_4'        => 'Door Facebook',
            'found_choose_5'        => 'Anders',
            'create_user'           => 'Maak je '. Config::site['shortname'] . '!',
            'username'              =>  Config::site['shortname'] . 'naam',
            'username_fill'         =>  Config::site['shortname'] . 'naam...',
            'username_help'         => 'Jouw unieke naam in '. Config::site['shortname'] . ' Hotel.',
            'sex'                   => 'Geslacht',
            'male'                  => 'Jongen',
            'female'                => 'Meisje',
            'register'              => 'Registreer'
        ),

        /*     App/View/Jobs     */
        'apply' => array (
            'title'               => 'Reageer op de vacture',
            'content_1'           => 'Bedankt voor je interesse in '. Config::site['shortname'] . ' Hotel en voor het reageren op de vacature.',
            'content_2'           => 'Probeer de vragenlijst zo nauwkeurig mogelijk te beantwoorden.',
            'description'         => 'Taakomschrijving',
            'question_name'       => 'Hoe heet je?',
            'question_age'        => 'Hoe oud ben je?',
            'question_why'        => 'Waarom denk je dat je geschikt zou kunnen zijn?',
            'question_time'       => 'Hoeveel uur ben je online?',
            'question_time_help'  => 'Geef ons door hoeveel uur je per dag online bent op '. Config::site['shortname'] . ' Hotel.',
            'monday'              => 'Maandag',
            'tuesday'             => 'Dinsdag',
            'wednesday'           => 'Woensdag',
            'thursday'            => 'Donderdag',
            'friday'              => 'Vrijdag',
            'saturday'            => 'Zaterdag',
            'sunday'              => 'Zondag',
            'time_to_time'        => 'van X tot Y uur',
            'send'                => 'Stuur mijn sollicitatie'
        ),
        'jobs' => array (
            'title'                   => 'Lijst vacatures',
            'applications'            => 'Mijn sollicitaties',
            'available_applications'  => 'Beschikbare vacatures',
            'buildteam'               => 'Bouwteam',
            'buildteam_desc'          => 'Ze zijn verantwoordelijk voor het bouwen van (event/officiele) kamers.',
            'react'                   => 'Reageren'
        ),

        /*     App/View/Password     */
        'password_claim' => array (
            'title'                 => 'Wachtwoord vergeten?',
            'content_1'             => 'Vul hieronder je '. Config::site['shortname'] . 'naam en e-mailadres in en we sturen je een link per e-mail om je wachtwoord te veranderen.',
            'content_2'             => 'Doe dit niet als iemand je vraagt om dit te doen!',
            'username'              =>  Config::site['shortname'] . 'naam',
            'email'                 => 'E-mailadres',
            'send'                  => 'Verstuur e-mail',
            'wrong_page'            => 'Vals alarm!',
            'wrong_page_content_1'  => 'Als je je wachtwoord wel weer weet - of hier per ongeluk bent beland - kun je de link hieronder gebruiken om terug te gaan naar de homepage.',
            'back_to_home'          => 'Terug naar de homepage'
        ),
        'password_reset' => array (
            'title'                     => 'Wachtwoord veranderen',
            'new_password'              => 'Nieuw wachtwoord',
            'new_password_fill'         => 'Vul je nieuwe wachtwoord in...',
            'new_password_repeat_fill'  => 'Vul nogmaals je wachtwoord in...',
            'change_password'           => 'Wijzig wachtwoord'
        ),

        /*     App/View/Settings     */
        'settings_panel' => array (
            'preferences'    => 'Mijn voorkeuren',
            'password'       => 'Wachtwoord veranderen',
            'verification'   => 'Verificatie instellen',
            'email'          => 'E-mailadres veranderen',
            'namechange'     =>  Config::site['shortname'] . 'naam veranderen',
            'shop_history'   => 'Aankoopgeschiedenis'
        ),
        'settings_email' => array (
            'title'           => 'E-mail veranderen',
            'email_title'     => 'E-mailadres',
            'email_label'     => 'Je e-mailadres is nodig om je account te herstellen voor het geval je de toegang verliest.',
            'password_title'  => 'Huidig wachtwoord',
            'fill_password'   => 'Vul je huidige wachtwoord in...',
            'save'            => 'Opslaan'
        ),
        'settings_namechange' => array (
            'title'           =>  Config::site['shortname'] . 'naam veranderen',
            'help_1'          => 'Wil jij je Asteroidnaam veranderen? Dat kan! Dit kost',
            'help_2'          => 'en zullen meteen na je verzoek afgeschreven worden. Wanneer je naam eenmaal is veranderd kunnen wij dit niet meer terugdraaien! Zorg dus dat je goed na denkt over je besluit!',
            'fill_username'   => 'Asteroidnaam...',
            'request'         => 'Aanvragen'
        ),
        'settings_password' => array (
            'title'                     => 'Wachtwoord veranderen',
            'password_title'            => 'Huidig wachtwoord',
            'fill_password'             => 'Vul je huidig wachtwoord in...',
            'newpassword_title'         => 'Nieuw wachtwoord',
            'fill_newpassword'          => 'Vul hier je nieuwe wachtwoord in...',
            'fill_newpassword_repeat'   => 'Herhaal je nieuwe wachtwoord...',
            'help'                      => 'Je wachtwoord moet minimaal 6 tekens lang zijn en letters en cijfers bevatten.',
            'save'                      => 'Opslaan'
        ),
        'settings_preferences' => array (
            'title'               => 'Mijn voorkeuren',
            'follow_title'        => 'Volgfunctie - wie mogen je volgen?' ,
            'follow_label'        => 'Ik wil dat '. Config::site['shortname'] . '\'s mij niet mogen volgen',
            'friends_title'       => 'Vriendenverzoeken',
            'friends_label'       => 'Vriendschap verzoeken toestaan?',
            'room_title'          => 'Kamer uitgenodigingen',
            'room_label'          => 'Ik wil niet uitgenodigd worden voor kamers',
            'hotelalerts_title'   => 'Hotel alerts',
            'hotelalerts_label'   => 'Ik wil geen hotelmeldingen ontvangen',
            'chat_title'          => 'Chat instellingen',
            'chat_label'          => 'Ik wil gebruik maken van de oude chat'
        ),
        'settings_verification' => array (
            'title'                 => 'Beveilig jouw account',
            'help'                  => 'Deze controle verhoogt de beveiliging van uw account. Wanneer u inlogt, moet u, afhankelijk van uw voorkeuren, de beveiligingsvragen beantwoorden die u hebt gedefinieerd of een code invoeren die door uw toepassing is gegenereerd.',
            'password_title'        => 'Vul je wachtwoord in',
            'auth_title'            => 'Twee-staps verificatie',
            'auth_label'            => 'Beveilig je account met twee-staps verificatie',
            'method_title'          => 'Verificatie methode',
            'method_choose'         => 'Kies jouw verificatie middel...',
            'method_pincode'        => 'Ik wil een pincode instellen',
            'method_auth_app'       => 'Ik wil Google 2FA gebruiken',
            'pincode_title'         => 'Pincode beveiliging',
            'pincode_label'         => 'Zet een pincode op je account als extra beveiliging, hiermee zorg je voor een betere beveiliging van jouw account tegen hackers.',
            'fill_pincode'          => 'Vul je pincode in',
            'generate_auth'         => 'Code generen door 2FA',
            'generate_auth_label'   => 'Deze methode is het meest betrouwbaar. Het koppelt uw '. Config::site['shortname'] . '-account aan een authenticatietoepassing (Google Authenticator) op uw telefoon. Wanneer u zich aanmeldt, hoeft u alleen de code in te voeren die door uw app is gegenereerd.',
            'link_account'          => 'Koppel je account',
            'link_account_label'    => 'Om uw account te koppelen, moet u deze QR-code eenvoudig met uw toepassing scannen en vervolgens op opslaan klikken om deze wijziging te valideren.',
            'save'                  => 'Opslaan'
        ),

        /*     App/View/Shop     */
        'shop_club' => array (
            'club_benefits'       => 'Club voordelen',
            'club_buy'            => 'Koop '. Config::site['shortname'] . ' Club',
            'unlimited'           => 'Onbeperkt',
            'more_information'    => 'Meer informatie',
            'content_1'           => 'Heb je een vraag of probleem met een aankoop?',
            'content_2'           => 'Aarzel niet om contact op te nemen met de klantenservice via de',
            'help_tool'           =>  Config::site['shortname'] . ' Help Tool',
            'random_club_users'   => 'Willekeurige '. Config::site['shortname'] . ' Club leden',
            'desc'                => 'Hier kun je club kopen voor echt geld. Met club heb je voordelen en kun je exclusieve items kopen.'
        ),
        'shop_history' => array (
            'buy_history'         => 'Aankoopgeschiedenis',
            'product'             => 'Product',
            'date'                => 'Datum',
            'buy_history_empty'   => 'Je hebt nog geen aankoopgeschiedenis.',
            'buy_club'            => 'Koop '. Config::site['shortname'] . ' Club',
            'content_1'           => 'Heb je een vraag of probleem met een aankoop?',
            'content_2'           => 'Aarzel niet om contact op te nemen met de klantenservice via de',
            'help_tool'           =>  Config::site['shortname'] . ' Help Tool',
            'title'               => 'Mijn aankoopgeschiedenis',
            'desc'                => 'Vind hier alle aankopen die je hebt gedaan in',
            'title_draw'          => 'Maak je badge',
            'draw_desc'     => 'Creeër je badge en koop hem voor punten!'
        ),
        'shop_offers' => array (
            'back'              => 'Terug',
            'buymethods'        => 'Betaalmethodes',
            'for'               => 'voor',
            'or_lower'          => 'of lager',
            'loading_methods'   => 'De betaalmethodes worden geladen...'
        ),
        'shop' => array (
            'title'             => 'Kies een product',
            'country'           => 'Land:',
            'netherlands'       => 'Nederland',
            'belgium'           => 'België',
            'super_rare'        => 'Super zeldzaam',
            'more_information'  => 'Meer informatie',
            'content_1'         => 'Heb je een vraag of probleem met een aankoop?',
            'content_2'         => 'Aarzel niet om contact op te nemen met de klantenservice via de',
            'help_tool'         =>  Config::site['shortname'] . ' Help Tool',
            'not_logged'        => 'Oeps! Je bent niet ingelogd.',
            'have_to_login'     => 'Om de '. Config::site['shortname'] . ' Winkel te bezoeken moet je ingelogd zijn.',
            'click_here'        => 'Klik hier',
            'to_login'          => 'om in te loggen.',
            'desc'              => 'Hier kun je Bel-Credits kopen voor echt geld. Met Bel-Credits kun je exclusieve items kopen.',
            'store'             => 'Winkel',
            'get'               => 'Je krijgt'
        ),
        'games_ranking' => array(
            'title'             => 'Highscores',
            'desc'              => 'Bekijk hier onze spelers met de meeste punten of scores!'
        )
    ),
    'core' => array (
        'belcredits' => 'Bel-Credits',
        'hotelapi' => array (
            'disabled' => 'Kan vezoek niet verwerken omdat de hotelapi staat uitgeschakeld!'
        ),
        'dialog' => array (
            'logged_in'             => 'Oeps om deze pagina te bezoeken dien je ingelogd te zijn!',
            'not_logged_in'         => 'Om deze pagina te bezoeken dien je niet ingelogd te zijn!'
        ),
        'notification' => array (
            'message_placed'        => 'Je bericht is geplaatst!',
            'message_deleted'       => 'Je bericht is verwijderd!',
            'invisible'             => 'Dit is onzichtbaar gemaakt!',
            'profile_invisible'     => 'Deze '. Config::site['shortname'] . ' heeft zijn/haar profiel onzichtbaar gemaakt.',
            'profile_notfound'      => 'Helaas.. we hebben de '. Config::site['shortname'] . ' niet kunnen vinden!',
            'no_permissions'        => 'Je hebt geen toestemming.',
            'already_liked'         => 'Je vindt dit al leuk!',
            'liked'                 => 'Je vindt dit leuk!',
            'banned_1'              => 'Je bent verbannen voor het overtreden van de '. Config::site['shortname'] . ' Regels:',
            'banned_2'              => 'Je ban verloopt over:',
            'something_wrong'       => 'Er is iets misgegaan, probeer het nogmaals.',
            'room_not_exists'       => 'Deze kamer bestaat niet!',
            'staff_received'        => 'Bedankt! De '. Config::site['shortname'] . ' Staff heeft dit ontvangen!',
            'not_enough_belcredits' => 'Je hebt niet genoeg belcredits.',
			'not_enough_points'     => 'Je hebt helaas geen genoeg Diamanten!',
            'topic_closed'          => 'Je kunt niet reageren op een topic dat is gesloten!',
            'post_not_allowed'      => 'Je hebt geen rechten om te posten!',
            'draw_badge_uploaded'   => 'Je badge is ingediend en is klaar voor beoordeling!'
        ),
        'pattern' => array (
            'can_be'                => 'mag maximaal',
            'must_be'               => 'moet minimaal',
            'characters_long'       => 'karakters lang zijn.',
            'invalid'               => 'voldoet niet aan de eisen!',
            'invalid_characters'    => 'bevat ongeldige karakters!',
            'is_required'           => 'Vul alle velden in!',
            'not_same'              => 'komt niet overeen',
            'captcha'               => 'Recaptcha is foutief ingevoerd!',
            'numeric'               => 'moet numeriek zijn!',
            'email'                 => 'is niet geldig!'
        ),
        'title' => array (
            'home'              => 'Maak vrienden, speel games, maak kamers en val op!',
            'lost'              => 'Pagina niet gevonden!',
            'registration'      => 'Meld je gratis aan!',
            'hotel'             => 'Hotel',

            'password' => array (
                'claim'    => 'Wachtwoord kwijt?',
                'reset'    => 'Wachtwoord veranderen',
            ),
            'settings' => array (
                'index'         => 'Mijn voorkeuren',
                'password'      => 'Wachtwoord veranderen',
                'email'         => 'E-mail veranderen',
                'namechange'    =>  Config::site['shortname'] . 'naam veranderen'
            ),
            'community' => array (
                'index'     => 'Community',
                'photos'    => 'Foto\'s',
                'staff'     =>  Config::site['shortname'] . ' Staff',
                'team'      =>  Config::site['shortname'] . ' Team',
                'fansites'  => 'Fansites',
                'value'     => 'Ruilwaarde',
                'forum'     => 'Ons Forum'
            ),
            'games' => array (
                'ranking'   => 'Highscores'
            ),
            'shop' => array (
                'index'     =>  Config::site['shortname'] . ' Winkel',
                'history'   => 'Aankoopgeschiedenis',
                'club'      =>  Config::site['shortname'] . ' Club'
            ),
            'help' => array (
                'index'     => 'Help Tool',
                'requests'  => 'Help Tickets',
                'new'       => 'Help Ticket openen'
            ),
            'jobs' => array (
                'index'     =>  Config::site['shortname'] . ' Vacatures',
                'apply'     => 'Reageren op vacature'
            )
        )
    ),
    'asn' => array(
        'login'                     => 'Inloggen met door jouw gekozen VPN is niet toegestaan!',
        'registered'                => 'Registeren met een verbannen VPN is niet toegestaan!'
    ),
    'login' => array (
        'invalid_password'          => 'Onjuist wachtwoord.',
        'invalid_pincode'           => 'Deze pincode komt niet overeen met die van deze '. Config::site['shortname'] . '!',
        'fill_in_pincode'           => 'Vul nu je pincode in om toegang te krijgen tot jouw account!'
    ),
    'register' => array (
        'username_invalid'          =>  Config::site['shortname'] . 'naam is in strijd met de '. Config::site['shortname'] . ' Regels.',
        'username_exists'           =>  Config::site['shortname'] . 'naam is al in gebruik :-(',
        'email_exists'              =>  'This e-mail address is already in use :-(',
        'too_many_accounts'         =>  'Er zijn teveel accounts geregistreerd op dit ip :-('
    ),
    'claim' => array (
        'invalid_email'             => 'Dit e-mailadres komt niet overeen met die van deze '. Config::site['shortname'] . ' ID.',
        'invalid_link'              => 'Deze link is verlopen. Vraag opnieuw je wachtwoord aan om je wachtwoord te veranderen.',
        'send_link'                 => 'We hebben zojuist een e-mail naar je gestuurd! Niks ontvangen? Controleer dan de map met ongewenste e-mail.',
        'password_changed'          => 'Je wachtwoord is veranderd. Je kunt nu weer inloggen!',

        'email'  => array (
            'title'                 => 'Verander je wachtwoord.'
        )
    ),
    'settings' => array (
        'email_saved'               => 'Je e-mailadres is veranderd.',
        'pincode_saved'             => 'Je pincode is opgeslagen, je zult opnieuw moeten inloggen. Tot zo! :)',
        'password_saved'            => 'Je wachtwoord is veranderd. Je zult nu opnieuw moeten inloggen. Tot zo! :)',
        'preferences_saved'         => 'Je voorkeuren zijn opgeslagen!',
        'current_password_invalid'  => 'Huidig wachtwoord komt niet overeen met die van je '. Config::site['shortname'] . ' ID.',
        'choose_new_username'       => 'Vul een nieuwe '. Config::site['shortname'] . 'naam in.',
        'choose_new_pincode'        => 'Vul een nieuwe pincode in.',
        'user_is_active'            => 'Deze '. Config::site['shortname'] . ' is mogelijk nog actief!',
        'user_not_exists'           => 'Deze '. Config::site['shortname'] . 'naam is beschikbaar en bestaat nog niet!',
        'name_change_saved'         => 'Je naam is gewijzigd! En er zijn 50 Bel-Credits afgeschreven.',
        'invalid_secretcode'        => 'Google Authenticatie secretcode is onjuist.',
        'enabled_secretcode'        => 'Authenticatie methode ingesteld! Je zult opnieuw moeten inloggen.. tot zo!',
        'disabled_secretcode'       => 'Authenticatie methode uitgeschakeld!'
    ),
    'rcon' => array (
        'exception'                 => 'RCON kan niet worden uitgevoerd omdat het hotel niet online is!'
    ),
    'shop' => array (
        'offers' => array (
            'invalid_transaction'   => 'Transactie kon niet verwerkt worden!',
            'invalid_code'          => 'De door jouw ingevulde code is niet correct.',
            'success_1'             => 'Bedankt voor je aankoop! Je hebt',
            'success_2'             => 'Bel-Credits ontvangen.'
        ),
        'club' => array (
            'already_vip'           => 'Je bent al onbeperkt lid van de VIP Club.',
            'purchase_success'      => 'Jeuj! Je hebt een levenslange VIP-Club gekocht!'

        ),
        'marketplace' => array(
            'expired'               => 'Item die je probeert te kopen is niet meer te koop!',
            'purchased'             => 'Item is met succes gekocht en is toegevoegd aan je inventory!',
            'regards'               => 'Je gekochte item is gearriveerd! Met vriendelijke groet, ' . Config::site['shortname']
        ),
    ),
    'help' => array (
        'ticket_created'            => 'Jouw Help Ticket is aangemaakt. Bekijk je Help Tickets om het hulpverzoek te bekijken.',
        'ticket_received'           => 'Een '. Config::site['shortname'] . ' Staff heeft gereageerd op je Help Tool ticket. Bezoek de Help Tool om de reactie te bekijken.',
        'already_open'              => 'Je hebt nog een openstaande ticket! Wanneer deze behandeld is kun je weer een ticket aanmaken.',
        'no_answer_yet'             => 'Je kunt pas reageren als een '. Config::site['shortname'] . ' Staff je ticket heeft beantwoord.',
    ),
    'forum' => array (
        'is_sticky'                 => 'Sticky geüpdate!',
        'is_closed'                 => 'Topic status aangepast!'
    ),

    /*     Housekeeping     */
    'housekeeping' => array (
        'base' => array(
            'dashboard_header_title'    => 'Dashboard'
        ),
        'javascript' => array(
            'dashboard_table_username'  => 'Username'
        )
    )
);
?>
