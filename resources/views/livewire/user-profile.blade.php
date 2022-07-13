<div>
	{{-- The Master doesn't talk, he acts. --}}
	<div class="flex h-screen justify-center items-center">
		<div class="row w-100">
			<div class="col-md-12 p-6 text-center">
				<h1 class="w-100">Welcome {{ $user->name }}!</h1>
				<div class="mt-5 mb-3">
					<img class="img-fluid"
					     src="{{ $user->profile_photo_path ?: 'https://res.cloudinary.com/dgrpkngjn/image/upload/f_auto,q_auto,w_100,h_100,g_face,c_thumb/v1657646841/profile-image/assets/default_profile.png' }}"
					     alt="Profile Image"/>
				</div>
				@if(!$user->profile_photo_path)
					<form class="mt-5 mb-5 flex align-items-center" wire:submit.prevent="setProfileImage">
						<div class="input-group ml-4">
							<input id="avatar" type="file" class="form-control @error('avatar') is-invalid @enderror"
							       placeholder="Choose profile photo..." wire:model="avatar">
							<button class="btn btn-outline-primary" type="submit">
								Set Profile Image
								<i class="spinner-border spinner-border-sm ml-1 mt-1" wire:loading wire:target="setProfileImage"></i>
							</button>
							@error('avatar')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
					</form>
				@endif
			</div>
		</div>
	</div>
</div>
