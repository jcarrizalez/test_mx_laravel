<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Paginator;

class EloquentPaginator
{
	public function paginate($query, $count=10, $page=null)
	{
      $paginate =  $query->paginate((int) $count, ['*'], 'page', $page);

      return [
      	'elements' => $query->get(),
      	'metadata' =>[
	      'count' => $count,
	      'page' => $paginate->currentPage(),
	      'total' => $paginate->total(),
	      'total_pages' => $paginate->lastPage(),
        ]
       ];
	}
}