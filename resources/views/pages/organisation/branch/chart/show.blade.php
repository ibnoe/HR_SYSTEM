@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => $chart['name'], 'route' => route('hr.branch.charts.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Otentikasi', 'route' => route('hr.branch.charts.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
					]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'sidebar'				=> 
										[
											'search'			=> ['withattributes' => 'branches'],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100
										]
									]
	])
@overwrite

@section('content_body')
			@include('widgets.chart.stat', [
				'widget_template'		=> 'panel',
				'widget_title'			=> $chart['name'],
				'widget_options'		=> 	[
												'chartlist'		=>
												[
													'organisation_id'	=> $data['id'],
													'search'			=> ['id' => $chart['id']],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 1,
													'route_create'		=> route('hr.branch.charts.create', ['branch_id' => $branch['id'], 'org_id' => $data['id']]),
												]
											]
			])
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite