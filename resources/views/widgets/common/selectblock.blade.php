<div class="row">
	<div class="col-sm-12 text-left">
		<a href="{{ $widget_options['url_old'] }}" class="btn btn-primary">{{$widget_options['caption_old']}}</a>
		<a href="{{ $widget_options['url_new'] }}" class="btn btn-primary">{{$widget_options['caption_new']}}</a>

		@if (Route::is('hr.person.works.index')&((int)Session::get('user.menuid') <= 3))
			<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#import_csv_work_person" data-action="{{ route('hr.person.works.store') }}" data-org_id="{{ $data['id'] }}">Import CSV</a>
		@endif
	</div>
</div>
