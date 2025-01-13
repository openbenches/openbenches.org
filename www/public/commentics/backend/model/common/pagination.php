<?php
namespace Commentics;

class CommonPaginationModel extends Model
{
    public function paginate($page, $total, $url, $lang)
    {
        if ($page < 1) {
            $page = 1;
        }

        $limit = $this->setting->get('limit_results');

        $num_links = 3;

        $num_pages = ceil($total / $limit);

        $links = '';

        if ($page > 1) {
            $links = '<span><a href="' . str_replace('[page]', 1, $url) . '" title="' . $lang['lang_text_first'] . '">' . $lang['lang_text_first'] . '</a></span><span><a href="' . str_replace('[page]', $page - 1, $url) . '" title="' . $lang['lang_text_previous'] . '">&lt;</a></span>';
        }

        if ($num_pages > 1) {
            if ($num_pages <= $num_links) {
                $start = 1;

                $end = $num_pages;
            } else {
                $start = $page - floor($num_links / 2);

                $end = $page + floor($num_links / 2);

                if ($start < 1) {
                    $end += abs($start) + 1;

                    $start = 1;
                }

                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);

                    $end = $num_pages;
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $links .= '<span class="active">' . $i . '</span>';
                } else {
                    $links .= '<span><a href="' . str_replace('[page]', $i, $url) . '" title="' . $i . '">' . $i . '</a></span>';
                }
            }
        }

        if ($page < $num_pages) {
            $links .= '<span><a href="' . str_replace('[page]', $page + 1, $url) . '" title="' . $lang['lang_text_next'] . '">&gt;</a></span><span><a href="' . str_replace('[page]', $num_pages, $url) . '" title="' . $lang['lang_text_last'] . '">' . $lang['lang_text_last'] . '</a></span>';
        }

        $stats = sprintf($lang['lang_text_pagination'], ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, $num_pages);

        return array(
            'stats' => $stats,
            'links' => $links
        );
    }
}
