<?php

declare(strict_types=1);

namespace phpMyFAQ\Controller\Administration;

use phpMyFAQ\Core\Exception;
use phpMyFAQ\Enums\PermissionType;
use phpMyFAQ\Filter;
use phpMyFAQ\Pagination;
use phpMyFAQ\Session\Token;
use phpMyFAQ\Template\Extensions\LanguageCodeTwigExtension;
use phpMyFAQ\Translation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Error\LoaderError;

class StatisticsSearchController extends AbstractAdministrationController
{
    /**
     * @throws LoaderError
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/statistics/search', name: 'admin.statistics.search', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $this->userHasPermission(PermissionType::STATISTICS_VIEWLOGS);

        $perPage = 10;

        $search = $this->container->get('phpmyfaq.search');
        $session = $this->container->get('session');

        $searchesCount = $search->getSearchesCount();
        $searchesList = $search->getMostPopularSearches($searchesCount + 1, true);

        // Pagination options
        $options = [
            'baseUrl' => $request->getUri(),
            'total' => is_countable($searchesList) ? count($searchesList) : 0,
            'perPage' => $perPage,
            'pageParamName' => 'page',
        ];
        $pagination = new Pagination($options);

        $this->addExtension(new LanguageCodeTwigExtension());
        return $this->render(
            '@admin/statistics/search.twig',
            [
                ... $this->getHeader($request),
                ... $this->getFooter(),
                'msgAdminElasticsearchStats' => Translation::get('msgAdminElasticsearchStats'),
                'csrfToken' => Token::getInstance($session)->getTokenString('truncate-search-terms'),
                'ad_searchterm_del' => Translation::get('ad_searchterm_del'),
                'ad_searchstats_search_term' => Translation::get('ad_searchstats_search_term'),
                'ad_searchstats_search_term_count' => Translation::get('ad_searchstats_search_term_count'),
                'ad_searchstats_search_term_lang' => Translation::get('ad_searchstats_search_term_lang'),
                'ad_searchstats_search_term_percentage' => Translation::get('ad_searchstats_search_term_percentage'),
                'pagination' => $pagination->render(),
                'searchesCount' => $searchesCount,
                'searchesList' => $searchesList,
                'csrfTokenDelete' => Token::getInstance($session)->getTokenString('delete-searchterm'),
                'ad_news_delete' => Translation::get('ad_news_delete'),
            ]
        );
    }
}