<?php $this->load->view('header'); ?>
<?php
	$user_role_id = 0;
	$is_login = 0;
	$is_user_suspended = 0;

	if( is_user_login()) {
		$is_login = 1;
		if ( !empty( get_user_role_id() ) ) {
	         $user_role_id = get_user_role_id();
	    }
	    $is_user_suspended = $this->session->userdata['is_user_suspended'];
	}
?>


	<header>
  		<input type="hidden" id="hdCalculateHeight" value='0'/>
			<script async src="//"></script>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({
					google_ad_client: "ca-pub-9267296218777616",
					enable_page_level_ads: true
				});
			</script>
			<!-- Google Analytics -->
			<script>
			window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
			ga('create', 'UA-49106633-8', 'auto');
			ga('send', 'pageview');
			</script>
			<script async src='https://www.google-analytics.com/analytics.js'></script>
			<!-- End Google Analytics -->
  		<div class="wrapper">
    		<div class="social">
    			<span id="social-first">
    				<img alt="Social Share" src="<?php echo get_layout_url('images')?>/social.png"/>
    			</span>
	      		<ul id="social-open-first">
	        		<li>
	        			<a style="cursor:pointer;" href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonPin" data-pin-custom="true">
	        				<img alt="Pintrest" src="<?php echo get_layout_url('images')?>/pintrest.png"/>
	        			</a>
	        		</li>
	        		<li>
	        			<a rel="external" href="javascript:void(0);" id="shareBtnFB">
	        				<img alt="Facebook" src="<?php echo get_layout_url('images')?>/facebook.png"/>
	        			</a>
	        		</li>
	      		</ul>
    		</div>
    		<!--  <a href="javascript:;" class="return" onclick="history.back();"> Retourner </a> -->
    		<!--social close-->
    	    <a class="logo" href="<?php echo base_url(); ?>">
    	    	<img src="<?php echo get_layout_url('images')?>/home-header-logo.png" alt="logo"/>
    	   	</a>
   			<div class="right-side-header">

   				<?php
   					$is_user_book_ws = can_user_book_workshop(); //login_helper.php file [check is user suspende or not]
      			 	if( is_user_login() ) { //if login user ?>
	   					<a rel="external" href="<?php echo base_url('user/logout'); ?>">
	                        <img class="logoutbtn" src="<?php echo get_layout_url('images')?>/logoutbtn.png" alt="se déconnecter">Se Déconnecter
	                    </a>
	                    <?php if ( $this->session->userdata['front_user']['roles_id'] == ATTENDEE ) {?>
							<a rel="external" href="<?php echo base_url('panier'); ?>" class="fr shop" > <img alt="shop" src="<?php echo get_layout_url('images')?>/shop.png"> </a>
		      			<?php }
	      			} else { ?>
						<a rel="external" href="<?php echo base_url('login'); ?>" class="home-login">
		   					<img alt="Connexion / inscription" src="<?php echo get_layout_url('images')?>/conex.png"/>Connexion / inscription
		   				</a>
							<div class="btn-inscription-pro">
		   					<a rel="external" class="ins-pro" href="<?php echo base_url('inscription/professional/1'); ?>">
										<!-- <img alt="Connexion / inscription" src="<?php echo get_layout_url('images')?>/conex.png"/> -->
									Devenez organisateur d’ateliers</a>
								</div>
				<?php } ?>

				<?php if( !empty($this->session->userdata['front_user'])) { ?>
					<div onclick="redirect_to_myaccount();" class="logininfo_box">
	                        <?php
	                            $fname = $this->session->userdata['front_user']['fname'];
	                            $lname = $this->session->userdata['front_user']['lname'];
	                            $user_image = $this->session->userdata['front_user']['image'];

	                            if( !empty($user_image) ){
					               $user_image_str = '<li><img id="profile_pic" src="'.base_url().'uploads/75x75/'.$user_image.'" alt="'.$fname.' '.$lname.'"/></li>';
					            }else{
					               $user_image_str = '<li><img id="profile_pic" src="'.base_url().'public/default/images/no_image/default-450x450.jpg" alt="'.$user_image.'"/></li>';
					            }
	                        ?>
	                        <div class="logininfo_img">
	                           <?php echo $user_image_str; ?>
	                        </div>
	                        <p class="username"><?php echo $fname.' '.$lname; ?></p>
	                </div>
	            <?php } ?>


    		</div>
    		<!--right side header close-->
  		</div>
  		<!--wrapper close-->
	</header>
	<!--header close-->

	<!-- Google Analytics -->
	<?php include_once("analyticstracking.php") ?>
	<!-- End Google Analytics -->
	<div class="div-nav div-visible">
		<span onclick="open_headermenu();" >
			<img src="<?php echo get_layout_url('images')?>/menu.png" alt="menu"/>
		</span>
		<a rel="external" href="<?php echo base_url(); ?>" class="logo">
			<img alt="logo" src="<?php echo get_layout_url('images')?>/home-header-logo.png">
		</a>
	</div>
	<!--div nav-->
	<section class="banner-section">
			<h3 class="none">.</h3>
			<img alt="Main Banner" class="banner" src="<?php echo get_layout_url('images')?>/banner-placeholder.png"/>
	</section>
	<!--banner-section close-->
	<section class="sticky-header">
		<h3 class="none">.</h3>
		<div class="wrapper text-center">
			<div class="sticky-header-set get-hieght">
				<span>Vous aussi, Trouvez l’atelier</span> <b>do it yourself</b> <i>qui vous fait envie !</i>
			</div>
			<!--sticky-header-set close-->
		    <div class="sticky-header-div get-hieght">
				<div class="wrapper">


					<?php if( is_user_login() ) { ?>
						<?php if ( $this->session->userdata['front_user']['roles_id'] == ATTENDEE ) { ?>
		                    <a href="<?php echo base_url('account/previous_orders'); ?>" class="logo">
		                        <img alt="logo" src="<?php echo get_layout_url('images')?>/my_account_logo.png">
		                    </a>
		                <?php } else { ?>
		                    <a href="<?php echo base_url('account'); ?>" class="logo">
		                        <img alt="logo" src="<?php echo get_layout_url('images')?>/my_account_logo.png">
		                    </a>
		                <?php } ?>
					<?php } else { ?>
						<a rel="external" href="<?php echo base_url(); ?>" class="logo">
							<img alt="logo" src="<?php echo get_layout_url('images')?>/white-logo.png">
						</a>
					<?php } ?>

					<div class="right-side-header">

						<?php if( is_user_login() ) { //if login user ?>
		   					<a rel="external" href="<?php echo base_url('user/logout'); ?>">
		                        <img class="logoutbtn" src="<?php echo get_layout_url('images')?>/logoutbtn.png" alt="se déconnecter">Se Déconnecter
		                    </a>
		                    <?php if ( $this->session->userdata['front_user']['roles_id'] == ATTENDEE ) {?>
								<a rel="external" href="<?php echo base_url('panier'); ?>" class="fr shop" > <img alt="shop" src="<?php echo get_layout_url('images')?>/shop.png"> </a>
			      			<?php }
			      			} else { ?>
								<a rel="external" href="<?php echo base_url('login'); ?>"><img alt="Connexion / inscription" src="<?php echo get_layout_url('images')?>/conex.png">Connexion / inscription</a>
						<?php } ?>

						<?php if( !empty($this->session->userdata['front_user'])) { ?>
							<div onclick="redirect_to_myaccount();" class="logininfo_box">
			                        <?php
			                            $fname = $this->session->userdata['front_user']['fname'];
			                            $lname = $this->session->userdata['front_user']['lname'];
			                            $user_image = $this->session->userdata['front_user']['image'];

			                            if( !empty($user_image) ){
							               $user_image_str = '<li><img id="profile_pic" src="'.base_url().'uploads/75x75/'.$user_image.'" alt="'.$fname.' '.$lname.'"/></li>';
							            }else{
							               $user_image_str = '<li><img id="profile_pic" src="'.base_url().'public/default/images/no_image/default-450x450.jpg" alt="'.$user_image.'"/></li>';
							            }
			                        ?>
			                        <div class="logininfo_img">
			                           <?php echo $user_image_str; ?>
			                        </div>
			                        <p class="username"><?php echo $fname.' '.$lname; ?></p>
			                </div>
			            <?php } ?>

			            <div class="social">
							<span id="social1">
								<img src="<?php echo get_layout_url('images')?>/social.png" alt="Social Share">
							</span>
	  						<ul id="social-open1">
					            <li>
					            	<a style="cursor:pointer;" href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonPin" data-pin-custom="true">
					            		<img src="<?php echo get_layout_url('images')?>/pintrest.png" alt="Pintrest">
					            	</a>
					            </li>
					            <li>
					            	<a rel="external" href="javascript:void(0);" id="shareBtnFB_sticky">
					            		<img src="<?php echo get_layout_url('images')?>/facebook.png" alt="Facebook">
					            	</a>
					            </li>
	  						</ul>
						</div>
					</div>
				</div>
				<!--wrapper close-->
			</div>
			<!--sticky-header-div close-->
			<div class="div-nav div-invisible">
				<!-- <span onclick="go_to_bottom();" > -->
				<span onclick="open_headermenu();" >
					<img src="<?php echo get_layout_url('images')?>/menu.png" alt="menu"/>
				</span>

				<a rel="external" href="<?php echo base_url(); ?>" class="logo">
					<img alt="logo" src="<?php echo get_layout_url('images')?>/white-logo.png">
				</a>
				<div class="right-side-header">

	                <span class="fr cancel" style="display:none;">
	                    <img src="<?php echo get_layout_url('images')?>/cancel.png" alt="cancel"/>
	                </span>
	                <span class="fr search">
	                    <img src="<?php echo get_layout_url('images')?>/search.png" alt="search"/>
	                </span>
	            </div>
	            <?php if( is_user_login() && $this->session->userdata['front_user']['roles_id'] == ATTENDEE ) { ?>
					<a class="fr shop marginbtm mr-zero" href="<?php echo base_url('panier'); ?>">
						<img src="<?php echo get_layout_url('images')?>/shop.png" alt="shop">
					</a>
				<?php } ?>
			</div>
			<!--div nav-->
		    <div class="sticky-header-fields mobilesearch">
		    <?php echo form_open( base_url(), array('method' => 'get', 'name' => 'search_form', 'id' => 'search_form')); ?>

				<input class="first-input" type="text" name="search_title" id="search_title" value="<?php if(!empty($this->input->get('search_title'))){ echo $this->input->get('search_title'); } ?>" placeholder="Rechercher une activité"/>
				<input type="text" class="datepicker" name="search_date" id="search_date" value="<?php if(!empty($this->input->get('search_date'))){ echo $this->input->get('search_date'); } ?>" placeholder="Date" readonly="" />
				<input type="hidden" name="offset" id="offset" value="0" />
				<input type="hidden" name="offer_count" id="offer_count" value="0" />
				<input type="hidden" name="has_next_record" id="has_next_record" value="0" />
				<input type="hidden" name="filter_theme" id="filter_theme" value="" />
				<input type="hidden" name="filter_tags" id="filter_tags" value="" />
				<input type="hidden" name="filter_from_price" id="filter_from_price" value="0" />
				<input type="hidden" name="filter_to_price" id="filter_to_price" value="200" />
				<div class="feilds-search">
					<?php
						if( !empty($this->input->get('search_from_time')) && !empty($this->input->get('search_to_time')) )
						{
							$m = $this->input->get('search_from_time')." - ".$this->input->get('search_to_time')."H";
						}
						else
						{
							$m= "";
						}
					?>
					<input readonly type="text" value="<?php echo $m; ?>" class="label" placeholder="Heure">
					<div class="feilds-search-inner">
						<input type="text" maxlength="2" onkeyup="if (/\D/g.test(this.value)) {this.value = this.value.replace(/\D/g,''); $('.overlay').show(); $('.modelbox_content').text('Entrez les chiffres seulement'); }" name="search_from_time" id="search_from_time" value="<?php if(!empty($this->input->get('search_from_time'))){ echo $this->input->get('search_from_time'); } ?>" placeholder="De"/>
						<input type="text" maxlength="2" onkeyup="if (/\D/g.test(this.value)) {this.value = this.value.replace(/\D/g,''); $('.overlay').show(); $('.modelbox_content').text('Entrez les chiffres seulement'); }" name="search_to_time" id="search_to_time" value="<?php if(!empty($this->input->get('search_to_time'))){ echo $this->input->get('search_to_time'); } ?>" placeholder="À"/>
					</div>
				</div>
				<input type="submit" value="Et hop !"/>
			<?php echo form_close(); ?>
			</div>
			<!--sticky-header-fields close-->
		</div>
			<!--wrapper close-->
	</section>
	<!--sticky-header close-->
	<div class="side-bar-fields one scroll_when_footer_appear">
			<div class="arrow">
				<img alt="arrow-out" src="<?php echo get_layout_url('images')?>/arrow-r.png"/>
			</div>
	</div>

	<div class="side-bar scrollpanel no1 scroll_when_footer_appear">
	   <div class="side-bar-fields1">
	      <div class="set-nav">
	         <b>Filtrer les activités</b> <i>Thèmes</i>
	         <div class="left-nav">
	            <ul id="common_tags_ul">

	            </ul>
	         </div>
	         <!--left-nav close-->
	         <i class="currently_tag_common_class">En ce moment</i>
	         <div class="left-nav currently_tag_common_class ">
	            <ul id="currently_tags_ul">

	            </ul>
	         </div>
	         <!--left-nav close-->
	         <i class="paddingbtm5">Prix</i>
	         <div class="rangeslide">
	            <div id="skipstep" class="noUi-target noUi-ltr noUi-horizontal noUi-background"></div>
	            <span id="skip-value-lower" class="example-val">0</span>
	            <span id="skip-value-upper" class="example-val">0</span>
	            <script type="text/javascript">
	            	var skipSlider=document.getElementById("skipstep");noUiSlider.create(skipSlider,{step:10,range:{min:0,max:200},format:wNumb({postfix:"€",decimals:0}),snap:!1,start:[0,200]});var skipValues=[document.getElementById("skip-value-lower"),document.getElementById("skip-value-upper")];skipSlider.noUiSlider.on("update",function(a,b){skipValues[b].innerHTML=a[b],0==b?$("#filter_from_price").val(parseInt(a[b])):$("#filter_to_price").val(parseInt(a[b]))}),skipSlider.noUiSlider.on("end",function(){reset_fields("offset",0),reset_fields("offer_count",0),reset_fields("has_next_record",0),search_filter_workshops()});
	            </script>
	         </div>
	         <i></i>
	      </div>
	      <div class="arrow-left"> <img alt="arrow-in" src="<?php echo get_layout_url('images')?>/arrow-l.png"/> </div>
	   </div>
	</div>
	<!--side-bar close-->

	<section class="boxes-div">
		<h3 class="none">.</h3>
		<div class="wrapper-fields">
			<div class="boxes-div-primary">
				<div class="fl boxes-left">
					<div class="fl">
						<!-- <input id="checkbox-1" class="checkbox-custom" name="checkbox-1" type="checkbox" checked>
						<label for="checkbox-1" class="checkbox-custom-label">Se perfectionner</label> -->
					</div>
					<div class="fl">
						<!-- <input id="checkbox-2" class="checkbox-custom" name="checkbox-2" type="checkbox" checked>
						<label for="checkbox-2" class="checkbox-custom-label">découvrir</label> -->
					</div>
				</div>
				<input type="checkbox" id="sort_by_price" value="0" style="display:none;">
				<input type="hidden" value="0" id="hidden_prix_type" />
				<div class="fr boxes-right">
					<b>Trier par </b>
					<a id="prix_name" rel="external" onclick="sort_by_price()" href="javascript:;">prix croissant</a>

				</div>
			</div>
			<!--boxes-div-primary close-->
		</div>
		<!--wrapper close-->
		<div class="lazy_loading"> <!--lazy load div start -->
			<div class="boxes-div-secondary" id="workshop_div_first">
				<div class="box-container">
					<p class="not-found-p" style="display:none;"></p>
					<div id="first_advertised_workshop_place"></div>
			    	<ul id="workshop_list_ul" >

					</ul>

				</div>
				<!--box-container close-->
			</div>
			<div id="loader-icon" class="loadersvg">
			<div id="container">
				<svg width="400" height="200" viewBox="0 0 400 200">
					<defs>
						<filter id="goo">
							<feGaussianBlur in="SourceGraphic" stdDeviation="7" result="blur" />
							<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 17 -7" result="cm" />
							<feComposite in="SourceGraphic" in2="cm">
						</filter>
						<filter id="f2" x="-200%" y="-40%" width="400%" height="200%">
							<feOffset in="SourceAlpha" dx="9" dy="3" />
							<feGaussianBlur result="blurOut" in="offOut" stdDeviation="0.51" />
							<feComponentTransfer>
								<feFuncA type="linear" slope="0.05" />
							</feComponentTransfer>
							<feMerge>
								<feMergeNode/>
								<feMergeNode in="SourceGraphic" />
							</feMerge>
						</filter>
					</defs>
					<g filter="url(#goo)" style="fill:#27dfd4">
						<ellipse id="drop" cx="125" cy="90" rx="20" ry="20" fill-opacity="1" fill="#27dfd4"/>
						<ellipse id="drop2"cx="125" cy="90" rx="20" ry="20" fill-opacity="1" fill="#27dfd4"/>
					</g>
				</svg>
			</div>

		</div> <!--lazy load div close -->
	</section>
	<!--boxes-section close-->
	<script type="text/javascript">

		$(function(){

			resetWorkshopOffset();
			get_filter_tags_list();
			search_filter_workshops();

			// lazy loading on scroll
			$(window).scroll( $.throttle( 300, lazy_loading ) );

			//session flash message start
			<?php if($this->session->flashdata('flash_message')){ ?>
				$(".overlay").show();
				$(".modelbox_content").text('<?php echo $this->session->flashdata('flash_message'); ?>');
			<?php } ?>
			//session flash message end

			<?php if( isset($_POST['search_title']) && $_POST['search_title'] != '' ){ ?>
				$('body,html').animate({ scrollTop: $(".banner-section").height() }, 1000);
				// $('body,html').animate({ scrollTop: $(document).height() }, 2000);
			<?php } ?>
		});

		function redirect_to_myaccount()
		{
			window.location.href = js_base_url+"account";
		}

		function lazy_loading( event ) {
			if((navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i)!= null)) {
				if($(window).scrollTop() + $(window).height() >= $(document).height() - 20) {
					update_offset();
	        	}
	        }
	        else{
	        	if($(document).height() == ( $(window).scrollTop() + $(window).height() ) )
				{
		           update_offset();
		        }
	        }
		}

		function update_offset()
		{
			var offset = $("#offset").val();
			offset = parseInt(offset) + parseInt(1);
			$("#offset").val(offset);

			var has_next_record = $("#has_next_record").val();
			if(has_next_record > 0){
				search_filter_workshops();
			}
		}

		/*
		* To gets tags list used in filter
		* Created by: Sandeep Rawat
		*/
		function get_filter_tags_list(){
			$.ajax({
		   		type: "POST",
		      	url: js_base_url + "workshop/get_filter_tags",
		      	// data: {},
				success: function(response){
					//console.log(response);

					// var data = response;
					var result = JSON.parse(response);
					if(result['status'] == true ){
		      			var com_str = ''; //common tags string
		      			var cur_str = ''; //currently tags string
		      			var data = result['data'];
		      			for(var i=0;i<data.length;i++){
		      				var dataObj = data[i];

		      				var tag_id = dataObj["id"];
		      				var tag_name = dataObj["tag_name"];
		      				var tag_category = dataObj["tag_category"];
		      				var is_active = dataObj["is_active"];
		      				var w_tag_count = dataObj["w_tag_count"];
		      				var o_tag_count = dataObj["o_tag_count"];
		      				var w_currently_tag_count = dataObj["w_currently_tag_count"];
		      				if(is_active == 1 && (w_tag_count>0 || w_currently_tag_count > 0)){

		      					if(tag_category == 'currently'){
		      						cur_str += 	'<li class="common_currently_tags_class" id="currently_tag_li_'+tag_id+'" >'+
				      								'<input type="checkbox" class="css-checkbox" onchange=set_currently_tag_ids_and_search() id="currently_tags-'+tag_id+'" name="currently_tags_id[]" value="'+tag_id+'">'+
				                					'<label class="css-label" for="currently_tags-'+tag_id+'" >'+tag_name+'</label>'+
				                  					'<p>'+w_currently_tag_count+'</p>'+
					      						'</li>';
		      					} else {
		      						com_str += 	'<li class="common_tags_class" id="tag_li_'+tag_id+'" >'+
				      								'<input type="checkbox" class="css-checkbox" onchange=set_tag_ids_and_search() id="tags-'+tag_id+'" name="tags_id[]" value="'+tag_id+'">'+
				                					'<label class="css-label" for="tags-'+tag_id+'" >'+tag_name+'</label>'+
				                  					'<p>'+w_tag_count+'</p>'+
					      						'</li>';
		      					}
		      				}
		      			}

		      			var empty_str = '<li class="common_tags_class">'+
			                				'<label class="css-label" >No tags found</label>'+
				      					'</li>';
		      			if(com_str == ''){
		      				com_str = empty_str;
		      			}
		      			$(".currently_tag_common_class").show();
		      			if(cur_str == ''){
		      				cur_str = empty_str;
		      				$(".currently_tag_common_class").hide();
		      			}

		      			$("#common_tags_ul").html(com_str);
		      			$("#currently_tags_ul").html(cur_str);
		      		}


		      	},
		      	error: function(response) {

		      	}
		  	});
		}

		/*
		* To search workshops list using sort by price/relevance
		*
		*/
		function sort_by_price(){
			var hidden_prix_type = $("#hidden_prix_type").val();
			if(hidden_prix_type == 1){
				$("#hidden_prix_type").val(2);
			}
			else {
				$("#hidden_prix_type").val(1);
			}
			$("#offset").val(0);
			search_filter_workshops();
		}


		/*
		* To search workshops list using filter for theme
		* @param: int tag_id
		*/
		function set_tag_ids_and_search(){
			reset_fields('offset',0);
	   		reset_fields('offer_count',0);
	   		reset_fields('has_next_record',0);

			$(".common_tags_class").removeClass("active");
			var cboxes = document.getElementsByName('tags_id[]');
		    var len = cboxes.length;
		    var tags_id_array = [];
		    for (var i=0; i<len; i++) {
		        if(cboxes[i].checked){
		        	$("#tag_li_"+ cboxes[i].value).addClass("active");
		        	tags_id_array.push(cboxes[i].value);
		        }
		    }
		    var tags_id = tags_id_array.join(",");
		    //console.log("tags_id: "+tags_id);
		    $("#filter_theme").val(tags_id);
       		$('#fifth_advertised_workshop_place').remove();
		    search_filter_workshops();
		}

		/*
		* To search workshops list using filter for currently tags
		* @param: int tag_id
		*/
		function set_currently_tag_ids_and_search(){
			reset_fields('offset',0);
	   		reset_fields('offer_count',0);
	   		reset_fields('has_next_record',0);

			$(".common_currently_tags_class").removeClass("active");
			var cboxes = document.getElementsByName('currently_tags_id[]');
		    var len = cboxes.length;
		    var currently_tags_id_array = [];
		    for (var i=0; i<len; i++) {
		        // console.log(i + (cboxes[i].checked?' checked ':' unchecked ') + cboxes[i].value);
		        if(cboxes[i].checked){
		        	$("#currently_tag_li_"+ cboxes[i].value).addClass("active");
		        	currently_tags_id_array.push(cboxes[i].value);
		        }
		    }
		    var currently_tags_id = currently_tags_id_array.join(",");
		    //console.log("currently_tags_id: "+currently_tags_id);
		    $("#filter_tags").val(currently_tags_id);
		    $('#fifth_advertised_workshop_place').remove();
		    search_filter_workshops();
		}

		/*
		* To capitalize first letter
		*/
		function ucfirst(str) {
		    var firstLetter = str.substr(0, 1);
		    return firstLetter.toUpperCase() + str.substr(1).toLowerCase();
		}

		function show_hide_loader(arg){
			if(arg=="show")
			{
				$("#loader-icon").show();
			}
			else
			{
				$("#loader-icon").hide();
			}
		}

		/*
		* Reset the fields
		* @param: int id (field id), mixed (suitable value)
		*/
		function reset_fields(id, v){
			$("#"+id).val(v);
		}

		/*
		* To publish/unpublish the workshop
		* @param:  int workshop_id
		*/
		function search_filter_workshops(){
			var offset = $("#offset").val();
			var offer_count = $("#offer_count").val();
			var search_title = $("#search_title").val();
			var search_date = $("#search_date").val();
			var search_from_time = $("#search_from_time").val();
			var search_to_time = $("#search_to_time").val();
			var filter_theme = $("#filter_theme").val();
			var filter_tags = $("#filter_tags").val();
			var filter_from_price = $("#filter_from_price").val();
			var filter_to_price = $("#filter_to_price").val();


			if( $("#hidden_prix_type").val() == 1)
			{
				$("#prix_name").text("prix décroissant");
				var sort_by_price = 1;
	        }
	        else if( $("#hidden_prix_type").val() == 2)
			{
				$("#prix_name").text("prix croissant");
				var sort_by_price = 2;
	        }
	        else {
	        	var sort_by_price = 0;
	        }


			$.ajax({
		   		type: "POST",
		      	url: js_base_url + "workshop/search",
	      		beforeSend: function(){
					show_hide_loader('show');
				},
		      	data: {
		      		"offset" : offset,
		      		"offer_count" : offer_count,
		      		"search_title" : search_title,
		      		"search_date" : search_date,
		      		"search_from_time" : search_from_time,
		      		"search_to_time" : search_to_time,
		      		"filter_theme" : filter_theme,
		      		"filter_tags" : filter_tags,
		      		"filter_from_price" : filter_from_price,
		      		"filter_to_price" : filter_to_price,
		      		"sort_by_price" : sort_by_price
		      	},
				success: function(response)
				{
					show_hide_loader('hide');
					// var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
					var monthNames = ["Janv", "Fév", "Mars", "Avr", "Mai", "Juin", "Juill", "Août", "Sept", "Oct", "Nov", "Dec"];
					var result = JSON.parse(response);

					if(result['data']['workshops'].length == 0)
					{
						$(".not-found-p").show();
						$(".not-found-p").text("Il n'y a pas de résultat pour cette recherche");
					}

					if(result['status'] == true ){
		      			var str = '';
		      			var has_next_record = result['data']['has_next_record'];
		      			$("#has_next_record").val(has_next_record);
		      			var data_offer_1 = result['data']['offer_1'];
		      			var data_offer_1_length = data_offer_1.length;
		      			var data_offer_2 = result['data']['offer_2'];
		      			var data_offer_2_length = data_offer_2.length;
		      			var data_workshops = result['data']['workshops'];
		      			var data_workshops_length = data_workshops.length;
		      			var data_advertised_workshops = result['data']['advertised_workshops'];
		      			var data_advertised_workshops_length = data_advertised_workshops.length;

		      			for(var i = 0; i < data_workshops.length; i++)
		      			{
		      				var dataObj = data_workshops[i];

		      				var workshop_id = dataObj["id"];
		      				var workshop_avail_id = dataObj["workshop_avail_id"];
		      				var workshop_title = (!dataObj["title"])?"":dataObj["title"];
		      				workshop_title = (workshop_title.length < 20)? workshop_title : workshop_title.substr(0, 30) + '...';

		      				var slug = dataObj["slug"];
		      				var workshop_description = (!dataObj["descrition"])?"":dataObj["descrition"];

		      				workshop_description = htmlDecode(workshop_description);
		      				workshop_description = (workshop_description.length < 80)? workshop_description : workshop_description.substr(0, 80) + '...';

		      				var price_per_attendee = (!dataObj["price_per_attendee"])?"0.00":dataObj["price_per_attendee"];
							price_per_attendee = replace_decimal_to_comma_in_price( price_per_attendee );

		      				/*var price_per_attendee = (!dataObj["price_per_attendee"])?"0.00":dataObj["price_per_attendee"];
							var tax_amt = (price_per_attendee * <?php echo TAX_AMOUNT ?>/100).toFixed(2);
							price_per_attendee_with_tax = parseFloat(price_per_attendee) + parseFloat(tax_amt);
							price_per_attendee = replace_decimal_to_comma_in_price( price_per_attendee_with_tax.toString() );*/

		      				var status 		  = dataObj["status"];
		      				var is_suspended  = dataObj["is_suspended"];
		      				var workshop_date = (!dataObj["workshop_date"])?"00/00/0000":dataObj["workshop_date"]; //mm/dd/yyyy
		      				workshop_date 	  = new Date(workshop_date);
		      				var day_of_month  = workshop_date.getDate();
		      				var month = monthNames[workshop_date.getMonth()];

		      				var from_time = (!dataObj["from_time"])?"00:00:00":dataObj["from_time"];
		      				from_time_str = from_time.split(":")[0];
		      				var end_time  = (!dataObj["end_time"])?"00:00:00":dataObj["end_time"];
		      				end_time_str  = end_time.split(":")[0];
		      				var avg_rating1 = (!dataObj["avg_rating"])?0.0:dataObj["avg_rating"];
		      				var avg_rating  = parseFloat(avg_rating1).toFixed(1);
		      				// console.log("avg_rating: "+avg_rating);
		      				// var avg_rating = Math.round(avg_rating1);

		      				var total_reviews = (!dataObj["total_reviews"])?"0":dataObj["total_reviews"];
		      				var reviewer_name = (!dataObj["reviewer_name"])?"":dataObj["reviewer_name"];
		      				var fname = (!dataObj["fname"])?"":ucfirst(dataObj["fname"]);
		      				var lname = (!dataObj["lname"])?"":dataObj["lname"][0].toUpperCase();
		      				var pro_name  = fname+" "+lname;
		      				var pro_image = (!dataObj["user_image"])?"":dataObj["user_image"];
		      				var workshop_image = (!dataObj["workshop_image"])?"":dataObj["workshop_image"];
		      				var workshop_image_path = (!dataObj["image_path"])?"":dataObj["image_path"];
		      				var workshop_tag_ids = (!dataObj["workshop_tag_ids"])?"":dataObj["workshop_tag_ids"];

		      				var orientation_class = (workshop_image.width > workshop_image.height) ? "landscape" : "portrait";
		      				var workshop_image_str = '<img alt="Box DP" src="<?php echo get_layout_url('images')?>/no_image/default-371.jpg" />';

		      				if(workshop_image!=""){
		      					workshop_image_str = '<img alt="Box DP" src=<?php echo base_url("uploads/'+workshop_image+'");?> onerror=imgError(this,"371") />';
		      				}

		      				var book_btn_str = '';
		      				<?php
		      					$show_book_btn = 1;
		      					if($is_login == 1 && $user_role_id == PROFESSIONAL){
		      						$show_book_btn = 0;
		      					}

		      				?>
		      				var show_book_btn = <?php echo $show_book_btn; ?>;
		      				var is_user_book_ws = <?php echo $is_user_book_ws; ?>;

		      				book_btn_str = '<a rel="external" href=<?php echo base_url()."atelier/detail/"; ?>'+slug+'>réserver</a>';
		      				/*if( is_suspended == 0 && show_book_btn == 1 && is_user_book_ws == 1){
		      					book_btn_str = '<a rel="external" href=<?php echo base_url()."workshop/book/"; ?>'+workshop_id+'>réserver</a>';
		      				}
		      				else if( is_user_book_ws == 0) {
		      					book_btn_str = '<a rel="external" href="javascript:;" onclick="user_suspended(); return false;"> réserver </a>';
		      				}
		      				else if( is_suspended == 1) {
		      					book_btn_str = '<a rel="external" href="javascript:;" onclick="workshop_suspended(); return false;"> réserver </a>';
		      				}
		      				else {
		      					book_btn_str = '<a rel="external" href="javascript::" onclick="not_auth_user(); return false;"> réserver </a>';
		      				}*/

		      				var avg_rating_str = '';
		      				var half_star_flag = true;
		      				for(var j=0;j<5;j++){
		      					if(j<parseInt(avg_rating)){
		      						avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/active-star.png"/>';
		      					}
		      					else{
		      						var decPart = (avg_rating+"").split(".")[1];
		      						if(decPart!=0 && half_star_flag == true){
		      							avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/star-h.png"/>';
		      							half_star_flag = false;
		      						}
		      						else{
		      							avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/star.png"/>';
		      						}

		      					}
		      				}


		      					str += '<li>'+
								        	'<div class="card-value">'+
								            	'<p>'+price_per_attendee+'<span>€</span></p>'+
								          	'</div>'+
								          	'<div class="box-dp" onclick=workshop_detail("'+slug+'") >'+
								          		workshop_image_str+
								          	'</div>'+
								          	'<div class="small-calender">'+
								          		'<b>'+day_of_month+'</b>'+
								          		'<i>'+month+'</i>'+
								            	'<p>'+from_time_str+' à '+end_time_str+'h</p>'+
								          	'</div>'+
								          	'<div class="star">'+
								          		'<span>'+
								          			avg_rating_str+
								          		'</span>'+
								          		'<b>'+total_reviews+' avis</b>'+
								          	'</div>'+
								          	'<h6 style="cursor:pointer;" onclick=workshop_detail("'+slug+'")>'+workshop_title+'</h6>'+
								          	'<div class="info-box">'+
									            '<p>Proposé par '+pro_name+'.</p>'+
									            '<p>'+(workshop_description)+'.</p>'+
								          	'</div>'+
								          	book_btn_str+
								      	'</li>';

		      			}
		      			if(offset==0)
		      				$("#workshop_list_ul").html(str);
		      			else
		      				$("#workshop_list_ul").append(str);

		      			var li_counts = $("#workshop_list_ul li").length;
	      				// console.log("li counts: "+ li_counts);

		      			//first advertised workshop
	      				var data_advertised_workshops_1_str = '';
	      				if(data_advertised_workshops_length > 0 && typeof data_advertised_workshops[0] !== 'undefined')
	      				{
	      					var data_advertised_workshops_1_obj = data_advertised_workshops[0];

	      					var price_per_attendee = (!data_advertised_workshops_1_obj["price_per_attendee"])?"0.00":data_advertised_workshops_1_obj["price_per_attendee"];
							price_per_attendee = replace_decimal_to_comma_in_price( price_per_attendee );

							var workshop_image = (!data_advertised_workshops_1_obj["workshop_image"])?"":data_advertised_workshops_1_obj["workshop_image"];
							var workshop_image_str = '<img alt="Box DP" src="<?php echo get_layout_url('images')?>/no_image/default-371.jpg"/>';

		      				if(workshop_image!=""){
		      					workshop_image_str = '<img alt="Box DP" src=<?php echo base_url("uploads/'+workshop_image+'");?> onerror=imgError(this,"371") />';
		      				}

		      				var workshop_date = (!data_advertised_workshops_1_obj["workshop_date"])?"00/00/0000":data_advertised_workshops_1_obj["workshop_date"]; //mm/dd/yyyy
		      				workshop_date 	  = new Date(workshop_date);
		      				var day_of_month  = workshop_date.getDate();
		      				var month = monthNames[workshop_date.getMonth()];
									console.log(month);
									switch (month) {
										case "Jan":
											month = "Janv";
											break;
										case "Feb":
											month = "Fev";
											break;
										case "Mar":
											month = "Mars";
											break;
										case "Apr":
											$month = "Avr";
											break;
										case "May":
											month = "Mai";
											break;
										case "Jun":
											month = "Juin";
											break;
										case "Jul":
											month = "Juil";
											break;
										case "Aug":
											month = "Août";
											break;
										case "Sep":
											month = "Sept";
											break;
										case "Oct":
											month = "Oct";
											break;
										case "Nov":
											month = "Nov";
											break;
										case "Dec":
											month= "Dec";
											break;
									}

		      				var from_time = (!data_advertised_workshops_1_obj["from_time"])?"00:00:00":data_advertised_workshops_1_obj["from_time"];
		      				from_time_str = from_time.split(":")[0];
		      				var end_time  = (!data_advertised_workshops_1_obj["end_time"])?"00:00:00":data_advertised_workshops_1_obj["end_time"];
		      				end_time_str  = end_time.split(":")[0];

		      				var avg_rating1 = (!data_advertised_workshops_1_obj["avg_rating"])?0.0:data_advertised_workshops_1_obj["avg_rating"];
		      				var avg_rating  = parseFloat(avg_rating1).toFixed(1);
		      				var avg_rating_str = '';
		      				var half_star_flag = true;
		      				for(var j=0;j<5;j++){
		      					if(j<parseInt(avg_rating)){
		      						avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/active-star.png"/>';
		      					}
		      					else{
		      						var decPart = (avg_rating+"").split(".")[1];
		      						if(decPart!=0 && half_star_flag == true){
		      							avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/star-h.png"/>';
		      							half_star_flag = false;
		      						}
		      						else{
		      							avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/star.png"/>';
		      						}

		      					}
		      				}
		      				var total_reviews = (!data_advertised_workshops_1_obj["total_reviews"])?"0":data_advertised_workshops_1_obj["total_reviews"];

		      				var workshop_title = (!data_advertised_workshops_1_obj["title"])?"":data_advertised_workshops_1_obj["title"];
		      				workshop_title = (workshop_title.length < 20)? workshop_title : workshop_title.substr(0, 30) + '...';

		      				var slug = data_advertised_workshops_1_obj["slug"];
		      				var fname = (!data_advertised_workshops_1_obj["fname"])?"":ucfirst(data_advertised_workshops_1_obj["fname"]);
		      				var lname = (!data_advertised_workshops_1_obj["lname"])?"":data_advertised_workshops_1_obj["lname"][0].toUpperCase();
		      				var pro_name  = fname+" "+lname;

		      				var user_image = (!data_advertised_workshops_1_obj["user_image"])?"":data_advertised_workshops_1_obj["user_image"];
		      				var user_image_str = '<img alt="Box DP" src="<?php echo get_layout_url('images')?>/no_image/default-371.jpg"/>';


		      				if(user_image!=""){
		      					user_image_str = '<img alt="Box DP" src=<?php echo base_url("uploads/75x75/'+user_image+'");?> onerror=imgError(this,"371") />';
		      				}

		      				var workshop_description = (!data_advertised_workshops_1_obj["descrition"])?"":data_advertised_workshops_1_obj["descrition"];

		      				workshop_description = htmlDecode(workshop_description);
		      				workshop_description = (workshop_description.length < 125)? workshop_description : workshop_description.substr(0, 125) + '...';

		      				var book_btn_str = '';
		      				<?php
		      					$show_book_btn = 1;
		      					if($is_login == 1 && $user_role_id == PROFESSIONAL){
		      						$show_book_btn = 0;
		      					}

		      				?>
		      				var show_book_btn = <?php echo $show_book_btn; ?>;
		      				var is_user_book_ws = <?php echo $is_user_book_ws; ?>;

		      				book_btn_str = '<a rel="external" href=<?php echo base_url()."atelier/detail/"; ?>'+slug+'>réserver</a>';
							data_advertised_workshops_1_str += '<div class="boxes-div-secondary full-bg">'+
																    '<div class="box-container">'+
																      	'<ul class="padding-set">'+
																	        '<li>'+
																	          	'<div class="full-bg-child1">'+
																	          		'<div class="box-dp" onclick=workshop_detail("'+slug+'") >'+
																		          		workshop_image_str+
																		          	'</div>'+

																		            '<div class="small-calender">'+
																		            	'<b>'+day_of_month+'</b>'+
																		            	'<i>'+month+'</i>'+
																		              	'<p>'+from_time_str+' à '+end_time_str+'h</p>'+
																		            '</div>'+
																	          	'</div>'+
																	          	'<div class="full-bg-child2">'+
																	            	'<div class="star">'+
																	              		'<div class="star-dp">'+user_image_str+'</div>'+
																	              		'<span>'+avg_rating_str+'</span>'+
																	              		'<b>'+total_reviews+' avis</b>'+
																	              	'</div>'+
																	            	'<h6 style="cursor:pointer;" onclick=workshop_detail("'+slug+'")>'+workshop_title+'</h6>'+
																	            	'<div class="card-value">'+
																	            		'<p>'+price_per_attendee+'<span>€</span></p>'+
																	            	'</div>'+
																	            	'<div class="info-box">'+
																	              		'<p>Proposé par '+pro_name+'.</p>'+
									            										'<p>'+(workshop_description)+'.</p>'+
																	            	'</div>'+
																	            	book_btn_str
																	            '</div>'+
																	        '</li>'+
																      	'</ul>'+
																    '</div>'+
																'</div>';
							$("#first_advertised_workshop_place").html(data_advertised_workshops_1_str);
	      				}

	      				//second advertised workshop
	      				var data_advertised_workshops_2_str = '';
	      				if(data_advertised_workshops_length > 0 && typeof data_advertised_workshops[1] !== 'undefined')
	      				{
	      					var data_advertised_workshops_2_obj = data_advertised_workshops[1];
	      					var price_per_attendee = (!data_advertised_workshops_2_obj["price_per_attendee"])?"0.00":data_advertised_workshops_2_obj["price_per_attendee"];
							price_per_attendee = replace_decimal_to_comma_in_price( price_per_attendee );



							var workshop_image = (!data_advertised_workshops_2_obj["workshop_image"])?"":data_advertised_workshops_2_obj["workshop_image"];
							var workshop_image_str = '<img alt="Box DP" src="<?php echo get_layout_url('images')?>/no_image/default-371.jpg"/>';

		      				if(workshop_image!=""){
		      					workshop_image_str = '<img alt="Box DP" src=<?php echo base_url("uploads/'+workshop_image+'");?> onerror=imgError(this,"371") />';
		      				}

		      				var workshop_date = (!data_advertised_workshops_2_obj["workshop_date"])?"00/00/0000":data_advertised_workshops_2_obj["workshop_date"]; //mm/dd/yyyy
		      				workshop_date 	  = new Date(workshop_date);
		      				var day_of_month  = workshop_date.getDate();
		      				var month = monthNames[workshop_date.getMonth()];

		      				var from_time = (!data_advertised_workshops_2_obj["from_time"])?"00:00:00":data_advertised_workshops_2_obj["from_time"];
		      				from_time_str = from_time.split(":")[0];
		      				var end_time  = (!data_advertised_workshops_2_obj["end_time"])?"00:00:00":data_advertised_workshops_2_obj["end_time"];
		      				end_time_str  = end_time.split(":")[0];

		      				var avg_rating1 = (!data_advertised_workshops_2_obj["avg_rating"])?0.0:data_advertised_workshops_2_obj["avg_rating"];
		      				var avg_rating  = parseFloat(avg_rating1).toFixed(1);
		      				var avg_rating_str = '';
		      				var half_star_flag = true;
		      				for(var j=0;j<5;j++){
		      					if(j<parseInt(avg_rating)){
		      						avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/active-star.png"/>';
		      					}
		      					else{
		      						var decPart = (avg_rating+"").split(".")[1];
		      						if(decPart!=0 && half_star_flag == true){
		      							avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/star-h.png"/>';
		      							half_star_flag = false;
		      						}
		      						else{
		      							avg_rating_str += '<img alt="Favourite" src="<?php echo get_layout_url('images')?>/star.png"/>';
		      						}

		      					}
		      				}
		      				var total_reviews = (!data_advertised_workshops_2_obj["total_reviews"])?"0":data_advertised_workshops_2_obj["total_reviews"];

		      				var workshop_title = (!data_advertised_workshops_2_obj["title"])?"":data_advertised_workshops_2_obj["title"];
		      				workshop_title = (workshop_title.length < 20)? workshop_title : workshop_title.substr(0, 30) + '...';

		      				var slug = (!data_advertised_workshops_2_obj["slug"])?"":data_advertised_workshops_2_obj["slug"];
		      				var fname = (!data_advertised_workshops_2_obj["fname"])?"":ucfirst(data_advertised_workshops_2_obj["fname"]);
		      				var lname = (!data_advertised_workshops_2_obj["lname"])?"":data_advertised_workshops_2_obj["lname"][0].toUpperCase();
		      				var pro_name  = fname+" "+lname;

		      				var user_image = (!data_advertised_workshops_2_obj["user_image"])?"":data_advertised_workshops_2_obj["user_image"];
		      				var user_image_str = '<img alt="Box DP" src="<?php echo get_layout_url('images')?>/no_image/default-371.jpg"/>';


		      				if(user_image!=""){
		      					user_image_str = '<img alt="Box DP" src=<?php echo base_url("uploads/75x75/'+user_image+'");?> onerror=imgError(this,"371") />';
		      				}

		      				var workshop_description = (!data_advertised_workshops_2_obj["descrition"])?"":data_advertised_workshops_2_obj["descrition"];

		      				workshop_description = htmlDecode(workshop_description);
		      				workshop_description = (workshop_description.length < 125)? workshop_description : workshop_description.substr(0, 125) + '...';

		      				var book_btn_str = '';
		      				<?php
		      					$show_book_btn = 1;
		      					if($is_login == 1 && $user_role_id == PROFESSIONAL){
		      						$show_book_btn = 0;
		      					}

		      				?>
		      				var show_book_btn = <?php echo $show_book_btn; ?>;
		      				var is_user_book_ws = <?php echo $is_user_book_ws; ?>;

		      				book_btn_str = '<a rel="external" href=<?php echo base_url()."atelier/detail/"; ?>'+slug+'>réserver</a>';
							data_advertised_workshops_2_str += '<div class="boxes-div-secondary full-bg">'+
																    '<div class="box-container">'+
																      	'<ul class="padding-set">'+
																	        '<li>'+
																	          	'<div class="full-bg-child1">'+
																	          		'<div class="box-dp" onclick=workshop_detail("'+slug+'") >'+
																		          		workshop_image_str+
																		          	'</div>'+

																		            '<div class="small-calender">'+
																		            	'<b>'+day_of_month+'</b>'+
																		            	'<i>'+month+'</i>'+
																		              	'<p>'+from_time_str+' à '+end_time_str+'h</p>'+
																		            '</div>'+
																	          	'</div>'+
																	          	'<div class="full-bg-child2">'+
																	            	'<div class="star">'+
																	              		'<div class="star-dp">'+user_image_str+'</div>'+
																	              		'<span>'+avg_rating_str+'</span>'+
																	              		'<b>'+total_reviews+' avis</b>'+
																	              	'</div>'+
																	            	'<h6 style="cursor:pointer;" onclick=workshop_detail("'+slug+'")>'+workshop_title+'</h6>'+
																	            	'<div class="card-value">'+
																	            		'<p>'+price_per_attendee+'<span>€</span></p>'+
																	            	'</div>'+
																	            	'<div class="info-box">'+
																	              		'<p>Proposé par '+pro_name+'.</p>'+
									            										'<p>'+(workshop_description)+'.</p>'+
																	            	'</div>'+
																	            	book_btn_str
																	            '</div>'+
																	        '</li>'+
																      	'</ul>'+
																    '</div>'+
																'</div>';


							if(li_counts == 0 && data_offer_1_length == 0)
							{
								var add_div = '<div id="fifth_advertised_workshop_place"></div>';
								$(add_div).insertAfter( "#first_advertised_workshop_place" );
								$("#fifth_advertised_workshop_place").html(data_advertised_workshops_2_str);
							}
							else if(li_counts == 0 && data_offer_1_length > 0)
							{
								var add_div = '<div id="fifth_advertised_workshop_place"></div>';
								//$(add_div).insertAfter( "#workshop_list_ul li:last-child" );
								$(add_div).insertAfter( "#workshop_list_ul" );
								$("#fifth_advertised_workshop_place").html(data_advertised_workshops_2_str);
							}
							else if(li_counts >= 2)
							{
								var add_div = '<div id="fifth_advertised_workshop_place"></div>';
								$(add_div).insertAfter( "#workshop_list_ul li:nth-child(2)" );
								$("#fifth_advertised_workshop_place").html(data_advertised_workshops_2_str);
							}
							else
							{
								var add_div = '<div id="fifth_advertised_workshop_place"></div>';
								//$(add_div).insertAfter( "#workshop_list_ul li:last-child" );
								$(add_div).insertAfter( "#workshop_list_ul" );
								$("#fifth_advertised_workshop_place").html(data_advertised_workshops_2_str);
							}

	      				}

		      			//first offer
	      				var offer_1_str = '';
	      				if( data_offer_1_length > 0 ){
	      					var data_offer_1_obj = data_offer_1[0];
	      					var offer_id = data_offer_1_obj['id'];
	      					var offer_title = data_offer_1_obj['title'];
	      					var offer_image = data_offer_1_obj['image'];

	      					var offer_short_desc = data_offer_1_obj['short_desc'];

	      					if(offer_short_desc.length > '36'){
	      						var offer_short_desc = offer_short_desc.substr(0, 36)+"...";
	      					}

	      					var offer_description = data_offer_1_obj['description'];
	      					var offer_status = data_offer_1_obj['status'];

	      					var offer_1_image_str = '<img alt="Box DP" src="<?php echo get_layout_url('images')?>/no_image/default-239x320.jpg"/>';
	      					if(offer_image!=""){
		      					offer_1_image_str = '<img alt="Card DP" src="<?php echo base_url()?>admin/uploads/offer_images/'+offer_image+'" onerror=imgError(this,"239x320") />';
		      				}


	      					offer_1_str += 	'<li class="card-parent">'+
									        	'<div class="card" onclick=go_to_offer_page('+offer_id+') >'+
									            	'<b>'+offer_title+'</b>'+
										          	'<div class="card-dp">'+
										          		offer_1_image_str +
										          	'</div>'+
										          	'<span>'+
										          		offer_short_desc +
										          	'</span>'+
									          	'</div>'+
									      	'</li>';
							//$('#workshop_list_ul li:eq(1)').before(offer_1_str);
							if(li_counts > 1)
							{
								$('#workshop_list_ul li:eq(1)').before(offer_1_str);
							}
							else
							{
								$( "#workshop_list_ul" ).append( offer_1_str );
							}
							$("#offer_count").val(1);
	      				}
		      			//first offer end

		      			//second offer
		      			var offer_2_str = '';
		      			var magazine_image_str = '';

	      				if( data_offer_2_length>0 ){

	      					var data_offer_2_obj = data_offer_2[0];
	      					var offer_id = data_offer_2_obj['id'];
	      					var offer_title = data_offer_2_obj['title'];
	      					var offer_image = data_offer_2_obj['image'];

	      					var offer_short_desc = data_offer_2_obj['short_desc'];

	      					if(offer_short_desc.length >= '36'){
	      						var offer_short_desc = offer_short_desc.substr(0, 36)+"...";
	      					} else {
	      						var offer_short_desc = offer_short_desc;
	      					}


	      					var offer_description = data_offer_2_obj['description'];
	      					var offer_status = data_offer_2_obj['status'];

	      					var offer_2_image_str = '<img alt="Box DP" src="<?php echo get_layout_url('images')?>/no_image/default-239x320.jpg"/>';
	      					if(offer_image!=""){
		      					offer_2_image_str = '<img alt="Card DP" src="<?php echo base_url()?>admin/uploads/offer_images/'+offer_image+'" onerror=imgError(this,"239x320") />';
		      				}

	      					offer_2_str += 	'<li class="card-parent">'+
									        	'<div class="card">'+
									            	'<b>'+offer_title+'</b>'+

										          	'<div class="card-dp" onclick=go_to_offer_page('+offer_id+')>'+
										          		offer_2_image_str+
										          	'</div>'+
										          	'<span>'+
											            offer_short_desc+
										          	'</span>'+
									          	'</div>'+
									      	'</li>';

							magazine_image_str += 	'<li class="card-parent magazine">'+
														'<div class="card">'+
											            	'<a href="http://www.yearn-magazine.fr/" target="_blank"><img alt="magazine-image" src="<?php echo get_layout_url('images')?>/yearn-diy-magazine.png"/></a>'+
											          	'</div>'+
											      	'</li>';

							// At end position

							if( li_counts < 9 && data_advertised_workshops_length == 0 )
							{
								$("#workshop_list_ul").append(offer_2_str);
							}
							else if( li_counts > 9 && data_advertised_workshops_length == 0 )
							{
								$(offer_2_str).insertBefore("#workshop_list_ul li:nth-child(8)");
							}
							else if( li_counts < 10 && data_advertised_workshops_length > 0) // At 9th position
							{
								//$( "#workshop_list_ul li:nth-child(8)" ).after( offer_2_str );
								$("#workshop_list_ul").append(magazine_image_str);

							}
							else if( li_counts > 10 && data_advertised_workshops_length > 0) // At 11th position
							{
								//$( "#workshop_list_ul li:nth-child(10)" ).after( offer_2_str );
								$(magazine_image_str).insertBefore("#workshop_list_ul li:nth-child(10)");
							}

							$("#offer_count").val(2);
	      				}

		      			//if no record found than show
		      			if( offset==0 && data_offer_1_length==0 && data_offer_2_length==0 && data_workshops_length==0 && data_advertised_workshops_length==0 ){
		      				$("#workshop_list_ul").html("<li class='noworkshop'><h6>Actuellement pas d'atelier disponible</h6></li>");
		      			}
		      			$('.boxes-div-secondary ul li .box-dp img').each(function(){
		                    $(this).addClass( parseInt(this.width) > parseInt(this.height) ? 'landscape' : 'portrait' );
		                });
					}
		      	},
		      	error: function(response) {
		      		show_hide_loader('hide');
		      	}
		   });
		}

		function workshop_detail(workshop_slug){
			window.location = js_base_url + "atelier/detail/" + workshop_slug;
		}

		function go_to_offer_page(offer_id){
			window.location = js_base_url + "offres/" + offer_id;
		}

		function imgError(elem, img_size){
			var img_url = '<?php echo get_layout_url('images')?>/no_image/default-'+img_size+'.jpg'
			elem.src = img_url;
		}

	</script>
	<script type="text/javascript" src="<?php echo get_layout_url('js')?>/TweenMax.min.js"></script>
	<script type="text/javascript">
		jQuery(function(){var a=document.getElementById("container"),b=document.getElementById("drop"),c=document.getElementById("drop2");document.getElementById("outline");TweenMax.set(["svg"],{position:"absolute",top:"50%",left:"50%",xPercent:-50,yPercent:-50}),TweenMax.set([a],{position:"absolute",top:"50%",left:"50%",xPercent:-50,yPercent:-50}),TweenMax.set(b,{transformOrigin:"50% 50%"});var e=new TimelineMax({repeat:-1,paused:!1,repeatDelay:0,immediateRender:!1});e.timeScale(3),e.to(b,4,{attr:{cx:250,rx:"+=10",ry:"+=10"},ease:Back.easeInOut.config(3)}).to(c,4,{attr:{cx:250},ease:Power1.easeInOut},"-=4").to(b,4,{attr:{cx:125,rx:"-=10",ry:"-=10"},ease:Back.easeInOut.config(3)}).to(c,4,{attr:{cx:125,rx:"-=10",ry:"-=10"},ease:Power1.easeInOut},"-=4")});
	</script>
<?php $this->load->view('footer'); ?>
