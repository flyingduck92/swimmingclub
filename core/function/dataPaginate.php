<?php 

    function getData($query, $limit, $page) {

        $limit = $limit;
        $page = $page;
        $total = count(query($query));

        // add limit to query
        $query = $query." LIMIT ".( ($page-1)*$limit ). ", ".$limit;

        // execute
        $data = query($query);

        foreach ($data as $row) {
            $results[] = $row;
        }

        $result = new stdClass();
        $result->page = $page;
        $result->limit = $limit;
        $result->total = $total;
        $result->data = $results;

        return $result;
    }

    function pageLinks($links, $total, $limit, $page) {

        // links: show prev link and next link, from current page 
        $links; 

        $total;
        $limit; 
        $page;

        // calculate total pages 
        $last = ceil($total/$limit);

        // find startPage and endPage
        'Start: '.$start      = ( ($page-$links) > 0 ) ? $page - $links : 1;
        'End: '.$end        = ( ($page+$links) < $last ) ? $page + $links : $last;

        // if page more than end page force to page 1
        if($page > $end) {
            header('Location: ?'.basename($_SERVER['PHP_SELF']));
        }

        $html  = '<ul class="paginate">';

        // if not page 1 show
        if($page != 1) {
            $html  .= '<li class="paginate-item"><a href="?limit='.$limit.'&page='.($page-1).'">&laquo;</a></li>';
        }

        if ($page > 2) {
            $html  .= '<li class="paginate-item"><a href="?limit=' . $limit . '&page=1">1</a></li>';
            // $html  .= '<li class="paginate-item"><span>...</span></li>';
        }

        // show Links
        for ($i=$start;$i<=$end;$i++) {
            $html .= '<li class="paginate-item"><a href="?limit=' . $limit . '&page=' . $i . '">' . $i . '</a></li>';
        }

        // if endPage less lastPage 
        if ($end < $last) {
            // $html  .= '<li class="paginate-item"><span>...</span></li>';
            $html  .= '<li class="paginate-item"><a href="?limit=' . $limit . '&page=' . $last . '">' . $last . '</a></li>';
        }

        // if not last page show
        if($page != $last) {
            $html       .= '<li class="paginate-item"><a href="?limit=' . $limit . '&page=' . ( $page + 1 ) . '">&raquo;</a></li>';
        } 
             
        return $html       .= '</ul>';        
    }

 ?>