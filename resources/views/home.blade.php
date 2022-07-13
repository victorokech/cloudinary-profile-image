@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
					@livewire('user-profile')
				</div>
			</div>
		</div>
	</div>
@endsection
