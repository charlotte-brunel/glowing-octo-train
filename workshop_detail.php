<?php $this->load->view('header_inner'); ?>
<?php $this->load->view('sticky_header_profile'); ?>
<?php
	 $is_workshop_suspended = 0;
		$is_same_professional_logged_in = 0;
		$login_user_id = 0;
		$user_role_id = 0;
		$is_login = 0;

		if( is_user_login()) {
				$is_login = 1;
				if ( !empty( get_user_role_id() ) ) {
						$user_role_id = get_user_role_id();
				}

				if( !empty( get_logged_in_user_id() ) ){
						$login_user_id = get_logged_in_user_id();
				}
		}
		else
		{
			$_SESSION['last_visited_workshop'] = $workshop[0]['slug'];;
		}
?>

<script type="text/javascript">
		$(function(){
			$('.flexslider').flexslider({
				animation: "slide",
				smoothHeight: true,
				touch : true,
				start: function(slider){
					$('body').removeClass('loading');
				}
			});

			init_send_message();

			$(".exclamation").click(function(){
					$("span.popupbox").toggleClass("dsplyblock");
			});

			$("form").attr("autocomplete", "off");

			$("#btn_abuse").click(function(e){
				if($("#btn_abuse").attr("disabled")=="disabled") {
						e.preventDefault();
						return false;
				}
				else{
						report_abuse();
				}
			});

			$(".common_nav").click(function(e){
				$('.common_nav').removeClass('active');
				$(this).addClass('active');
				var li_id = $(this).attr('id');
				var id = li_id.slice(0, -3);

				var scrolltop_height = $('#'+id).position().top - $(".sticky-header").height();
				$('html,body').animate({
						scrollTop: scrolltop_height
				}, 'slow');
			});
		});

	 /*
	 * (SR) - Send email to admin about workshop report abuse
	 */
	 function report_abuse(){
				var workshop_id = $.trim($("#workshop_id").val());
				var abuse_msg = $("input:radio[name=report_abuse]:checked").val();
				if(abuse_msg){

				var post_data = {
						"workshop_id" : workshop_id,
						"abuse_msg" : abuse_msg,
						'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
						};


				 $("#btn_abuse").hide();
				 $("#loader-icon").show();

				 $.ajax({
						type: "POST",
						dataType: "json",
						url: js_base_url + "workshop/report_abuse/",
						data:post_data,
						beforeSend: function() {
							 $("#btn_abuse").hide();
							 $("#loader-icon").show();

						},
						success: function(response){
							 if(response["status"] == true){
										 $("input:radio[name=report_abuse]:checked").attr("checked",false);
										 $("span.popupbox").toggleClass("dsplyblock");
							 }

							 $("#btn_abuse").show();
							 $("#loader-icon").hide();

							 $(".overlay").show();
							 $(".modelbox_content").text(response['message']);

							 $("#btn_abuse").removeAttr("disabled");
						},
						error: function(response) {
							 $("#btn_abuse").show();
							 $("#loader-icon").hide();

							 $(".overlay").show();
							 $(".modelbox_content").text('Something went wrong');

							 $("#btn_abuse").removeAttr("disabled");
						}
				 });

			}
			else{
				 $(".overlay").show();
				 $(".modelbox_content").text("Please choose a reason.");
			}

	 }
	 /*
	 * (SR) - Send message to professional
	 */
	 function init_send_message(){
			$('#send_message_form').on('submit',function(event){
						event.preventDefault() ;
						event.stopPropagation();
						var mailbox_id = 0;
						var workshop_id = $.trim($("#workshop_id").val());
						var to_users_id = $.trim($("#to_users_id").val());
						var subject = $.trim($("#subject").val());
						var message = $.trim($("#message_to_professional").val());
						//alert(message);
						$(".error_msg").html("");

						var post_data = {
							 "mailbox_id" : mailbox_id,
							 "workshop_id" : workshop_id,
							 "to_users_id" : to_users_id,
							 "subject" : subject,
							 "message" : message,
							 '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
							 };
						// return false;

						$.ajax({
							 type: "POST",
							 dataType: "json",
							 url: js_base_url + "mailbox/send_message/",
							 data:post_data,
							 success: function(response){
									// var msg_array = [response["message"]];
									// display_error_success_msg('errorsDiv_message_to_professional',msg_array,response["status"]);
									$(".overlay").show();
									$(".modelbox_content").text(response["message"]);
									if(response["status"] == true){
										 $("#subject").val("");
										 $("#message_to_professional").val("");
									}

							 },
							 error: function(response) {
									$(".overlay").show();
									$(".modelbox_content").text("Something went wrong");
							 }
						});
			});
	 }

	 /*
	 * (SR) - Setting star rating
	 */
	 function star_rating(star){
			var star_url = '<?php echo base_url();?>public/default/images/star.png';
			$('.star').attr('src',star_url);
			var active_star_url = '<?php echo base_url();?>public/default/images/active-star.png';
			for(var i=1;i<=star;i++){
				 $('#star_'+i).attr('src',active_star_url);
			}
			$("#reviewer_rating").val(star);
	 }

		function setCookie(cname, cvalue, exdays) {
				var d = new Date();
				d.setTime(d.getTime() + (exdays*24*60*60*1000));
				var expires = "expires="+d.toUTCString();
				document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
		}

		var delete_cookie = function(name) {
				document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
		};

	 /*
	 * review rules
	 */
	 var review_rules = new Array();
	 review_rules[0]  = 'reviewer_rating|regexp|^[1-5]+[0-5]*$|<ul style="margin: 10px 0px 0px 0px;"><li>Il vous plaît donner à votre étoile .</li></ul>';
	 review_rules[1]  = 'reviewer_name|required|<ul style="margin: 10px 0px 0px 0px;"><li>Entrez votre nom</li></ul>';
	 review_rules[2]  = 'reviewer_email|required|<ul style="margin: 10px 0px 0px 0px;"><li>Entrer votre Email</li></ul>';
	 review_rules[3]  = 'reviewer_email|email|<ul style="margin: 10px 0px 0px 0px;"><li>Entrez une adresse email valide</li></ul>';
	 review_rules[4]  = 'reviewer_text|required|<ul style="margin: 10px 0px 0px 0px;"><li>Entrez votre commentaire</li></ul>';

	 /*
	 * send message rules
	 */
	 var send_message_rules = new Array();
	 send_message_rules[0]  = 'subject|required|^[1-5]+[0-5]*$|<ul style="margin: 10px 0px 0px 0px;"><li>Il vous plaît Entrez Sujet</li></ul>';
	 send_message_rules[1]  = 'message_to_professional|required|<ul style="margin: 10px 0px 0px 0px;"><li>Il vous plaît Entrez un message</li></ul>';

	 /*
	 * (SR) - Submit review about workshop by attendee
	 */
		function submit_review(){


				var workshop_id = $.trim($("#workshop_id").val());
				var reviewer_rating = $.trim($("#reviewer_rating").val());
				var reviewer_name = $.trim($("#reviewer_name").val());
				var reviewer_email = $.trim($("#reviewer_email").val());
				var reviewer_text = $.trim($("#reviewer_text").val());

				$(".error_msg").html("");

				var post_data = {
						"workshop_id" : workshop_id,
						"rating" : reviewer_rating,
						"name" : reviewer_name,
						"email" : reviewer_email,
						"description" : reviewer_text,
						'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
				}; <?php

				if( !is_user_login() ) { ?>

						setCookie("review_form_data", JSON.stringify(post_data), 1);
						setCookie("workshop_review_form", 1, 1);
						window.location = js_base_url+"login"; <?php

				} else { ?>

						$.ajax({
								type: "POST",
								dataType: "json",
								url: js_base_url + "workshop/save_review/",
								data:post_data,
								success: function(response){

										var msg_array = [response["message"]];
										var error_msg_str = msg_str(msg_array);
										$("#errorsDiv_reviewer_name").html(error_msg_str);
										if(response["status"]==true){

												setCookie("review_form_data", "", -1);
												setCookie("workshop_review_form", "", -1);

												$("#errorsDiv_reviewer_name").addClass('success_msg');
												setTimeout(function() {
														location.reload();
												}, 8000);
										}
										else
										{
												$("#errorsDiv_reviewer_name").addClass('error_msg');
										}
								},
								error: function(response) {
										console.log(response);
								}
						}); <?php
				} ?>
	 }

	 /*
	 * Redirects to professional detail page
	 */
	 function redirect_to_professional(prof_id)
	 {
			// alert(prof_id);
			location.href='<?php echo base_url();?>'+'professional/detail/'+prof_id;
	 }

