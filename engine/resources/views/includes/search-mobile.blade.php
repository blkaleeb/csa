<!-- Open Search Section (visible on smaller screens) -->
<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
<button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout"
    data-action="header_search_on">
    <i class="fa fa-fw fa-search"></i>
</button>
<!-- END Open Search Section -->

<!-- Search Form (visible on larger screens) -->
<form autocomplete="off" class="d-none d-md-inline-block" action="bd_search.html" method="POST">
    <div class="input-group input-group-sm">
        <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2"
            name="page-header-search-input2" />
        <span class="input-group-text bg-body border-0">
            <i class="fa fa-fw fa-search"></i>
        </span>
    </div>
</form>
<!-- END Search Form -->
