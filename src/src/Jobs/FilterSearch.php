<?php

namespace App\Jobs;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class FilterSearch extends Event
{
    const NAME = "filter";

    public const FILTER_TYPE_TEXT = 'FILTER_TEXT';
    public const FILTER_TYPE_SELECT = 'FILTER_SELECT';

    /**
     * @var callable|SessionInterface
     */
    protected $session;

    public static $filterTypes = [
        'text' => self::FILTER_TYPE_TEXT,
        'select' => self::FILTER_TYPE_SELECT
    ];


    protected $filters = [];


    public function __construct(?array $filters = null, Request $request)
    {

        $this->session = $request->getSession();

        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $this->addFilter(
                    $filter['name'],
                    $filter['type'],
                    $filter['values'] ?? null
                );
            }
        }

        if (!$this->session->has("usedFilters")) {
            $usedFilters = $this->session->get("usedFilters", []);
            foreach ($filters as $filter) {
                if (isset($filter['default'])) {
                    $usedFilters[$filter['name']] = [];
                    $usedFilters[$filter['name']][] = $filter['default'];
                }
            }
            $this->session->set("usedFilters", $usedFilters);
        }

    }

    /**
     * Add Filter
     *
     * @param string $name
     * @param string $type
     * @param array|null $values
     *
     * @return $this|string
     */
    public function addFilter(string $name, string $type, ?array $values = null)
    {
        if (!in_array(trim($type), self::$filterTypes)) {
            return 'Filter Type unaviable: ' . $type;
        }

        if (trim($type) == self::FILTER_TYPE_SELECT && empty($values)) {
            return 'Select a value';
        }

        $this->filters[$name] = ['name' => trim($name), 'type' => trim($type), 'values' => $values];

        return $this;
    }


    /*
     * Get all instantiated filters
     */
    public function getFilters()
    {
        return $this->filters;
    }


    /**
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    public function handleRequest(Request $request): void
    {
        if ($request->query->get('delete-all-search-filter')) {
            $this->session->set("usedFilters", []);
            return;
        }
        $usedFilters = $this->session->get("usedFilters", []);

        $type = trim($request->query->get('search-filter-type', null));
        $value = trim($request->query->get('search-filter-value', null));

        $delType = trim($request->query->get('delete-search-filter-type', null));
        $delValue = trim($request->query->get('delete-search-filter-value', null));

        if (!empty($type) && !empty($value) && isset($this->filters[$type])) {
            $usedFilters[$type] = [];
            if ($this->filters[$type]['type'] == self::FILTER_TYPE_SELECT &&
                !in_array($value, array_keys($this->filters[$type]['values']))) {
                throw new \Exception('value not allowed');
            }
            $usedFilters[$type][] = $value;
        }

        if (!empty($delType) && !empty($delValue)) {
            if (isset($usedFilters[$delType])) {
                if (($key = array_search($delValue, $usedFilters[$delType])) !== false) {
                    unset($usedFilters[$delType][$key]);
                }
                if (count($usedFilters[$delType]) <= 0) {
                    unset($usedFilters[$delType]);
                }
            }
        }

        $this->session->set("usedFilters", $usedFilters);
    }


    /**
     * @param string $filter
     * @param callable $callback
     * @return void
     */
    public function on(string $filter, callable $callback)
    {
        if (isset($this->getUsedFilters()[$filter])) {
            $callback($this->getUsedFilters()[$filter]);
        }
    }

    /**
     * @return array
     */
    public function getUsedFilters(): array
    {
        return $this->session->get('usedFilters') ?? [];
    }


}