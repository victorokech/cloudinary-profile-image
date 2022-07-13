<?php
	
	namespace App\Http\Livewire;
	
	use Illuminate\Support\Facades\Auth;
	use Livewire\Component;
	use Livewire\WithFileUploads;
	
	class UserProfile extends Component {
		use WithFileUploads;
		
		public $user;
		public $avatar;
		
		public function mount() {
			// get profile image id from db if empty show default
			$this->user = Auth::user();
		}
		
		public function setProfileImage() {
			$this->validate([
				'avatar' => [
					'required',
					'image',
					'max:10240'
				]
			]);
			
			// create profile image
			$userAvatar = cloudinary()->upload($this->avatar->getRealPath(), [
				'folder'         => 'profile-image',
				'public_id'      => $this->user->id,
				'format'         => 'webp',
				'transformation' => [
					'format'  => 'auto',
					'quality' => 'auto',
					'crop'    => 'thumb',
					'gravity' => 'face',
					'radius'  => 'max',
					'width'   => 100,
					'height'  => 100,
				]
			])->getSecurePath();
			
			// set profile image path to database
			$this->user->profile_photo_path = $userAvatar;
			$this->user->save();
		}
		
		public function render() {
			return view('livewire.user-profile');
		}
	}
