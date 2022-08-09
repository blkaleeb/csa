<!-- User Dropdown -->
<div class="dropdown d-inline-block ms-2">
    <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <img class="rounded" src="assets/media/avatars/avatar10.jpg" alt="" style="width: 21px;" />
        <span class="d-none d-sm-inline-block ms-1">{{Auth::user()->displayName}}</span>
        <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
        aria-labelledby="page-header-user-dropdown">
        {{-- <div class="p-3 text-center bg-body-light border-bottom rounded-top">
            <img class="img-avatar img-avatar48 img-avatar-thumb" src="assets/media/avatars/avatar10.jpg" alt="">
            <p class="mt-2 mb-0 fw-medium">John Smith</p>
            <p class="mb-0 text-muted fs-sm fw-medium">Web Developer</p>
        </div>
        <div class="p-2">
            <a class="dropdown-item d-flex align-items-center justify-content-between"
                href="be_pages_generic_inbox.html">
                <span class="fs-sm fw-medium">Inbox</span>
                <span class="badge rounded-pill bg-primary ms-2">3</span>
            </a>
            <a class="dropdown-item d-flex align-items-center justify-content-between"
                href="be_pages_generic_profile.html">
                <span class="fs-sm fw-medium">Profile</span>
                <span class="badge rounded-pill bg-primary ms-2">1</span>
            </a>
            <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                <span class="fs-sm fw-medium">Settings</span>
            </a>
        </div>
        <div role="separator" class="dropdown-divider m-0"></div> --}}
        <div class="p-2">
            {{-- <a class="dropdown-item d-flex align-items-center justify-content-between" href="op_auth_lock.html">
                <span class="fs-sm fw-medium">Lock Account</span>
            </a> --}}
            <a class="dropdown-item d-flex align-items-center justify-content-between logout" href="#">
                <span class="fs-sm fw-medium">Log Out</span>
            </a>
            <form action="{{route("logout")}}" method="post">@csrf</form>
        </div>
    </div>
</div>
<!-- END User Dropdown -->
