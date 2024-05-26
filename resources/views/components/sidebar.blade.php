<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}"><img src="{{asset('img/logo/logo_text.png')}}" width="150" height="150" alt=""></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}">DS</a>
        </div>
        <ul class="sidebar-menu mt-5">
            
            <li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-dashboard"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="nav-link"><i class="fas fa-users"></i><span>Accounts</span></a>
            </li>
            <li class="nav-item {{ Request::is('category*') ? 'active' : '' }}">
                <a href="{{ route('category.index') }}" class="nav-link"><i class="fas fa-gift"></i><span>Category</span></a>
            </li>
            <li class="nav-item {{ Request::is('product*') ? 'active' : '' }}">
                <a href="{{ route('product.index') }}" class="nav-link"><i class="fas fa-gift"></i><span>Products</span></a>
            </li>
            <li class="nav-item {{ Request::is('order*') ? 'active' : '' }}">
                <a href="{{ route('order.index') }}" class="nav-link"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
            </li>
    </aside>
</div>
