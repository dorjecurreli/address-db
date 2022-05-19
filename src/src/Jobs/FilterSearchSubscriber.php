<?php


namespace App\Jobs;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FilterSearchSubscriber implements EventSubscriberInterface
{
    /**
     * @return mixed[]
     */
    public static function getSubscribedEvents()
    {
        return [
            FilterSearch::NAME => 'onFilterAdded'
        ];
    }

    public function onFilterAdded(FilterSearch $job)
    {
        // TODO: implement
        // $job->stopPropagation();
    }
}