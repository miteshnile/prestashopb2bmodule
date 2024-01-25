<nav aria-label="Page navigation example">
  <ul class="pagination">
    {if $totalPages > 1}
      {foreach from=range(1, $totalPages) item=pageNumber}
        {if $pageNumber == $currentPage}
          <li class="page-item active"><span class="page-link">{$pageNumber}</span></li>
        {else}
          <li class="page-item"><a class="page-link" href="{$link->getAdminLink('AdminModules', false)}&configure=b2bmodule&page={$pageNumber}">{$pageNumber}</a></li>
        {/if}
      {/foreach}
    {/if}
  </ul>
</nav>
