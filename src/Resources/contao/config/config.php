<?php
// nach https://github.com/contao/core-bundle/pull/512
// src/AppBundle/Resources/contao/config/config.php 
// hook wenn die Navigation am Backend gerufen wird siehe auch listener.yml in config dort als service angegeben
//$GLOBALS['TL_HOOKS']['getUserNavigation'][] = ['routing_app.user_navigation_listener', 'onGetUserNavigation'];

/*
 * vendor/bin/contao-console cache:clear --env prod --no-warmup
 * vendor/bin/contao-console cache:warmup --env prod
 */