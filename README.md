<h1 align="center">
 Automated Profile Image/Avatar Creation with Laravel
</h1>

## Introduction

Modern web and mobile applications allow users to upload images to be used for their profiles. Using Cloudinary we will upload, transform and optimize the image uploaded and return a profile image that is optimized and consistent throughout the web application.

## PHPSandbox and Github

With [PHPSandbox](https://phpsandbox.io/e/x/09rrc?layout=EditorPreview&defaultPath=%2F&theme=dark&showExplorer=no&openedFiles=) we will be able to run a live demo of this project. All the code will be available on my [Github](https://github.com/victorokech/cloudinary-profile-image) repository for any references.


## Prerequisites

To follow along, you need to have experience and be comfortable issuing commands through the terminal of your respective operating system. You should also have some knowledge of Git version control and PHP specifically with the Laravel framework.

## Getting Started

Composer is the de facto package installer for most PHP projects. Follow the steps below keenly to install PHP and Composer.

1. Install [Composer](https://getcomposer.org/) and [PHP](https://www.php.net/manual/en/install.php) on
   your machine. Be sure to follow the steps for your respective operating system.
2. Laravel can be installed in two ways:

	1. Via Composer:

	   `composer create-project --prefer-dist laravel/laravel cloudinary-photobooth`
	2. Via Laravel Installer (Recommended):

	   `composer global require laravel/installer`

	   `laravel new cloudinary-photobooth`
3. If you follow either one of the steps above you should have a brand new Laravel applicaiton in the folder `cloudinary-profile-image`. 
   
We need to run the server and test our application to ensure everything is okay. Fire it up by running the following commands:

   `cd cloudinary-profile-image`

   `php artisan serve`

The Laravel server should be up and running and when you open `http://localhost:8000` on your browser, you should see the application default page shown in the image below:

![Laravel Server Running](https://res.cloudinary.com/dgrpkngjn/image/upload/v1655887283/watermark-api/assets/laravel-running_zqk8ol.png)

## Resizing and Optimizing the Profile Images

Cloudinary is a media management and manipulation platform which comes in handy in this scenario. We will use it to manipulate the profile image the users upload for consistency and store the optimized profile images on Cloudinary for a faster performant application.

We will start by installing the [Cloudinary SDK](https://github.com/cloudinary-labs/cloudinary-laravel#installation) for Laravel and setting the Cloudinary credentials in our environmental file `.env`.

```
CLOUDINARY_API_KEY=YOUR_CLOUDINARY_API_KEY
CLOUDINARY_API_SECRET=YOUR_CLOUDINARY_API_SECRET
CLOUDINARY_CLOUD_NAME=YOUR_CLOUDINARY_CLOUD_NAME
CLOUDINARY_URL=YOUR_CLOUDINARY_ENVIRONMENT_VARIABLE
```

1. Go to your Cloudinary dashboard and get your Account details - the  Cloud Name, API Key, API Secret and the API Environment variable, we will need them later.

   ![Cloudinary Dashboard](https://res.cloudinary.com/dgrpkngjn/image/upload/v1655976836/assets/cloudinary_dashboard.png)
2. Install the Cloudinary SDK. Ensure you follow all the steps in the #Installation section from the Github repo of the SDK:

   `composer require cloudinary-labs/cloudinary-laravel`


## User Interface

Our user interface will be a very simple implementation of a user profile with a form to set and submit the user image.

We will use Bootstrap for our CSS and HTML and Livewire for our dynamic interfaces.

1. Install Laravel UI
   
	`composer require laravel/ui`

2. Install Bootstrap UI. This will install the necessary scaffolding
   
	`php artisan ui bootstrap`

3. Install the Livewire dependency:

   `composer require livewire/livewire`
4. Include Livewire scripts and styles on every page that will be using Livewire. In our case `welcome.blade.php`:

```html
...
    @livewireStyles
</head>
<body>
    ...
  
    @livewireScripts
</body>
</html>
```

5. We will then create a Livewire Component for our user profile:

   `php artisan make:livewire UserProfile`

   This creates two files, first `app/Http/Livewire/UserProfile.php` and the other `resources/views/livewire/user-profile.blade.php`.

   We can then use this component anywhere in our code using the following snippet:

   `<livewire:user-profile/>`

   or

   `@livewire('user-profile')`
4. Open `resources/views/welcome.blade.php` and add the following code within the `<body></body>` tags as shown below:

```html
<body class="antialiased">
  <div>
    @livewire('user-profile')
  </div>
</body>
```

This includes the Livewire component we created earlier in our `welcome.blade.php`.

**Note:** Please ensure you go through the [Livewire documentation](https://laravel-livewire.com/docs/2.x/quickstart), to learn more.

3. Open the file `resources/views/livewire/user-profile.blade.php` and populate it with the following code:

```html
<form class="mb-5" wire:submit.prevent="photoBooth">
	<div class="form-group row mt-5 mb-3">
		<div class="input-group mb-5">
			<select id="effect" type="file" class="form-select @error('effect') is-invalid @enderror"
			        wire:model="effect">
				<option selected>Choose Photo Effect ...</option>
				<option selected value="effect_one">Cloudinary Rocks</option>
				<option value="effect_two">Rose Flower</option>
				<option value="effect_three">Abstract</option>
				<option value="effect_four">Flower Petals</option>
			</select>
			@error('effect')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<div class="input-group">
			<input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror"
			       placeholder="Choose photo..." wire:model="photo">
		
			@error('photo')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<small class="text-muted text-center mt-2" wire:loading wire:target="photo">
			{{ __('Uploading') }}…
		</small>
	</div>
	<div class="text-center">
		<button type="submit" class="btn btn-sm btn-primary w-25">
			<i class="fas fa-check mr-1"></i> {{ __('Lights, Camera, Action') }}
			<i class="spinner-border spinner-border-sm ml-1 mt-1" wire:loading wire:target="photoBooth"></i>
		</button>
	</div>
</form>
```

This is a form with a select field, file input field and a button. You can refer to the code on Github for the full implementation.

## Implementation in Code

Open the file `app/Http/Livewire/PhotoBooth.php` and update it with the following code:

1. First, we use Livewire's `WithFileUploads` to help us with file uploads, then create the variables `$photo`, `$effect`, `effectTransformations`, `$tag`, `$folder` and `$gallery` an array which will contain the transformed image URLs we get back from Cloudinary.

   ```php
   use WithFileUploads;  

   public $photo;
   public $effect;
   public $gallery = [];
   public $effectTransformations;
   public $folder = "photo-booth";
   public $tag = "photo-booth";
   ```
2. Next, create the `photoBooth` function which will apply upload and apply transformations to create the respective effect.

   ```php
   public function photoBooth() {
    ...
   }
   ```
3. Let's populate our method in step 2 above:

   ```php
   public function photoBooth() {
        $this->validate([
          'effect' => 'required|string',
          'photo' => ['required', 'image', 'max:10240'
        ]);

        $photo = cloudinary()->upload($this->photo->getRealPath(), [
            'folder' => $this->folder,
            'aspect_ratio'   => 0.75,
            'crop'           => 'fill',
            'height'         => 1600,
            'gravity' => 'faces'
        ])->getSecurePath();

       $this->effectTransformations = [
          'overlay' => [
              'public_id' => "$this->folder/assets/$this->effect",
              'flags'     => 'layer_apply',
              'width'     => 1.0,
              'height'    => 1.0,
          ]
       ];

       $transformed = cloudinary()->upload($photo, [
          'folder' => $this->folder,
          'tags'   => $this->tag,
          'transformation' => $this->effectTransformations
       ])->getSecurePath();

       $this->gallery = Arr::prepend($this->gallery, $transformed);
   }
   ```

   Let's talk about the code.

- ### Overlay Transformation

  First, we create the effect transformations based on user input and assign them to the variable `$effectTransformations`.


  ```php
  $this->effectTransformations = [
    ['crop' => 'crop', 'aspect_ratio' => 0.75, 'gravity' => 'faces', 'height' => 1600],
    [
	   'overlay' => [
	     'public_id' => "$this->folder/assets/$this->effect",
	     'flags'     => 'layer_apply',
	     'width'     => 1.0,
	     'height'    => 1.0,
	   ]
    ]
   ];
  ```

- ### Upload Photo with Transformations

  First, we upload the user image to Cloudinary, with an `aspect_ratio` of `0.75`, `crop` of `fill`, `gravity` set to `faces` and get the `secure_url` which we save in the variable `$photo`. The last line just prepends the transformed `$photo` to our `$this->gallery` array which we use to display the gallery.

```php
$photo = cloudinary()->upload($this->photo->getRealPath(), [
	'folder'         => $this->folder, 
	'tags'           => $this->tag,
	'transformation' => $this->effectTransformations
])->getSecurePath();

$this->gallery = Arr::prepend($this->gallery, $photo);
```

Update our Livewire component view and add the following code beneath the form:

```php
<div class="row mt-4">
	@foreach($this->gallery as $galleryItem)
		@if ($galleryItem)
			<div class="col-sm-3 col-md-3 mb-3">
				<img class="card-img-top img-thumbnail img-fluid" src="{{ $galleryItem }}" alt="Virtual Photo Booth"/>
			</div>
		@endif
	@endforeach
</div>
```

This will display our gallery.

With our code implementation complete, you should be able to see the following when you navigate to `http://localhost:8000`:

![Cloudinary Virtual Photo Booth Demo](https://res.cloudinary.com/dgrpkngjn/image/upload/c_scale,w_940/v1657629350/photo-booth/assets/demo_ha4bgw.png)

## Awesome

![Cloudinary Virtual Photo](https://res.cloudinary.com/dgrpkngjn/image/upload/c_scale,w_400/v1657629099/photo-booth/Xl3XxUTaukus92joF0MbF1HghboMZj-metaYmxhY2stbWFuLWhlcm8tYW5ncnktZXhwcmVzc2lvbi5qcGc_-_odrbyg.jpg)

Congratulations, we have built our own virtual photo booth powered by Cloudinary and Laravel. This is just the beginning, with Cloudinary you can create wonderful image management and manipulation products.

Keep discovering more with Cloudinary, all you have to do is create [free](https://cloudinary.com/signup) account.
