<?php

namespace Webkul\UI\DataGrid\Traits;

trait ProvideQueryStringParser
{
    /**
     * Parse the query strings and get it ready to be used.
     *
     * @return array
     */
    private function getQueryStrings()
    {
        $route = request()->route() ? request()->route()->getName() : '';

        $queryString = $this->grabQueryStrings($route == 'admin.datagrid.export' ? url()->previous() : url()->full());

        $parsedQueryStrings = $this->parseQueryStrings($queryString);

        $this->itemsPerPage = isset($parsedQueryStrings['perPage']) ? $parsedQueryStrings['perPage']['eq'] : $this->itemsPerPage;

        unset($parsedQueryStrings['perPage']);

        return $this->updateQueryStrings($parsedQueryStrings);
    }

    /**
     * Grab query strings from url.
     *
     * @param  string  $fullUrl
     * @return string
     */
    private function grabQueryStrings($fullUrl)
    {
        return explode('?', $fullUrl)[1] ?? null;
    }

    /**
     * Parse query strings.
     *
     * @param  string  $queryString
     * @return array
     */
    private function parseQueryStrings($queryString)
    {
        $parsedQueryStrings = [];

        if ($queryString) {
            parse_str(urldecode($queryString), $parsedQueryStrings);

            unset($parsedQueryStrings['page']);
        }

        return $parsedQueryStrings;
    }

    /**
     * Update query strings.
     *
     * @param  array  $parsedQueryStrings
     * @return array
     */
    private function updateQueryStrings($parsedQueryStrings)
    {
        foreach ($parsedQueryStrings as $key => $value) {
            if (in_array($key, ['locale'])) {
                if (! is_array($value)) {
                    unset($parsedQueryStrings[$key]);
                }
            } else if (! is_array($value)) {
                unset($parsedQueryStrings[$key]);
            }
        }

        return $parsedQueryStrings;
    }
}
