<div>
	{{-- The Master doesn't talk, he acts. --}}
	<div class="flex h-screen justify-center items-center">
		<div class="row w-100">
			<div class="col-md-12 p-6">
				<h1>Welcome James!</h1>
				<form class="mt-5 mb-5 flex align-items-center" wire:submit.prevent="photoBooth">
					<div class="mr-2">
						@if($this->avatar)
							<img class="img-fluid" src="{{ $avatar }}" alt="Profile Image"/>
						@endif
					</div>
					<div class="input-group ml-4">
						<input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror"
						       placeholder="Choose photo..." wire:model="photo">
						<button class="btn btn-outline-primary" type="submit">
							Set Profile Image
							<i class="spinner-border spinner-border-sm ml-1 mt-1" wire:loading wire:target="photoBooth"></i>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