</script>

<div data-role="page" style="display:none;"></div>
<div class="content-wrapper bw">
	 <!-- Content Start-->
	 <div class="primary contact-primary liste-actus-left">
				<?php
						$wall_img ="";
						if( !empty($workshop_wallimage) )
						{
							$wall_img = $workshop_wallimage[0]->wallimage;
						}
						else
						{
							$wall_img = "woodenbg.jpg";
						}

				?>
				<img src="<?php echo base_url('public/default/images/'.$wall_img); ?>" alt="Diamond" class="bg"/>

				<?php
				 $is_user_book_ws = can_user_book_workshop(); //Can user book the workshop (login_helper.php file)

				 //workshop_professional_detail
				 $users_id = 0;
				 if(!empty($workshop_professional_detail)){
						$users_id = $workshop_professional_detail[0]['users_id'];
						$fname = $workshop_professional_detail[0]['fname'];
						$lname = $workshop_professional_detail[0]['lname'];
						$user_image = $workshop_professional_detail[0]['user_image'];
						$company_title = $workshop_professional_detail[0]['company_title'];
						$company_name = $workshop_professional_detail[0]['company_name'];
						$company_description = $workshop_professional_detail[0]['company_description'];

						$professional_name = ucfirst($fname);

						if(!empty($lname)){
							 $professional_name = ucfirst($fname). ' '. ucfirst($lname[0]).'.';
						}

						if( $login_user_id == $users_id ){
							 $is_same_professional_logged_in = 1;
						}
						else{
							 $is_same_professional_logged_in = 0;
						}
				 }

				 $show_btn = 1;
				 if($is_login == 1 && $user_role_id == PROFESSIONAL && $is_same_professional_logged_in == 1){
						$show_btn = 0;
				 }

				 // workshop basic detail start
				 $workshop_id = 0;
				 $workshop_title = '';
				 $number_of_attendees = '';
				 $price_per_attendee = '0.00';
				 $status = 0;

				 $is_manual = 0;
				 if(!empty($workshop)){
						$workshop_id = $workshop[0]['id'];
						$workshop_title = $workshop[0]['title'];
						$workshop_slug = $workshop[0]['slug'];
						$number_of_attendees = $workshop[0]['number_of_attendees'];

						$price_per_attendee = $workshop[0]['price_per_attendee'];
						/*$tax_amt = round($price_per_attendee * TAX_AMOUNT/100, 2);
						$price_per_attendee = floatval($price_per_attendee) + floatval($tax_amt);*/

						$status = $workshop[0]['status'];
						$is_workshop_suspended = $workshop[0]['is_suspended'];
						$is_manual = $workshop[0]['is_manual'];
				 }

				 $price_per_attendee = replace_decimal_to_comma_in_price( $price_per_attendee );

				 $book_btn_str = '<a rel="external" href="javascript:;" onclick=not_auth_user() class="btn"> réserver </a>';
				 if($is_workshop_suspended == 0 && $show_btn == 1 && $is_user_book_ws == 1){
						$book_btn_str = '<a rel="external" href="'.base_url().'atelier/reserver/'.$workshop_slug.'" class="btn">réserver</a>';
				 }
				 else if($is_workshop_suspended == 1){
						$book_btn_str = '<a rel="external" href="javascript:;" onclick=workshop_suspended() class="btn"> réserver </a>';
				 }

				 // get number of joined attendees start
				 $number_of_joined_attendees = 0;
				 if(!empty($joined_attendees_count)){
						$number_of_joined_attendees = $joined_attendees_count;
				 }

				 //workshop_images start
				 $workshop_images_str ='';
				 if(!empty($workshop_images)){
						for($l=0;$l<count($workshop_images);$l++){
							 $image_name = $workshop_images[$l]['image_name'];
							 $image_path = $workshop_images[$l]['image_path'];
							 $image_file_path = get_uploaded_image_file_path('510x308/'.$image_name);
							 if( !empty($image_name) && file_exists($image_file_path) ){
									$workshop_images_str .= '<li><img src="'.base_url().'uploads/'.$image_name.'" alt="'.$image_name.'"/></li>';
									//$workshop_images_str .= '<li><img src="'.base_url().'uploads/510x308/'.$image_name.'" alt="'.$image_name.'"/></li>';
							 }else{
									$workshop_images_str .= '<li><img src="'.base_url().'public/default/images/no_image/default-510x308.jpg" alt="'.$image_name.'"/></li>';
							 }
						}

				 }

				 //workshop_availability start
				 $workshop_date = 'Jan 1, 1970';
				 $from_time = '00:00:00';
				 $end_time = '00:00:00';
				 $is_reccurent = 0;
				 if(!empty($workshop_availability)){
						$workshop_date = $workshop_availability[0]['workshop_date'];
						$from_time     = $workshop_availability[0]['from_time'];
						$end_time      = $workshop_availability[0]['end_time'];
						$is_reccurent  = $workshop_availability[0]['is_reccurent'];
						$available_seats  = $workshop_availability[0]['available_seats'];?>
						<script>

							 $(function(){
									$(".common_available_seats").html('<?php echo $available_seats;?>');
							 });
						</script> <?php
				 }

				 setlocale (LC_TIME, 'fr_FR.utf8','fra'); // set location to french
				 $workshop_date = strftime("%d %B %Y", strtotime($workshop_date)); // format 01 Mars 2016

				 // $workshop_date = date('d M Y', strtotime($workshop_date));

				 $from_time_array = explode(":", $from_time);
				 $from_time = is_array($from_time_array) ? $from_time_array[0] : "";

				 $end_time_array = explode(":", $end_time);
				 $end_time = is_array($end_time_array) ? $end_time_array[0] : "";

				 // workshop_description start
				 $workshop_description_str = '';
				 if(!empty($workshop_description)){
						$workshop_description_str = $workshop_description[0]['descrition'];
				 }

				 // workshop_characteristics
				 $workshop_characteristics_str = '';
				 $class = "";
				 if(!empty($workshop_characteristics)){

						for($i=0; $i<count($workshop_characteristics);$i++ ) {
							 $workshop_characteristicsObj = $workshop_characteristics[$i];
							 $characteristics_id = $workshop_characteristicsObj['characteristics_id'];
							 $characteristics_name = $workshop_characteristicsObj['name'];
							 $characteristics_icon = $workshop_characteristicsObj['icon'];
							 switch ($characteristics_id) {
									case 1:
										 $class = "ateliers";
										 break;
									case 2:
										 $class = "pmr";
										 break;
									case 3:
										 $class = "particuliers";
										 break;
									case 4:
										 $class = "pour";
										 break;
									case 5:
										 $class = "experts";
										 break;
									case 6:
											$class = "adultes";
											break;
									case 7:
		 									$class = "adultes-enfants";
		 								  break;
							 }

							 $workshop_characteristics_str .= '<li>'.
																									 '<span class="'.$class.'"></span>'.
																									 '<p>'.$characteristics_name.'</p>'.
																								'</li>';
						}
				 }

				 // workshop_location: address of workshop where it will held
				 $address = '';
				 $latitude = '';
				 $longitude = '';
				 if(!empty($workshop_location)){
						$address = $workshop_location[0]['address'];
						$latitude = $workshop_location[0]['latitude'];
						$longitude = $workshop_location[0]['longitude'];
				 }

			?>
			<div class="ribbon-tag">
				 <div class="container">
						<div class="base">
							 <p>
									<span><?php echo $price_per_attendee; ?><span>€</span></span>encore<br/><b class="common_available_seats"><?php echo ($number_of_attendees - $number_of_joined_attendees); ?></b><br/>places
							 </p>
						</div>
						<div class="left_corner"></div>
						<div class="right_corner"></div>
				 </div>
			</div>

			<div class="slider">
				 <h1><?php echo $workshop_title; ?> </h1>
				 <div class="flexslider">
						<ul class="slides">

							 <?php

							 if($workshop_images_str!=''){
									echo $workshop_images_str;
							 } else {
									echo '<li><img src="'.base_url().'public/default/images/no_image/default-510x308.jpg" alt="default_image"/></li>';
							 }?>

						</ul>
				 </div>
				 <?php echo $book_btn_str; ?>

				 <p>prochain atelier - <?php echo $workshop_date; ?> de <?php echo $from_time; ?> à <?php echo $end_time; ?>h</p>
			</div>
	 </div>
	 <!--primary close-->
	 <div class="secondary quest-secondary portion-nav">
			<ul>
				 <li id="l_atelier_li" class="active common_nav"><a href="javascript:;">L’atelier</a></li>
				 <li id="le_lieu_li" class="common_nav"><a href="javascript:;">Le lieu</a></li>
				 <li id="l_org_li" class="common_nav"><a href="javascript:;">L’organisateur</a></li>
				 <li id="l_mat_li" class="common_nav"><a href="javascript:;">Le matériel</a></li>
				 <li id="les_avis_li" class="common_nav"><a href="javascript:;">Les avis</a></li>
			</ul>
			<div class="grab2-right">
				 <h4 id="latelier">L’atelier</h4>
				 <div class="content">
						<?php echo html_entity_decode($workshop_description_str); ?>
				 </div>
				 <div class="content">
						<ul>
							 <?php echo $workshop_characteristics_str; ?>
						</ul>
						<div class="clearfix"></div>
				 </div>
				 <h4 id="le_lieu">Le lieu</h4>

				 <div class="content paddingbtm0">
						<?php echo $address; ?>
				 </div>

			</div>
			<div class="clearfix"></div>
			<!--secondary close-->
			<div id="map" style="width:100%;height:400px;"></div>
			<script>

				 function initMap() {
						var mapProp = {
							 center: {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>},
							 zoom:14,
							 mapTypeId: google.maps.MapTypeId.ROADMAP,
							 //zoomControlOptions: {
  							 //						style:google.maps.ZoomControlStyle.SMALL // Change to SMALL to force just the + and - buttons.
							 // 				},
							 scrollwheel: false
						};

						var map = new google.maps.Map(document.getElementById("map"),mapProp);

						var homeLatlng = new google.maps.LatLng(<?php echo $latitude; ?>,<?php echo $longitude; ?>);

						var infowindo_content = '<?php echo preg_replace("~[\r\n\t]~", "", $address);?>';
						infowindo_content = String(infowindo_content).replace(/'/g, '&#39;');

						var infowindow = new google.maps.InfoWindow({
							 content: infowindo_content,
							 map: map,
							 // position: homeLatlng
						});


						var marker = new google.maps.Marker({
							 position: {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>},
							 title:'Click to zoom'
						});

						infowindow.open(map,marker);

						marker.addListener('mouseover', function() {
							 infowindow.open(map, marker);
						});

						marker.addListener('mouseout', function() {
							 infowindow.close();
						});

						marker.setMap(map);

						// Zoom to 9 when clicking on marker
						google.maps.event.addListener(marker,'click',function() {
							map.setZoom(16);
							map.setCenter(marker.getPosition());
						});

				 }
			</script>

			<div class="grab2-right">
				 <h4 id="l_org">L’organisateur</h4>
				 <div class="testimonial" >
				 <?php
						$image_file_path = get_uploaded_image_file_path('449x449/'.$user_image);
							 if( !empty($user_image) && file_exists($image_file_path) ){?>
									<img onclick="redirect_to_professional(<?php echo $users_id; ?>)" src="<?php echo base_url();?>uploads/449x449/<?php echo $user_image; ?>" alt="profilepic"/>
							 <?php } else { ?>
									<img onclick="redirect_to_professional(<?php echo $users_id; ?>)" src="<?php echo base_url();?>public/default/images/no_image/default-450x450.jpg" alt="profilepic"/>
							 <?php } ?>
						<p><?php echo $professional_name; ?></p>
						<?php echo $cd = empty($company_description)? '': '<h3 class="text">'.$company_description.'</h3>'; ?>

				 </div>
				 <div class="avis fontweight700">Contacter l’organisateur</div>
				 <form id="send_message_form" name="send_message_form" method="post" >
						<input type="hidden" id="workshop_id" name="workshop_id" value="<?php echo $workshop_id; ?>" />
						<input type="hidden" id="to_users_id" name="to_users_id" value="<?php echo $users_id; ?>" />
						<div  id="errorsDiv_subject"></div>
						<input type="text" id="subject" name="subject" onclick="clear_error_msgs('errorsDiv_subject');" placeholder="Quel est le sujet de votre demande ?"/>
						<div id="errorsDiv_message_to_professional"></div>
						<textarea id="message_to_professional" name="message_to_professional" onclick="clear_error_msgs('errorsDiv_message_to_professional');" placeholder="Quel est votre message?"></textarea>
						<?php
							 if( $is_workshop_suspended == 0 && $show_btn == 1 ){
						?>
							 <input value="envoyer" type="submit" onclick="if(yav.performCheck('send_message_form', send_message_rules, 'inline')){return true}else{ return false};" />
						<?php } else if( $is_same_professional_logged_in == 1 ) { ?>
							 <input value="envoyer" type="submit" onclick="not_allow_to_contact(); return false;" />
						<?php } else if( $is_workshop_suspended == 1 ) { ?>
							 <input value="envoyer" type="submit" onclick="workshop_suspended(); return false;" />
						<?php } else { ?>
							 <input value="envoyer" type="submit" onclick="not_auth_user(); return false;" />
						<?php } ?>
				 </form>
			</div>

			<?php if(!empty($workshop_equipments)){ ?>
			<div class="grab2-right" style="padding-bottom:0px;">
				 <h4 id="l_mat" >Le matériel</h4>
				 <div class="content">
						<?php echo $workshop_equipments[0]['equipment_text']; ?>
				 </div>
			</div>
				 <?php if($workshop_equipments[0]['equipment_image']!=''){ ?>
				 <?php
						$image_file_path = get_uploaded_image_file_path($workshop_equipments[0]['equipment_image']);
							 if( !empty($user_image) && file_exists($image_file_path) ){?>
									<img class="text-fields mrgn0" src="<?php echo base_url();?>uploads/<?php echo $workshop_equipments[0]['equipment_image']; ?>" alt="equipment">
							 <?php } else { ?>
									<img class="text-fields mrgn0" src="<?php echo base_url();?>public/default/images/img-rule.jpg" alt="equipment">
							 <?php } ?>

				 <?php } ?>
			<?php } ?>

			<div class="grab2-right">
				<h4 id="les_avis" >Les avis</h4>
				<div class="star">
					<?php
						function star_str($number_of_stars){
							error_reporting(0);
							$star_str = '';
							$half_star_flag = true;
							for($j=0;$j<5;$j++){

								 if( $j < (int)$number_of_stars ) {
										$star_str .= '<img src="'.base_url().'public/default/images/active-star.png" alt="Favourite">';
								 } else {
										if(is_float( $number_of_stars )){
											 $decmpart = explode( ".", $number_of_stars."" )[1];
											 if($decmpart != 0 && $half_star_flag == true ){
													$star_str .= '<img src="'.base_url().'public/default/images/star-h.png" alt="Favourite">';
													$half_star_flag = false;
											 }
											 else{
													$star_str .= '<img src="'.base_url().'public/default/images/star.png" alt="Favourite">';
											 }
										}
										else{
											 $star_str .= '<img src="'.base_url().'public/default/images/star.png" alt="Favourite">';
										}
								 }
							}
							return $star_str;
						}
					?>
					<span>
						 <?php echo star_str(round( $workshop_global_reviews[0]['avg_rating'], 1)); ?>
					</span>
					<b><?php echo round($workshop_global_reviews[0]['total_reviews']); ?> avis</b>
					<div id="workshop_review_div" >
						<?php
							$customer_reviews_str = '';
							if(!empty($workshop_reviews)){
								for($k=0;$k<count($workshop_reviews);$k++)
								{
									$colorclass = '';
									if($k%2!=0){
										$colorclass = 'colorgreen';
									}

									$workshop_reviews_obj = $workshop_reviews[$k];
									$rating = round($workshop_reviews_obj['rating']);
									$rating_str = star_str($rating);
									$name = $workshop_reviews_obj['name'];
									$created_at = $workshop_reviews_obj['created_at'];
									$review_date = date('m/d/Y', strtotime($created_at));
									$description = $workshop_reviews_obj['description'];
									$customer_reviews_str .= '<div class="avis">'.
																	'<b><span class=" fontweight700 reviews '.$colorclass.'">'.$name.' :</span><br/><small>'.$review_date.'</small></b>'.
																	'<span>'.
																		 $rating_str.
																	'</span>'.
																	'<p class="reviews" >'.$description.'</p>'.
															 '</div>';
								}
							}
							echo $customer_reviews_str;
						?>
						<!--avis close-->
					</div>
				</div>
				<?php if( is_user_login() && $has_user_booked_this_workshop == 'SUCCEEDED' ) { ?>
					<form id="review_form" name="review_form" method="post">
						<div class="avis scroll_to_form">
							<b class="fontweight700">ajouter un avis</b>
							<div  id="errorsDiv_reviewer_rating"></div>
							<span class="fl">
								<img class="star" id="star_1" onclick="clear_error_msgs('errorsDiv_reviewer_rating');star_rating(1)" src="<?php echo base_url();?>public/default/images/star.png" alt="Favourite">
								<img class="star" id="star_2" onclick="clear_error_msgs('errorsDiv_reviewer_rating');star_rating(2)" src="<?php echo base_url();?>public/default/images/star.png" alt="Favourite">
								<img class="star" id="star_3" onclick="clear_error_msgs('errorsDiv_reviewer_rating');star_rating(3)" src="<?php echo base_url();?>public/default/images/star.png" alt="Favourite">
								<img class="star" id="star_4" onclick="clear_error_msgs('errorsDiv_reviewer_rating');star_rating(4)" src="<?php echo base_url();?>public/default/images/star.png" alt="Favourite">
								<img class="star" id="star_5" onclick="clear_error_msgs('errorsDiv_reviewer_rating');star_rating(5)" src="<?php echo base_url();?>public/default/images/star.png" alt="Favourite">
							</span>
						</div>
					 	<!--avis close-->
						<?php
							$name = "";
							$email = "";
							$description = "";
							$rating = 0;
							if( !empty($_COOKIE['review_form_data']))
							{
									$review_form_data = json_decode($_COOKIE['review_form_data']);
									$rating = $review_form_data->rating;
									// $name = $review_form_data->name;
									// $email = $review_form_data->email;
									$description = $review_form_data->description; ?>

									<script type="text/javascript">
											$(function(){
													$('body,html').animate({ scrollTop: $(".star").position().top }, 1000);
													clear_error_msgs('errorsDiv_reviewer_rating');
													star_rating(<?php echo $rating; ?>);
											});
									</script> <?php
							}

							if( !empty( get_logged_in_user_fullname() ) ){
									$name = get_logged_in_user_fullname();
							}
							if( !empty( get_logged_in_user_email() ) ){
									$email = get_logged_in_user_email();
							}
						?>
						<input type="hidden" id="reviewer_rating" name="reviewer_rating" value="<?php echo $rating; ?>" />
						<div  id="errorsDiv_reviewer_name"></div>
						<input class="" type="text" id="reviewer_name" name="reviewer_name" onclick="clear_error_msgs('errorsDiv_reviewer_name');" placeholder="Votre nom" value="<?php echo $name; ?>"/>
						<div  id="errorsDiv_reviewer_email"></div>
						<input class="" type="text" id="reviewer_email" name="reviewer_email" onclick="clear_error_msgs('errorsDiv_reviewer_email');" placeholder="Votre email" value="<?php echo $email; ?>"/>
						<div  id="errorsDiv_reviewer_text"></div>
						<textarea  id="reviewer_text" name="reviewer_text" onclick="clear_error_msgs('errorsDiv_reviewer_text');" placeholder="Quel est votre commentaire ?"><?php echo $description; ?></textarea>
						<div class="grap2submit">

							 <?php if( $is_workshop_suspended == 0 && $show_btn == 1 ){ ?>
									<input value="envoyer" onclick="if(yav.performCheck('review_form', review_rules, 'inline')){submit_review()}else{ return false};" type="button" class="reviewsubmit"/>
							 <?php } else if( $is_same_professional_logged_in == 1 ) { ?>
									<input value="envoyer" type="submit" onclick="not_allow_to_review(); return false;" />
							 <?php } else if( $is_workshop_suspended == 1 ) { ?>
									<input value="envoyer" type="submit" onclick="workshop_suspended(); return false;" />
							 <?php } else { ?>
									<input value="envoyer" type="submit" onclick="not_auth_user(); return false;" />
							 <?php } ?>

							 <div class="exclamation-cntr">
									<span class="exclamation"><img src="<?php echo base_url();?>public/default/images/exclamation.png" alt="exclamation"></span>
									<span class="popupbox">
										 <span class="heading">SIGNALER CET ATELIER</span>
										 <input type="radio" id="radio01" name="report_abuse" value="Le contenu d’un atelier est illicite ou choquant" /><label for="radio01"><span></span>Le contenu d’un atelier est illicite ou choquant</label>
										 <input type="radio" id="radio02" name="report_abuse" value="Je suis professionnel et revendique cet atelier" /><label for="radio02"><span></span>Je suis professionnel et revendique cet atelier</label>
										 <input type="radio" id="radio03" name="report_abuse" value="L'adresse est mal positionnée sur le plan" /><label for="radio03"><span></span>L'adresse est mal positionnée sur le plan</label>
										 <input type="radio" id="radio04" name="report_abuse" value="Une ou des photos utilisées m’appartiennent" /><label for="radio04"><span></span>Une ou des photos utilisées m’appartiennent</label>
												<a id="btn_abuse" href="javascript:;" >SIGNALER</a>
												<span style="display: none;" id="loader-icon"><img width="30" src="http://192.168.0.147/yearn/www/public/default/images/LoaderIcon.gif"></span>
									</span>

							 </div>
						</div>
					</form>
				<?php } ?>
			</div>
	 </div>
	 <!-- /Content Start-->
</div>
<!--secondary close-->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2v2D7sMTGBO-KKSptlz0r7L01KXZeZL8&callback=initMap"></script>
<?php $this->load->view('footer_inner'); ?>
