<?php $this->load->view('header_inner'); ?>
<?php $this->load->view('sticky_header_profile'); ?>
<script>
    $( function (){
        setTimeout(function () {
            $("#profile_pic").one("load", function() {
                $(".profile_name").hide();
                $("#awesome_name").show();
            }).each(function() {
                if(this.complete){
                    $(this).load();
                    $(".profile_name").hide();
                    $("#name_of_user").show();
                }
            });
        }, 1000);
    });

    function nav_change(step, status){
        clearErrorsDiv();
        //if(status)
        //$("#errorDiv_professional_form").html("");

        $('.second_menu').find('li').removeClass('active');
        switch(step){
            case 'step1':
                $('.steps').hide();
                $('#subscription_div').hide();
                $('#step2').show();
                $('.second_menu').find('li').eq(1).addClass('active');
            break;
            case 'step2':
                $('.steps').hide();
                $('#subscription_div').hide();
                $('#step3').show();
                $('.second_menu').find('li').eq(2).addClass('active');
            break;
            default:
                $('.steps').hide();
                $('#subscription_div').hide();
                $('#step1').show();
                $('.second_menu').find('li').eq(0).addClass('active');
            break;
        }

        // $("html, body").animate({scrollTop: "0px"}, 1000);
        scroll_to_element(0);
    }

    function clearErrorsDiv(){
        $("#errorsDiv").html("");
        $("#errorDiv_professional_form").html("");
        $("#errorDiv_server_side").html("");
        // $("html, body").animate({scrollTop: "0px"}, 1000);
        scroll_to_element(0);
    }

    function clearErrorsDivByID(id){
        $("#"+id).html("");
        // $("html, body").animate({scrollTop: "0px"}, 1000);
        scroll_to_element(0);
    }

    function remove_dp(uid) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: js_base_url + "account/delete_profile_picture",
            data: {"uid" : uid},
            success: function(response){
                $(".overlay").show();
                $(".modelbox_content").text(response['message']);
                if( response['status'] == true){
                    $(".close").click(function(){
                        location.reload();
                    });
                }
            },
            error: function(response) {
                $(".overlay").show();
                $(".modelbox_content").text("Something went wrong!");
            }
        });
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

    $(document).on('change', '#user_image', function(){
        files = this.files;
        size = files[0].size;

        if( size > 1000000){
            $(".overlay").show();
            $(".modelbox_content").text("S'il vous plaît télécharger le fichier image à moins de 1 Mo");
            document.getElementById("user_image").value = "";
            return false;
        } else {
            $(".image-selected-text").text("Image chargée");
            $(".image-selected-text").show();
        }
        return true;
    });

</script>
<div data-role="page" style="display:none;"></div>
<div class="contact panier">
   <div class="primary contact-primary cso-left">
      <img src="<?php echo base_url();?>public/default/images/bg-star.jpg" alt="Diamond" class="bg"/>
      <div class="drag-container" >
      <?php

        $user_image = $query['user_image'];
        $image_file_path = get_uploaded_image_file_path('449x449/'.$user_image);
        if( !empty($user_image) && file_exists($image_file_path) ){ ?>
            <a id="remove_user_img" href="javascript:void(0);" rel="external" onclick="return remove_dp(<?php echo $query['id'] ?>);">Close</a>
        <?php  } else {  ?>
            <a id="remove_user_img" href="javascript:void(0);" style="display:none;" rel="external" onclick="return remove_dp(<?php echo $query['id'] ?>);">Close</a>
        <?php  }  ?>

         <div class="imgcontnr" onclick="jQuery('#user_image').trigger('click')">
            <?php

            $fname_lname = ucfirst($query['fname']).' '.ucfirst($query['lname'][0]).'.';

            $user_image = $query['user_image'];
            $image_file_path = get_uploaded_image_file_path('449x449/'.$user_image);
            if( !empty($user_image) && file_exists($image_file_path) ){
               $user_image_str = '<li><img id="profile_pic" src="'.base_url().'uploads/449x449/'.$user_image.'" alt="'.$fname_lname.'"/></li>';
            }else{
               $user_image_str = '<li><img id="profile_pic" src="'.base_url().'public/default/images/no_image/default-450x450.jpg" alt="'.$user_image.'"/></li>';
            }

            echo $user_image_str;
            ?>

         </div>
         <p class="profile_name" id="awesome_name" >Perfect, you are awesome</p>
         <p class="profile_name" id="name_of_user" style="display:none;"><?php echo $fname_lname; ?></p>
         <p class="image-selected-text"></p>
      </div>
      <div class="drag-image" style="display:none;">
         <div class="imgcontnr">
            <img src="<?php echo base_url();?>public/default/images/drag-image.png" alt="Drag Image"/>
         </div>
      </div>
   </div>
   <!--primary close-->
   <div class="secondary quest-secondary panier-secondary">
      <div class="quest-fields">
         <!-- <span>Fiche particulier</span> -->
         <div class="register-container-row text-left mfform">
            <div id="errorDiv_professional_form"></div>

            <?php
            // Get Flash data on view
            if(!empty($this->session->flashdata('success_msg'))){
               echo '<div id="errorDiv_server_side" class="success_msg"><ul><li>'.$this->session->flashdata('success_msg').'</li></ul></div>';
            }
            if(!empty($this->session->flashdata('error_msg'))){
               echo '<div id="errorDiv_server_side" class="error_msg"><ul><li>'.$this->session->flashdata('error_msg').'</li></ul></div>';
            } ?>
            <div id="errorsDiv"></div>

            <div id="bank_account_div" class="steps">
                <h3 class="details_label txtalgncntr">Ajoutez votre RIB</h3>
                <?php echo form_open_multipart('my_account/add_bank_account', array('method' => 'post', 'name' => 'add_bank_account_form', 'id' => 'add_bank_account_form', 'class' => '')); ?>
                    <input type="hidden" name="formSubmitted" value="1">

                    <label class="details_title_lv2">Titulaire du compte<input class="mgntop5" type="text" name="ownerName" placeholder="Titulaire du compte (Obligatoire)"></label>
                    <label class="details_title_lv2">Adresse du titulaire<input class="mgntop5" type="text" name="addressLine1" placeholder="Adresse (Obligatoire)"></label>
                    <label class="details_title_lv2">Ville<input class="mgntop5" type="text" name="city" placeholder="Ville (Obligatoire)"></label>
                    <!-- <label class="details_title_lv2">Region<input class="mgntop5" type="text" name="region" placeholder="Region"></label> -->
                    <label class="details_title_lv2">Code Postal<input class="mgntop5" type="text" name="postalCode" placeholder="Code Postal (Obligatoire)"></label>
                    <label class="details_title_lv2">Nom de la banque<input class="mgntop5" type="text" name="name" placeholder="Banque (Obligatoire)"></label>
                    <label class="details_title_lv2">Code Pays - (FR pour la FRANCE)<input class="mgntop5" type="text" name="country" placeholder="Tapez FR (Obligatoire)"></label>
                    <label class="details_title_lv2">IBAN - (Respectez les espaces de votre code IBAN)<input class="mgntop5" type="text" name="IBAN" placeholder="IBAN (Obligatoire)"></label>
                    <input type="submit" value="Valider">
                <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </div>
   <!--quest-fields close-->
</div>
<!--secondary close-->
<?php $this->load->view('footer_inner'); ?>
