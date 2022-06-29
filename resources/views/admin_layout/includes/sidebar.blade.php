<aside id="sidebar-left" class="sidebar-left">
				
    <div class="sidebar-header">
        <div class="sidebar-title">
            Navigation
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
            
                <ul class="nav nav-main">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>                        
                    </li>

                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('admin-list') || Auth::user()->is_superadmin == 1))
                    <li>
                        <a href="{{ route('admin.admin_user.index') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Admin</span>
                        </a>                        
                    </li>
                    @endif 

                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('user-list') || Auth::user()->is_superadmin == 1)) 
                    <li>
                        <a href="{{ route('admin.user.index') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>User</span>
                        </a>                        
                    </li>
                    @endif  

                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('category-list') || Auth::user()->is_superadmin == 1)) 
                    <li>
                        <a href="{{ route('admin.category.index') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Categories</span>
                        </a>                        
                    </li>
                    @endif  

                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('static_page-list') || Auth::user()->is_superadmin == 1)) 
                    <li>
                        <a href="{{ route('admin.static_pages.index') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Static Pages</span>
                        </a>                        
                    </li>
                    @endif  
             
                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('support_request-list') || Auth::user()->is_superadmin == 1)) 
                    <li>
                        <a href="{{ route('admin.support_request.index') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Support Request</span>
                        </a>                        
                    </li>
                    @endif  

                    @if(!empty(Auth::user()) && (Auth::user()->hasMultiplePermissionTo('permission_category-list','permission-list','role-list') || Auth::user()->is_superadmin == 1))
                    <li class="nav-parent">
                        <a href="#">
                            <i class="fa fa-asterisk" aria-hidden="true"></i>
                            <span>Setting</span>
                        </a>
                        <ul class="nav nav-children">
                            @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('permission_category-list') || Auth::user()->is_superadmin == 1))
                            <li>
                                <a href="{{ route('admin.permission_category.index') }}">
                                    Permission Category
                                </a>
                            </li>
                            @endif   
                            @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('permission-list') || Auth::user()->is_superadmin == 1))
                            <li>
                                <a href="{{ route('admin.permission.index') }}">
                                    Permission
                                </a>
                            </li>
                            @endif
                            @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('role-list') || Auth::user()->is_superadmin == 1))
                            <li>
                                <a href="{{ route('admin.role.index') }}">
                                    Role
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif    
                    
                </ul>
            </nav>
        </div>

        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    
                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
        

    </div>

</aside>