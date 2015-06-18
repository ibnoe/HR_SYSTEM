
@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@section('widget_title')
    <span class="ml-20">{{ $widget_title or 'Structure' }}</span>
@overwrite

@section('widget_info')
@overwrite

@section('widget_body')
    <ul class="nav" id="side-menu">
        <li>
            <a href="{{route('hr.organisations.create')}}"><i class="fa fa-plus-circle fa-fw"></i> Tambah Organisasi</a>
        </li>
        @if(isset($OrganisationComposer['widget_data']['sidebar']['organisation']))
            @foreach($OrganisationComposer['widget_data']['sidebar']['organisation'] as $key => $value)
              {{--   <li>
                    <a href=""><i class="fa fa-cog fa-fw"></i> Pengaturan<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li></li>
                    </ul>
                </li>
                <li>
                    <a href=""><i class="fa fa-table fa-fw"></i> Data<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li></li>
                    </ul>
                </li> --}}
                <li>
                    <a href="{{route('hr.organisations.show', $value['id'])}}"><i class="fa fa-bank fa-fw"></i> {{ $value['name'] }} <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        {{-- <li><a href="{{route('hr.organisations.show', $value['id'])}}"><i class="fa fa-eye fa-fw"></i> Show</a></li> --}}
                        <li><a href="{{route('hr.organisations.edit', $value['id'])}}"><i class="fa fa-pencil fa-fw"></i> Ubah</a></li>
                        <li><a href="{{route('hr.organisations.delete', [$value['id'], 'org_id' => $value['id']]) }}"><i class="fa fa-trash fa-fw"></i> Hapus</a></li>
                        <li><a href="{{route('hr.organisations.show', $value['id'])}}"><i class="fa fa-tachometer fa-fw"></i> Dashboard</a></li>
                        <li>
                            @if (count($value['branches'])==0)
                                <a href="{{route('hr.branches.index', ['org_id' => $value['id']])}}"><i class="fa fa-building fa-fw"></i> Cabang</a>
                            @else
                                <a href="{{route('hr.branches.index', ['org_id' => $value['id']])}}"><i class="fa fa-building fa-fw"></i> Cabang <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a href="{{route('hr.branches.index', ['org_id' => $value['id']])}}">Semua Cabang</a></li>
                                    @foreach($value['branches'] as $branch)
                                        <li>
                                            <a href="">{{ $branch['name'] }} <span class="fa arrow"></span></a>
                                            <ul class="nav nav-fourty-level">
                                                <li><a href="">Contact</a></li>
                                                <li><a href="">Chart</a></li>
                                                <li><a href="">Api</a></li>
                                                <li><a href="">Finger</a></li>
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                        <li><a href="{{route('hr.calendars.index', ['org_id' => $value['id']])}}"><i class="fa fa-calendar fa-fw"></i> Kalender</a></li>
                        <li><a href="{{route('hr.workleaves.index', ['org_id' => $value['id']])}}"><i class="fa fa-calendar-o fa-fw"></i> Template Cuti</a></li>
                        <li><a href="{{route('hr.documents.index', ['org_id' => $value['id']])}}"><i class="fa fa-archive fa-fw"></i> Template Dokumen</a></li>
                        <li><a href="{{route('hr.persons.index', ['org_id' => $value['id']])}}"><i class="fa fa-users fa-fw"></i> Data Karyawan</a></li>
                        <li><a href="{{route('hr.reports.index', ['org_id' => $value['id']])}}"><i class="fa fa-file-text-o fa-fw"></i> Laporan</a></li>
                    </ul>
                </li>                
            @endforeach
        @endif
    </ul>
@overwrite