@extends('layout.master')

@section('content')
	<h3>Please define headers</h3>
	<div>

		@if(Session::has('errorMsg'))
			<p>
				<strong>{{ Session::get('errorMsg') }}</strong>
			</p>
		@endif

		<form action="{{ URL::to('processHeaders') }}" method="post">
		<input type="hidden" name="filePath" value="{{ urlencode($filePath) }}">
		<table>

			<tr>
				@foreach($dataTypes as $dataType)
					<th>{{ ucfirst($dataType) }}</th>
				@endforeach
			</tr>
			<tr>
				@foreach($dataTypes as $dataType)
					<td>
						<select name="{{ $dataType }}">
							@foreach($headers as $key => $value)
								<option value="{{ $key }}">{{ $value }}</option>
							@endforeach
						</select>
					</td>
				@endforeach
			</tr>
		</table>
		<input type="submit" value="Submit">
		</form>
	</div>
@stop