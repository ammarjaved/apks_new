<table id="roads" class="table table-bordered table-hover">


    <thead style="background-color: #E4E3E3 !important">
        <tr>
            <th>Package Name</th>
            <th>Road Name</th>
            <th>Total KM</th>
            <th>Plan KM</th>
            <th>Feeder</th>


            <th>Action</th>

        </tr>
    </thead>
    <tbody>
        @if ($roads != '')
            
       
        @foreach ($roads as $road)
        <tr>
            <td><span class="work_pakcage_name"></span></td>
            <td class="text-center">{{$road->road_name != '' ? $road->road_name : '-'}}</td>
            <td class="text-center">{{$road->actual_km != '' ? $road->actual_km : '-'}}</td>
            <td class="text-center">{{$road->km != '' ? $road->km : '-'}}</td>
            <td class="text-center">{{$road->fidar != '' ? $road->fidar : '-'}}</td>

            <td class="text-center">

                <button type="button" class="btn  " data-toggle="dropdown">
                    <img
                        src="{{ URL::asset('assets/web-images/three-dots-vertical.svg') }}">
                </button>
                <div class="dropdown-menu" role="menu">
                    {{-- <a class="dropdown-item"
                    href="/edit-patrolling/{{ $road->id }}">Edit</a> --}}

                    <a class="dropdown-item"
                        href="/{{app()->getLocale()}}/patrolling-detail/{{ $road->id }}">Detail</a>



            </td>

        </tr>

        @endforeach
        @else
        <tr>
            <td class="text-center" colspan="6"><strong>no recored found</strong></td>
            

        </tr>

        @endif

    </tbody>
</table>

        @if ($roads != [])
        {{ $roads->links() }}

        @endif


