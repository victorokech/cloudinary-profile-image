<?php
	
	namespace App\Http\Livewire;
	
	use Livewire\Component;
	use Livewire\WithFileUploads;
	
	class UserProfile extends Component {
		use WithFileUploads;
		
		public $avatar = 'https://res.cloudinary.com/dgrpkngjn/image/upload/c_scale,q_auto,w_100/v1657646841/profile-image/assets/default_profile.png';
		
		public function mount() {
			// get profile image id from db if empty show default
			
		}
		
		public function setProfileImage() {
			// create profile image
			
			// set profile image public id to database
			
		}
		
		public function render() {
			return view('livewire.user-profile')->layoutData(['avatar' => $this->avatar]);
		}
	}
