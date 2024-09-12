<ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{request()->routeIs('marketing-dashboard') ? 'active' : '' }}" href="{{route('marketing-dashboard')}}" role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{request()->routeIs('leads') ? 'active' : '' }}"  href="{{route('leads')}}" role="tab">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Customers</span>
        </a>
    </li>
</ul>
