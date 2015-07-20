@extends('layout.master')

@section('content')
	<h1>GuildQuality CSV-to-GoogleMaps App</h1>
	<h2>Please upload a file</h2>
	
    @if(Session::has('errorMsg'))
		<p>
			<strong>{{ Session::get('errorMsg') }}</strong>
		</p>
    @endif

    @foreach($errors->all() as $error)
        <p><strong>{{ $error }}</strong></p>
    @endforeach
	
	<p>
		{{ Form::open(array('url' => 'uploadCSV', 'files' => true)) }}
		<p>
			{{ Form::label('csvFile', 'Select a CSV File') }}
			{{ Form::file('csvFile') }}
		</p>
		<p>
			{{ Form::submit('Submit') }}
		</p>
		{{ Form::close() }}
	</p>
@stop