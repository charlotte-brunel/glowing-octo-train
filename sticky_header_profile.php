<!--sticky-header-fields after login close-->
<?php
    $uri_segment = $this->uri->segment(1);
    if( $uri_segment == "panier" || $uri_segment == "payment"){ ?>
    <style type="text/css">
        .statsmenu{ background: none; }
    </style>
<?php } ?>
<?php if ( is_user_login() ) { ?>
<section class="sticky-header sticky">
    <h3 class="none">.</h3>
    <div class="wrapper pad">
        <!--sticky-header-set close-->

        <div class="sticky-header-div get-hieght">
            <div class="wrapper">


                <a href="<?php echo base_url(); ?>" class="return"> RETOUR À L'ACCUEIL </a>

                <?php if ( $this->session->userdata['front_user']['roles_id'] == ATTENDEE ) { ?>
                    <a href="<?php echo base_url('account/previous_orders'); ?>" class="logo">
                        <img alt="logo" src="<?php echo get_layout_url('images')?>/my_account_logo.png">
                    </a>
                <?php } else { ?>
                    <a href="<?php echo base_url('account'); ?>" class="logo">
                        <img alt="logo" src="<?php echo get_layout_url('images')?>/my_account_logo.png">
                    </a>
                <?php } ?>

                <div class="right-side-header">


                    <?php if(! is_user_login() ) { ?>

                        <a rel="external" href="<?php echo base_url('login'); ?>">
                            <img src="<?php echo base_url();?>public/default/images/logoutbtn.png" alt="Connexion / inscription" class="logoutbtn">Connexion / inscription
                        </a>
                    <?php } else { ?>

                        <a href="<?php echo base_url('user/logout'); ?>" rel="external">
                            <img src="<?php echo base_url();?>public/default/images/logoutbtn.png" alt="se déconnecter" class="logoutbtn">se déconnecter
                        </a>
                        <div onclick="redirect_to_myaccount();" class="logininfo_box">
                            <?php
                                $fname = $this->session->userdata['front_user']['fname'];
                                $lname = $this->session->userdata['front_user']['lname'];
                                $user_image = (isset($this->session->userdata['front_user']['image']) && !empty($this->session->userdata['front_user']['image']))?$this->session->userdata['front_user']['image']:'';

                                if( !empty($user_image) ){
                                   $user_image_str = '<li><img id="profile_pic" src="'.base_url().'uploads/75x75/'.$user_image.'" alt="-"/></li>';
                                }else{
                                   $user_image_str = '<li><img id="profile_pic" src="'.base_url().'public/default/images/no_image/default-450x450.jpg" alt="'.$user_image.'"/></li>';
                                }
                            ?>
                            <div class="logininfo_img">
                               <?php echo $user_image_str; ?>
                            </div>
                            <p class="username"><?php echo $fname.' '.$lname; ?></p>
                        </div>

                        <?php if ( $this->session->userdata['front_user']['roles_id'] == ATTENDEE ) {?>
                            <a rel="external" href="<?php echo base_url('panier'); ?>" class="fr shop" >
                                <img alt="shop" src="<?php echo get_layout_url('images')?>/shop.png">
                            </a>
                        <?php }
                    } ?>

                    <div class="social">
                        <span id="social1">
                            <img src="<?php echo base_url();?>public/default/images/social.png" alt="Social Share">
                        </span>
                        <ul id="social-open1">

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
                    <span class="fr cancel" style="display:none;">
                        <img src="<?php echo base_url();?>public/default/images/cancel.png" alt="cancel"/>
                    </span>

                    <span class="fr search">
                        <img src="<?php echo base_url();?>public/default/images/search.png" alt="search"/>
                    </span>
                </div>

            </div>
            <!--wrapper close-->
        </div>
        <!--sticky-header-div close-->

        <div class="div-nav div-invisible">
            <span class="mobilemenu">
                <img src="<?php echo base_url();?>public/default/images/menu.png" alt="menu"/>
            </span>
            <a href="<?php echo base_url(); ?>" class="logo">
                <img alt="logo" src="<?php echo base_url();?>public/default/images/white-logo.png">
            </a>
            <div class="right-side-header">
                <span class="fr cancel" style="display:none;">
                    <img src="<?php echo base_url();?>public/default/images/cancel.png" alt="cancel"/>
                </span>
                <span class="fr search">
                    <img src="<?php echo base_url();?>public/default/images/search.png" alt="search"/>
                </span>
            </div>
            <?php if( is_user_login() && $this->session->userdata['front_user']['roles_id'] == ATTENDEE ) { //if attendee user logged in ?>

                <a class="fr shop marginbtm" href="<?php echo base_url('panier'); ?>" style="margin-right: 0px;">
                    <img src="<?php echo get_layout_url('images')?>/shop.png" alt="shop">
                </a>

            <?php } ?>
        </div>
        <!--div nav-->
    </div>
    <!--wrapper close-->
    <div class="sticky-header-fields fields-inner" style="display:none;">
        <div class="wrapper">
            <?php echo form_open( base_url('?q=search'), array('method' => 'get', 'name' => 'search_form', 'id' => 'search_form')); ?>

                <input class="first-input" type="text" name="search_title" id="search_title" value="<?php if(!empty($this->input->get('search_title'))){ echo $this->input->get('search_title'); } ?>" placeholder="Rechercher une activité"/>
                <input type="text" class="datepicker" name="search_date" id="search_date" value="<?php if(!empty($this->input->get('search_date'))){ echo $this->input->get('search_date'); } ?>" placeholder="Date" readonly=""/>
                <div class="feilds-search">
                    <?php
                        if( !empty($this->input->get('search_from_time')) && !empty($this->input->get('search_to_time')) ){
                            $m = $this->input->get('search_from_time')." - ".$this->input->get('search_to_time')."H";
                        }
                        else{
                            $m= "";
                        }
                    ?>
                    <input readonly type="text" value="<?php echo $m; ?>" class="label" placeholder="Heure">
                    <div class="feilds-search-inner">
                        <input type="text" maxlength="2" onkeyup="if (/\D/g.test(this.value)) {this.value = this.value.replace(/\D/g,''); $('.overlay').show(); $('.modelbox_content').text('Enter digits only');}" name="search_from_time" id="search_from_time" value="<?php if(!empty($this->input->get('search_from_time'))){ echo $this->input->get('search_from_time'); } ?>" placeholder="De"/>
                        <input type="text" maxlength="2" onkeyup="if (/\D/g.test(this.value)) {this.value = this.value.replace(/\D/g,''); $('.overlay').show(); $('.modelbox_content').text('Enter digits only');}" name="search_to_time" id="search_to_time" value="<?php if(!empty($this->input->get('search_to_time'))){ echo $this->input->get('search_to_time'); } ?>" placeholder="À"/>
                    </div>
                </div>
                <input type="submit" value="Et hop !"/>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="statsmenu"><?php
        if ( $this->session->userdata['front_user']['roles_id'] == PROFESSIONAL ) { //professional nav menu ?>
            <ul class="topnav">
                <li id="topnav_info" class="select">
                    <a href="<?php echo base_url('account')?>">Mes informations</a>
                </li>
                <li id="topnav_atelier">
                    <a href="<?php echo base_url('workshop/show')?>">Mes ateliers</a>
                </li>
                <li id="topnav_history">
                    <a href="<?php echo base_url('account/previous_orders')?>">Mon historique</a>
                </li>
                <!-- <li id="topnav_stat">
                    <a href="javascript:;">Mes stats</a>
                </li> -->
                <li id="topnav_message">
                    <a href="<?php echo base_url('mailbox')?>">Ma messagerie</a>
                </li>

                <!-- <li id="topnav_admin_message">
                    <a href="<?php echo base_url('mailbox/admin')?>">Admin messagerie</a>
                </li> -->

                <li class="icon">
                    <a href="javascript:void(0);" style="font-size:15px;" onclick="myFunction()">☰</a>
                </li>
            </ul>
        <?php }
            else if( $this->session->userdata['front_user']['roles_id'] == ATTENDEE ){ //attendee nav menu
                $uri_segment = $this->uri->segment(1);
                if( $uri_segment != "panier" && $uri_segment != "payment"){ ?>
                    <ul class="topnav">
                        <li id="topnav_history" class="select">
                            <a href="<?php echo base_url('account/profile')?>">Mon historique</a>
                        </li>
                        <li id="topnav_info">
                            <a href="<?php echo base_url('account/coordonnees')?>">Mes coordonnées</a>
                        </li>
                        <li id="topnav_message">
                            <a href="<?php echo base_url('mailbox')?>">Ma messagerie</a>
                        </li>

                       <!--  <li id="topnav_admin_message">
                            <a href="<?php echo base_url('mailbox/admin') ?>">Admin messagerie</a>
                        </li> -->

                        <li class="icon">
                            <a href="javascript:void(0);" style="font-size:15px;" onclick="myFunction()">☰</a>
                        </li>
                    </ul> <?php
                }
            }
        ?>
    </div>
</section>
<!--sticky-header after login close-->

<?php } else { ?>

<!--sticky-header-fields before login close-->
<section class="sticky-header">
    <h3 class="none">.</h3>
    <div class="wrapper text-center">

        <!--sticky-header-set close-->

        <div class="sticky-header-div get-hieght">
            <div class="wrapper">
                <div class="social"> <span id="social1"><img src="<?php echo base_url();?>public/default/images/social.png" alt="Social Share"></span>
                    <ul id="social-open1">
                        <!-- <li>
                            <a rel="external" href="">
                                <img alt="Instagram" src="<?php //echo get_layout_url('images')?>/instagram.png"/>
                            </a>
                        </li> -->
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
                <a href="<?php echo base_url(); ?>" class="logo">
                    <img alt="logo" src="<?php echo base_url();?>public/default/images/white-logo.png">
                </a>
                <div class="right-side-header">
                    <span class="access-pro-link" onclick="location.href='<?php echo base_url('inscription/professional/1'); ?>'">
                        Accès Pro
                    </span>
                    <a href="<?php echo base_url('login'); ?>">
                        <img src="<?php echo base_url();?>public/default/images/conex.png" alt="Connexion / inscription">Connexion / inscription
                    </a>
                </div>
            </div>
            <!--wrapper close-->
        </div>
        <!--sticky-header-div close-->

        <div class="div-nav div-invisible">
            <span onclick="open_headermenu()">
                <img src="<?php echo base_url();?>public/default/images/menu.png" alt="menu"/>
            </span>
            <a href="<?php echo base_url(); ?>" class="logo">
                <img alt="logo" src="<?php echo base_url();?>public/default/images/white-logo.png">
            </a>
            <span class="fr cancel" style="display:none;">
                <img src="<?php echo base_url();?>public/default/images/cancel.png" alt="cancel"/>
            </span>
            <span class="fr search">
                <img src="<?php echo base_url();?>public/default/images/search.png" alt="search"/>
            </span>
        </div>
        <!--div nav-->

        <div class="sticky-header-fields mobilesearch">
            <?php echo form_open( base_url('?q=search'), array('method' => 'get', 'name' => 'search_form', 'id' => 'search_form')); ?>

                <input class="first-input" type="text" name="search_title" id="search_title" value="<?php if(!empty($this->input->get('search_title'))){ echo $this->input->get('search_title'); } ?>" placeholder="Rechercher une activité"/>
                <input type="text" class="datepicker" name="search_date" id="search_date" value="<?php if(!empty($this->input->get('search_date'))){ echo $this->input->get('search_date'); } ?>" placeholder="Date" readonly=""/>
                <div class="feilds-search">
                    <?php
                        if( !empty($this->input->get('search_from_time')) && !empty($this->input->get('search_to_time')) ){
                            $m = $this->input->get('search_from_time')." - ".$this->input->get('search_to_time')."H";
                        }
                        else{
                            $m= "";
                        }
                    ?>
                    <input readonly type="text" value="<?php echo $m; ?>" class="label" placeholder="Heure">
                    <div class="feilds-search-inner">
                        <input type="text" maxlength="2" onkeyup="if (/\D/g.test(this.value)) {this.value = this.value.replace(/\D/g,''); $('.overlay').show(); $('.modelbox_content').text('Enter digits only');}" name="search_from_time" id="search_from_time" value="<?php if(!empty($this->input->get('search_from_time'))){ echo $this->input->get('search_from_time'); } ?>" placeholder="De"/>
                        <input type="text" maxlength="2" onkeyup="if (/\D/g.test(this.value)) {this.value = this.value.replace(/\D/g,''); $('.overlay').show(); $('.modelbox_content').text('Enter digits only');}" name="search_to_time" id="search_to_time" value="<?php if(!empty($this->input->get('search_to_time'))){ echo $this->input->get('search_to_time'); } ?>" placeholder="À"/>
                    </div>
                </div>
                <input type="submit" value="Et hop !"/>
            <?php echo form_close(); ?>
        </div>
        <!--sticky-header-fields close-->
    </div>
    <!--wrapper close-->
</section>
<!--sticky-header-fields before login close-->
<?php } ?>
