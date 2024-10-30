<?php

declare(strict_types=1);

namespace ElementareTeilchen\Fixbetranslationsorting\Backend\EventListener;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\Event\ModifyDatabaseQueryForContentEvent;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Versioning\VersionState;

final class ModifyDatabaseQueryForContentEventListener
{
    public function __invoke(ModifyDatabaseQueryForContentEvent $event): void
    {

        #return;
        // Early return if we do not need to react
        if ($event->getTable() !== 'tt_content') {
            return;
        }

        // Retrieve QueryBuilder instance from event
        $queryBuilder = $event->getQueryBuilder();

        // Clone original query builder to request the tt_content records in the default language
        $queryBuilderForDefaultLanguages = clone $event->getQueryBuilder();
        // Add where clause to filter for sys_language_uid = 0
        $queryBuilderForDefaultLanguages->andWhere(
            $queryBuilderForDefaultLanguages->expr()->eq(
                'tt_content.sys_language_uid',
                $queryBuilderForDefaultLanguages->createNamedParameter(0, Connection::PARAM_INT)
            )
        );

        $resultForDefaultLanguage = $queryBuilderForDefaultLanguages->executeQuery();
        // Call getResult, same logic like in ContentFetcher
        $recordsInDefaultLanguage = $this->getResult($resultForDefaultLanguage);
        // Get array with uids from correctly sorted records in the default language
        $recordUidsInDefaultLanguage = array_column($recordsInDefaultLanguage, 'uid');

        // orderBy() overrides all previously set ordering conditions
        // https://docs.typo3.org/m/typo3/reference-tca/main/en-us/BestPractises/LanguageFields.html#fields_language-fields
        // The l10n_source field contains the uid of the record the translation was created from.
        // (Sometimes l18n_parent is used for this field in Core tables, like in tt_content. This is for historic reasons.)
        if(!empty($recordUidsInDefaultLanguage)) {
            $queryBuilder->orderBy('FIELD(l18n_parent,' . implode(',', $recordUidsInDefaultLanguage) . ')', null, false);
            $queryBuilder->addOrderBy('sorting');
        }
        // Set updated QueryBuilder to event
        $event->setQueryBuilder($queryBuilder);
    }

    // Copied from TYPO3\CMS\Backend\View\BackendLayout\ContentFetcher
    protected function getResult($result): array
    {
        $output = [];
        while ($row = $result->fetchAssociative()) {
            BackendUtility::workspaceOL('tt_content', $row, -99, true);
            if ($row && !VersionState::cast($row['t3ver_state'] ?? 0)->equals(VersionState::DELETE_PLACEHOLDER)) {
                $output[] = $row;
            }
        }
        return $output;
    }
}
