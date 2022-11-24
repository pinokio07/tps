@php
$base = Request::segment(1); 
$base2 = Request::segment(2);
$base3 = Request::segment(3);
$menu = getMenu('sidebar_'.$base);
@endphp

<ul class="nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
  @if($base == 'dashboard')
    <li class="nav-item">
      <a href="/dashboard" class="nav-link @if($base == 'dashboard') active @endif">
        <i class="fas fa-tachometer-alt nav-icon"></i>
        <p>Dashboard</p>
      </a>
    </li>
  @endif
  
  @if($menu != '')
    @forelse ($menu->parent_items as $item)
      <?php $linkActive = Str::replace(' ', '-', Str::lower($item->title)); ?>
      @if($item->children->isEmpty())
        @if($item->link() == '#')
          <li class="nav-header">{{ Str::title($item->title) }}</li>
        @else
          @can($item->permission)            
            <li class="nav-item">
              <a href="{{ $item->link() }}" class="nav-link {{ ($base2 == $linkActive) ? 'active' : '' }}">
                <i class="{{ $item->icon_class ?? 'far fa-cirle' }} nav-icon"></i>
                <p>{{ Str::upper($item->title)}}</p>
              </a>
            </li>
          @endcan
        @endif
      @else
        <li class="nav-header">{{ Str::title($item->title) }}</li>
        @foreach ($item->children as $subItem)
          @if($subItem->children->isEmpty())
            <?php $subActive = Str::replace(' ', '-', Str::lower($subItem->title)); ?>
            @if($subItem->link() == '#')
              <li class="nav-header">{{ Str::title($subItem->title) }}</li>
            @else
              @can($subItem->permission)              
              <li class="nav-item">
                <a href="{{ $subItem->link() }}" class="nav-link {{ ($base2 == $subActive) ? 'active' : '' }}">
                  <i class="{{ $subItem->icon_class ?? 'far fa-cirle' }} nav-icon "></i>
                  <p>{{ Str::upper($subItem->title) }}</p>
                </a>
              </li>
              @endcan
            @endif
          @else
            <?php $subActive = Str::replace(' ', '-', Str::lower($subItem->title)); ?>
            <li class="nav-item {{ ($base2 == $subActive) ? 'menu-open' : '' }}">
              <a href="{{ $subItem->link() }}" class="nav-link">
                <i class="nav-icon {{ $subItem->icon_class ?? 'far fa-cirle' }}"></i>
                <p>{{ Str::upper($subItem->title) }}
                  <i class="right fas fa-angle-left"></i>
                </p>                
              </a>
              <ul class="nav nav-treeview">
                @foreach($subItem->children as $deepSub)
                  <?php $deepSubActive = Str::replace(' ', '-', Str::lower($deepSub->title)); ?>
                  @can($deepSub->permission)
                  <li class="nav-item">
                    <a href="{{ $deepSub->link() }}" class="nav-link {{ ($base3 == $deepSubActive) ? 'active' : '' }}">
                      <i class="nav-icon {{ $deepSub->icon_class ?? 'far fa-cirle' }}"></i>
                      <p>{{ Str::upper($deepSub->title) }}</p>
                    </a>
                  </li>
                  @endcan
                @endforeach
              </ul>
            </li>
          @endif
        @endforeach
      @endif
    @empty
    
    @endforelse
  @endif
</ul>