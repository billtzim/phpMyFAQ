<?php

/**
 * This is the page there a user can request a new password.
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package   phpMyFAQ
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2012-2024 phpMyFAQ Team
 * @license   https://www.mozilla.org/MPL/2.0/ Mozilla Public License Version 2.0
 * @link      https://www.phpmyfaq.de
 * @since     2012-03-26
 */

use phpMyFAQ\Configuration;
use phpMyFAQ\Template\TwigWrapper;
use phpMyFAQ\Translation;

if (!defined('IS_VALID_PHPMYFAQ')) {
    http_response_code(400);
    exit();
}

$faqConfig = Configuration::getConfigurationInstance();

$faqSession->userTracking('forgot_password', 0);

$twig = new TwigWrapper(PMF_ROOT_DIR . '/assets/templates/' . TwigWrapper::getTemplateSetName());
$twigTemplate = $twig->loadTemplate('./password.twig');

$templateVars = [
    ... $templateVars,
    'pageHeader' => Translation::get('lostPassword'),
    'lang' => $faqConfig->getLanguage()->getLanguage(),
    'msgUsername' => Translation::get('ad_auth_user'),
    'msgEmail' => Translation::get('msgEmail'),
    'msgSubmit' => Translation::get('msgNewContentSubmit'),
];

return $templateVars;
