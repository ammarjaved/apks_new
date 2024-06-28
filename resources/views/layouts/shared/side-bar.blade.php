<aside class="main-sidebar sidebar-dark-primary ">

    <a href="/{{app()->getLocale()}}/dashboard" class="brand-link">
        <img src="{{ asset('assets/web-images/main-logo-sm.png') }}" alt="AdminLTE Logo" class="brand-image "
            style="opacity: .8">
        <span class="brand-text font-weight-light">APKS</span>
    </a>


    <div class="sidebar">

      {{--    <div class="user-panel mt-2 pb-2 mb-2 d-flex">

           <div class="info text-center">
                <a href="#" class=" text-center ml-4">Nav links</a>
            </div>
        </div>--}}



        <nav class="mt-2">



            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                @if (Auth::user()->is_admin != '1')
                <li class="nav-item">
                    <a href="/{{app()->getLocale()}}/dashboard" class="nav-link ">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>{{__('messages.dashboard')}}</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="fas fa-road"></i>

                        <p>{{__('messages.patrolling')}}

                        <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">


                        <li class="nav-item">
                            <a href="{{ route('patroling.index', app()->getLocale()) }}" class="nav-link ">
                               <p> <i class="far fa-circle nav-icon"></i>
                                {{__('messages.qr')}}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/patrolling-lks" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-tools"></i>
                        <p>
                            {{__('messages.3rd_party_digging')}}
                            <i class="right fas fa-angle-left"></i>

                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('third-party-digging.create', app()->getLocale()) }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.create')}} {{__('messages.notice')}}</p>
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/create-patrolling" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.patrolling')}}</p>
                            </a>
                        </li> --}}

                        <li class="nav-item">
                            <a href="{{route('notice', app()->getLocale())}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.update')}} {{__('messages.notice')}}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/map-1" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.map')}}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/get-all-work-packages" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.sbum_approval_and_detail')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('third-party-digging.index', app()->getLocale()) }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.index')}} {{__('messages.notice')}}</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="fas fa-building"></i>
                        <p>{{__('messages.substation')}}
                        <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('substation.create', app()->getLocale()) }}" class="nav-link ">
                                <p> <i class="far fa-circle nav-icon"></i>
                               {{__('messages.create')}} {{__('messages.substation')}}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/substation-map" class="nav-link ">
                              <p>  <i class="far fa-circle nav-icon"></i>
                                {{__('messages.map')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('substation.index', app()->getLocale()) }}" class="nav-link ">
                               <p> <i class="far fa-circle nav-icon"></i>
                                {{__('messages.qr')}}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/substation-documents" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="fas fa-cube"></i>
                        <p>{{__('messages.feeder_pillar')}}
                        <i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('feeder-pillar.create', app()->getLocale()) }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.create')}} {{__('messages.feeder_pillar')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/feeder-pillar-map" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.map')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('feeder-pillar.index', app()->getLocale()) }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.index')}}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/feeder-pillar-documents" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-bolt"></i>
                        <p>
                            {{__('messages.tiang_talian_vt_&_vr')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('tiang-talian-vt-and-vr.create', app()->getLocale())}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.create')}} SAVR</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/tiang-talian-vt-and-vr-map" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.map')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('tiang-talian-vt-and-vr.index', app()->getLocale())}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.index')}}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/tiang-talian-vt-and-vr-documents" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-link"></i>
                        <p>
                            {{__('messages.link_box_pelbagai_voltan')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('link-box-pelbagai-voltan.create', app()->getLocale())}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.create')}}  {{__('messages.link_box_pelbagai_voltan')}}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/link-box-pelbagai-voltan-map" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.map')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('link-box-pelbagai-voltan.index', app()->getLocale())}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.index')}}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/link-box-documents" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-road"></i>
                        <p>
                            {{__('messages.cable_bridge')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('cable-bridge.create', app()->getLocale())}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.create')}}  {{__('messages.cable_bridge')}}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/cable-bridge-map" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.map')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('cable-bridge.index', app()->getLocale())}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('messages.index')}}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/{{app()->getLocale()}}/cable-bridge-documents" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>


                    </ul>


                    <li class="nav-item ">
                        <a href="#" class="nav-link">
                            <i class="fas fa-bolt"></i>
                            <p>
                                {{__('messages.savt')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('savt.create', app()->getLocale())}}" class="nav-link ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{__('messages.create')}} savt</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/{{app()->getLocale()}}/savt-map" class="nav-link ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{__('messages.map')}}</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('savt.index', app()->getLocale())}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{__('messages.index')}}</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="/{{app()->getLocale()}}/tiang-talian-vt-and-vr-lks" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Documents</p>
                                </a>
                            </li> --}}
                        </ul>
                    </li>
                </li>





                {{-- <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-road"></i>
                        <p>
                            PO
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('po.create', app()->getLocale())}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('po.index', app()->getLocale())}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Index</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}


{{--
                <li class="nav-item">
                    <a href="/map-2" class="nav-link ">
                        <i class="fa fa-map"></i>
                        <p>Map</p>
                    </a>
                </li> --}}


                @else
                <li class="nav-item">
                    <a href="{{route('team.index', app()->getLocale())}}" class="nav-link ">
                        <i class="fa fa-map"></i>
                        <p>Team</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('team-users.index', app()->getLocale())}}" class="nav-link ">
                        <i class="fa fa-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>



    </div>

</aside>
<style>
    .nav-link p {
        color: #818896 !important;
    }

    .nav-item:hover .nav-link,
    .nav-item:hover .nav-link>p {
        color: #16c7ff !important;
    }

    nav .active {
        background-color: rgb(99 99 99 / 46%) !important;
    }
</style>
