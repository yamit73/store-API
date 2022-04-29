<?php

declare(strict_types=1);

namespace Frontend\Components;

use Phalcon\Escaper;

final class MyEscaper
{
    /**
     * Escaper function
     *
     * @param array $data
     * 
     * @return array
     */
    public function sanitize($data)
    {
        $escaper = new Escaper();
        $res = [];
        foreach ($data as $key => $val) {
            $res[$key] = $escaper->escapeHtml($val);
        }
        return $res;
    }
}
