<?php

if (!function_exists('get_max_page_number')) {
    /**
     * @return int
     */
    function get_max_page_number($total, $perPage)
    {
        $maxPage = ($total / $perPage);
        if (is_float($maxPage)) {
            return (int)$maxPage + (1);
        }
        return $maxPage;
    }
}
