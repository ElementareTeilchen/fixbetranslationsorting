<?php

defined('TYPO3') || die();

(function () {

    // Add own QueryBuilder with orderBy function
    // Thanks to https://gist.github.com/wazum/5b21cfa2f3da04189b52ea86a1da85c0
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Database\Query\QueryBuilder::class] = [
        'className' => \ElementareTeilchen\Fixbetranslationsorting\Database\Query\QueryBuilder::class
    ];

})();
