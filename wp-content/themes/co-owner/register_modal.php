<div class="modal fade" id="property_goal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="register_steps">
      <form  id="registerModal" method="post" enctype="multipart/form-data">
      <div id="registerModalMessage" class="alert"></div>
	   <div id="registerModalWait" style="display:none;">Please wait...</div>
	  <div class="register_step  register_step1">
	  
		  <div class="modal-header text-center">
			<h4 class="modal-title w-100 font-weight-bold">Let us know about your goal!</h4>
			<h3 class="modal-title w-100 font-weight-bold">I am here to:</h3>
		  </div>
		  <div class="modal-body mx-3">
		    <div class="radio-option-main">
			<div class="md-form mb-5 radio-bx">
				<div class="radio-bx-in">
			  <input type="radio" name="property_option" value="buy_property" class="property_option">
			  <label for="buy_property" >Buy a Property </label>
			</div>
				</div>

			<div class="md-form mb-4 radio-bx">
				<div class="radio-bx-in">
			  <input type="radio" name="property_option" value="sell_property"  class="property_option">
			  <label for="sell_property">Sell a Property</label>
				</div>
			</div>
			  
			  </div>
		  </div>
	  
	 </div>

	  <div class="register_step register_step2">
	  
		  <div class="modal-header text-center">
			<h5 class="profile-selected modal-title w-100 font-weight-bold"></h5>
			<h4 class="modal-title w-100 font-weight-bold">Upload Profile Picture:</h4>
			<h6 class="modal-title w-100 font-weight-bold">Highly Recommended:</h6>
		  </div>
		  <div class="modal-body mx-3 profile-up">
               <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 pb-30px person-list-box uplod-profi-box">
                                    <div class="card property-card-one pb-2">
                                        <div class="card-body">
                                            <div class="property-one-thumb">
                                                <img id="user-profile" style="display:none;" src="<?php echo esc_url( get_avatar_url($user->ID));  ?>" data-old="<?php echo esc_url( get_avatar_url($user->ID));  ?>" alt="" class="img-fluid <?php echo $user->user_status == 2 ? 'deactivated-account' :  null; ?>">
                                            </div>
                                        </div>
                                        <!-- <span class="d-block" id="user-profile-browse">
                                            <a class="user-browse-svg text-orange d-block" href="#">
                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                                                <style type="text/css">
                                                    .st0{fill:#0A7E80;}
                                                </style>
                                                <path class="st0" d="M28,18.1v6.4h0v2.2c0,0.8-0.7,1.5-1.5,1.5h-23c-0.8,0-1.5-0.7-1.5-1.5v-7.1l0-1.5l-1.9,0V27c0,1.7,1.3,3,3,3H27
                                                    c1.7,0,3-1.3,3-3v-8.9H28z"></path>
                                                <path class="st0" d="M22.6,7.9L15.8,1c-0.4-0.4-1-0.4-1.4,0l-7,6.7l1.4,1.4l5.5-5.3v18.1h1.8V4.1l5.1,5.2L22.6,7.9z"></path>
                                                </svg>
                                                <strong>Drag & Drop your image <br> or <span class="orgnb">Browse</span></strong>
                                            </a>
                                         </span> -->
										 
										 <span class="d-block">
											<div class="browse-new panel panel-default">
											<!-- <div class="panel-heading">Select Profile Image</div> -->
											<div class="panel-body" align="center">
											<input type="file" name="upload_image" id="upload_image" accept="image/*" />
											<div id="uploaded_image">
											<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve"><style type="text/css">.st0{fill:#0A7E80;}</style>
											<path class="st0" d="M28,18.1v6.4h0v2.2c0,0.8-0.7,1.5-1.5,1.5h-23c-0.8,0-1.5-0.7-1.5-1.5v-7.1l0-1.5l-1.9,0V27c0,1.7,1.3,3,3,3H27c1.7,0,3-1.3,3-3v-8.9H28z"></path>
											<path class="st0" d="M22.6,7.9L15.8,1c-0.4-0.4-1-0.4-1.4,0l-7,6.7l1.4,1.4l5.5-5.3v18.1h1.8V4.1l5.1,5.2L22.6,7.9z"></path>
											</svg>
											Browse</div>
											</div>
											</div>
										 </span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12 pb-3 d-none">
                                    <div class="file-field" id="user-profile-drop-box">
                                        <div class="btn-floating text-center">
                                            <div class="file-icon">
                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                                                    <style type="text/css">
                                                        .st0{fill:#0A7E80;}
                                                    </style>
                                                    <path class="st0" d="M28,18.1v6.4h0v2.2c0,0.8-0.7,1.5-1.5,1.5h-23c-0.8,0-1.5-0.7-1.5-1.5v-7.1l0-1.5l-1.9,0V27c0,1.7,1.3,3,3,3H27
                                                        c1.7,0,3-1.3,3-3v-8.9H28z"/>
                                                    <path class="st0" d="M22.6,7.9L15.8,1c-0.4-0.4-1-0.4-1.4,0l-7,6.7l1.4,1.4l5.5-5.3v18.1h1.8V4.1l5.1,5.2L22.6,7.9z"/>
                                                </svg>
                                            </div>
                                            <div class="file-cnt text-center">
                                                Drag & Drop your image or <span class="d-block" id="user-profile-browse">Browse</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

			<div class="md-form mb-4">
			 <p>We recommend uploading a picture to let people know it's a real account and make a good impression towards other investors</p>
			 
			</div>
		  </div>
	  
	 </div>	  
	  
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-default " id="register_back" style="display:none;" data-id="">Back</button>
        <button class="btn btn-default" id="register_next" data-id="" disabled>Next</button>
		
      </div>
	  </form>
	  
    </div>
	
	
	
  </div>
</div>



