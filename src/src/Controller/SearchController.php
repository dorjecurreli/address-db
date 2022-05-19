<?php

namespace App\Controller;


use App\Entity\Address;
use App\Entity\Organization;
use App\Entity\OrganizationType;
use App\Jobs\FilterSearch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\EventDispatcher\EventDispatcherInterface;

class SearchController extends BaseController
{
//    public array $filters;
//
//    public FilterSearch $filterSearch;
//
//
//
//    public function __construct(FilterSearch $filterSearch, $filters)
//    {
//        $this->filters =$filters;
//        $this->filterSearch = new FilterSearch();
//
//    }


    /**
     * @param Request $request
     *
     *
     * @return Response
     *
     * @Route("/organizations/filters", name="organizations_filter")
     *
     */
    public function searchOrganization(Request $request): Response
    {
        $countries = $this->getDoctrine()->getRepository(Address::class)->getDistinctCountries();
        $regions = $this->getDoctrine()->getRepository(Address::class)->getDistinctRegions();
        $organizationTypes = $this->getDoctrine()->getRepository(OrganizationType::class)->findAll();

        $filters = [
            ['name' => 'Name', 'type' => FilterSearch::FILTER_TYPE_TEXT],
            ['name' => 'Country', 'type' => FilterSearch::FILTER_TYPE_SELECT, 'values' => $this->makeValues($countries)],
            ['name' => 'Region', 'type' => FilterSearch::FILTER_TYPE_SELECT, 'values' => $this->makeValues($regions)],
            ['name' => 'Organization Type', 'type' => FilterSearch::FILTER_TYPE_SELECT, 'values' => $this->makeValues($organizationTypes)],
            ['name' => 'Address', 'type' => FilterSearch::FILTER_TYPE_TEXT],
            ['name' => 'Contact Details', 'type' => FilterSearch::FILTER_TYPE_TEXT],
            ['name' => 'Persons', 'type' => FilterSearch::FILTER_TYPE_TEXT]
        ];

        $filterSearch = new FilterSearch($filters, $request);
        $filterSearch->handleRequest($request);

        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findByFilterSearch($filterSearch);

        return $this->render("dashboard/organizations/filter.html.twig", ["filterSearch" => $filterSearch, 'organizations' => $organizations]);

    }

}