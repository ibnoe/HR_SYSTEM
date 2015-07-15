@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Otentikasi Group ' : 'Ubah Otentikasi Group ') }} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $MenuComposer['widget_data']['menu']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Aplikasi</label>
						{!! Form::input('text', 'application_id', $MenuComposer['widget_data']['menu']['menu']['application_id'], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Nama Menu</label>
						{!! Form::input('text', 'name', $MenuComposer['widget_data']['menu']['menu']['name'], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Deskripsi</label>
						{!! Form::input('text', 'tag', $MenuComposer['widget_data']['menu']['menu']['tag'], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>
			<div class="form-group text-right">				
				<a href="{{ $MenuComposer['widget_data']['menu']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif