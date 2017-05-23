<?php $this->load->view('header_inner'); ?>
<?php $this->load->view('sticky_header_profile'); ?>
<style type="text/css">
    .atelier-editer-table-cntnr table td:last-child{
        border: 2px solid #f2f2f2 !important;
        padding: 15px !important;
        border-left:0!important;
    }
    .green-cus-btn{
        background: #35b7af none repeat scroll 0 0;
        color: #fff;
        display: inline-block;
        font-family: "montserratbold";
        font-size: 12px;
        letter-spacing: 2px;
        padding: 11px 14px;
        text-transform: uppercase;
        transition: all 0.2s ease 0s;
        margin-bottom:5px;
    }
    .green-cus-btn:hover{background:#27dfd4;}
    .atelier-editer-table-cntnr table th, .atelier-editer-table-cntnr table td{float: none;}
    .atelier-editer-table-cntnr table th{border-width:1px;}
    @media only screen and (max-width: 980px){
        .atelier-editer-table-cntnr table th, .atelier-editer-table-cntnr table td{float: left;}
    }
</style>
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

    function show_subscription_information(){
        $('#step1').hide();
        $('#step2').hide();
        $('#step3').hide();
        $('#subscription_div').show();
        $('.second_menu').find('li').removeClass('active');
        $('.second_menu').find('li').eq(3).addClass('active');
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

    function upgrade_to_yearly(sub_id, uid)
    {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: js_base_url + "user/upgrade_subscription_plan",
            data: {"sub_id" : sub_id, "userid" : uid},
            success: function(response){
                $(".overlay").show();
                $(".modelbox_content").text("Votre abonnement a été mis à jour avec succès!");
                if( response['status'] == true){
                    $(".close").click(function(){
                        location.reload();
                    });
                }
            },
            error: function(response) {
                $(".overlay").show();
                $(".modelbox_content").text("Quelque-chose s'est mal passé!");
            }
        });
    }

    function upgrade_to_6months(uid)
    {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: js_base_url + "user/upgrade_subscription_plan_6_months",
            data: {"userid" : uid},
            success: function(response){
                $(".overlay").show();
                $(".modelbox_content").text("Votre abonnement a été mis à jour avec succès!");
                if( response['status'] == true){
                    $(".close").click(function(){
                        location.reload();
                    });
                }
            },
            error: function(response) {
                $(".overlay").show();
                $(".modelbox_content").text("Quelque-chose s'est mal passé!");
            }
        });
    }

    function upgrade_to_12months(uid)
    {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: js_base_url + "user/upgrade_subscription_plan_12_months",
            data: {"userid" : uid},
            success: function(response){
                $(".overlay").show();
                $(".modelbox_content").text("Votre abonnement a été mis à jour avec succès!");
                if( response['status'] == true){
                    $(".close").click(function(){
                        location.reload();
                    });
                }
            },
            error: function(response) {
                $(".overlay").show();
                $(".modelbox_content").text("Quelque-chose s'est mal passé!");
            }
        });
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
    <div class="bg"> </div>
  </div>
   <!--primary close-->
   <div class="secondary quest-secondary panier-secondary">
         <ul class="second_menu">
            <li><a rel="external" href="<?= base_url('account') ?>">Mes coordonnées</a></li>
            <li><a rel="external" href="<?= base_url('account') ?>">Ma description</a></li>
            <!-- <li><a rel="external" href="<?= base_url('account') ?>">Pour me contacter</a></li> -->
            <!-- <li><a rel="external" href="javascript:;" onclick="show_subscription_information();">Abonnement</a></li> -->
            <li class="active"><a href="<?= base_url('my_account') ?>">Mes fonds & Mon RIB</a></li>
         </ul>

      <div class="quest-fields" style="padding-top:100px;">
         <!-- <span>Fiche particulier</span> -->
         <div class="register-container-row text-left mfform some_div">
            <div id="errorDiv_professional_form" class="some_div"></div>

            <?php
            // Get Flash data on view
            if(!empty($this->session->flashdata('success_msg'))){
               echo '<div id="errorDiv_server_side" class="success_msg"><ul><li>'.$this->session->flashdata('success_msg').'</li></ul></div>';
            }
            if(!empty($this->session->flashdata('error_msg'))){
               echo '<div id="errorDiv_server_side" class="error_msg"><ul><li>'.$this->session->flashdata('error_msg').'</li></ul></div>';
            } ?>
            <div id="errorsDiv" class="some_div"></div>

            <div id="subscription_div" class="some_div">
                <h3 class="details_label txtalgncntr">Mes Fonds</h3>
                <?php if (!empty($user_subscription_data['sub_id'])): ?>
                    <?php if ($user_subscription_data['recurring_interval'] == 6): ?>
                        <div class="fr boxes-right">
                            <a class="green-cus-btn" href="javascript:void(0);" onclick="upgrade_to_yearly(<?php echo  $user_subscription_data['sub_id']; ?>, <?php echo $user_subscription_data['id']?>);">Actualisez votre abonnement</a>
                        </div>
                    <?php endif; ?>
                    <div class="atelier-editer-table-cntnr subscriptiondetail tablesmall" style="margin-bottom: 0px !important;">
                       <table>
                           <thead>
                               <tr>
                                   <th class="td2" style="width:15%;">date de début</th>
                                    <th class="td3" style="width:14%;">date de fin</th>
                                    <th class="td3" style="width:30%;">Prochaine date de facturation</th>
                                    <th class="td3" style="width:16%;">montant</th>
                                    <th class="td3" style="width:13%;">période</th>
                                    <th class="td3" style="width:12%;">statut</th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr>
                                   <td style="width:15%;"><?php echo $user_subscription_data['start_date_formatted']; ?></td>
                                   <td class="td1" style="width:14%;"><?php echo !empty($user_subscription_data['end_date_formatted']) ? $user_subscription_data['end_date_formatted'] : '-'; ?></td>
                                   <td class="td2" style="width:30%;"><?php echo $user_subscription_data['next_billing_date_formatted']; ?></td>
                                   <td class="td3" style="width:16%;"><?php echo $user_subscription_data['amount_formatted']; ?></td>
                                   <td class="td3" style="width:13%;"><?php echo $user_subscription_data['recurring_interval'].' Mois'; ?></td>
                                   <td class="td3" style="width:12%;"><?php echo ($user_subscription_data['status'] == 1) ? 'ACTIVE' : ''; ?></td>
                               </tr>
                           </tbody>
                       </table>
                   </div>
                <?php else: ?>
                    <div class="atelier-editer-table-cntnr" style="margin-bottom: 0px !important;">
                        <p>Vous utilisez actuellement le PLAN DE SOUSCRIPTION GRATUIT et vous serez facturé 15% du montant total de chaque réservation</p>
                    </div>
                    <!--
                    <div class="fr boxes-right">
                        <a id="upgrade_to_6months_btn" class="green-cus-btn" href="#" >Mise à niveau en plan de 6 mois</a>
                        <a id="upgrade_to_12months_btn" class="green-cus-btn" href="#" >Mise à niveau en plan de 12 mois</a>
                        <script>
                            $('#upgrade_to_6months_btn').click(function(){
                                upgrade_to_6months(<?= $id ?>);
                            })
                            $('#upgrade_to_12months_btn').click(function(){
                                upgrade_to_12months(<?= $id ?>);
                            })
                        </script>
                    </div>
                    -->
                <?php endif; ?>
            </div>
            <div id="wallet_div" class="some_div">

                <?php if (isset($wallet_data) && !empty($wallet_data)): ?>
                    <p class="details_title_lv2">Montant dans le portefeuille : <?= $wallet_data->Balance->Amount.' '.$wallet_data->Balance->Currency ?></p>
                    <?php if (isset($bank_account_data) && !empty($bank_account_data)): ?>
                        <a class="some_btn" href="<?= base_url('my_account/pay_out') ?>">Encaisser</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="#">Compte non trouvé...</a>
                <?php endif; ?>
            </div>
            <div id="rib_div" class="some_div">
                <h3 class="details_label txtalgncntr">Mon RIB</h3>
                <?php if (isset($bank_account_data) && !empty($bank_account_data)): ?>
                    <li>
                        <p class="details_title_lv2"><?= $bank_account_data->Tag ?></p>
                        <ul class="bank_account_details">
                            <li class="details_item"><p>Titulaire : <?= $bank_account_data->OwnerName ?></p></li>
                            <li class="details_item"><p>Adresse : <?= $bank_account_data->OwnerAddress->AddressLine1.', '.$bank_account_data->OwnerAddress->AddressLine2.', '.$bank_account_data->OwnerAddress->PostalCode.', '.$bank_account_data->OwnerAddress->City ?></p></li>
                            <li class="details_item">IBAN : <?= $bank_account_data->Details->IBAN ?> BIC : <?= $bank_account_data->Details->BIC ?></li>
                            <li class="details_item">
                                <a class="some_btn" href="<?= base_url('my_account/disable_bank_account') ?>">Désactiver</a><!-- my_account doubled if present  / absent if removed -->
                                <a class="some_btn" href="<?= base_url('my_account/add_bank_account/'.$bank_account_data->Id) ?>">Remplacer</a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <a class="some_btn" href="<?= base_url('my_account/add_bank_account') ?>">Renseigner mon RIB</a><!-- my_account doubled if present  / absent if removed -->
                <?php endif; ?>
            </div>
            <div id="kyc_div" class="some_div">
                <h3 class="details_label txtalgncntr">Mes documents</h3>
                <p>Afin de ne pas être limité dans les montant que vous pouvez encaisser dans l'année, veuillez fournir les documents suivants :</p>
                <?php if (isset($documents_data) && !empty($documents_data)): ?>
                    <div class="atelier-editer-table-cntnr subscriptiondetail tablesmall" style="margin-bottom: 0px !important;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Type de pièce</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents_data as $key => $document): ?>
                                    <?php if ($document['status'] !== 'unneeded'): ?>
                                        <tr>
                                            <td>
                                                <?php switch ($key): ?><?php case 'IDENTITY_PROOF': ?>
                                                Pièce d'identité
                                                <?php break;?>
                                                <?php case 'REGISTRATION_PROOF': ?>
                                                REGISTRATION_PROOF
                                                <?php break;?>
                                                <?php case 'ARTICLES_OF_ASSOCIATION': ?>
                                                ARTICLES_OF_ASSOCIATION
                                                <?php break;?>
                                                <?php case 'SHAREHOLDER_DECLARATION': ?>
                                                SHAREHOLDER_DECLARATION
                                                <?php break;?>
                                                <?php case 'ADDRESS_PROOF': ?>
                                                Justificatif de domicile
                                                <?php endswitch ?>
                                            </td>
                                            <td>
                                                <?php if ($document['status'] === 'needed'): ?>
                                                    Demandée
                                                <?php else: ?>
                                                    <?php switch ($document['document']->Status): ?><?php case 'CREATED': ?>
                                                    Créée
                                                    <?php break;?>
                                                    <?php case 'VALIDATION_ASKED': ?>
                                                    En cours de validation
                                                    <?php break;?>
                                                    <?php case 'VALIDATED': ?>
                                                    Validée
                                                    <?php break;?>
                                                    <?php case 'REFUSED': ?>
                                                    Refusée
                                                    <?php endswitch ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php switch ($document['status']): ?><?php case 'needed': ?>
                                                <a class="table_link" title="Ajouter" href="<?= base_url('my_account/add_kyc_document/'.$key) ?>">Ajouter<span class="add_btn"></span></a><!-- my_account dédoublé si présent -->
                                                <?php break;?>
                                                <?php case 'CREATED': ?>
                                                <a class="table_link" title="Modifier" href="<?= base_url('my_account/add_kyc_document/'.$key.'/'.$document['document']->Id) ?>">Modifier<span class="edit_btn"></span></a>
                                                <?php break;?>
                                                <?php case 'VALIDATION_ASKED': ?>
                                                <?php break;?>
                                                <?php case 'VALIDATED': ?>
                                                <?php break;?>
                                                <?php case 'REFUSED': ?>
                                                <a class="table_link" title="Ajouter à nouveau" href="<?= base_url('my_account/add_kyc_document') ?>">Ajouter à nouveau<span class="add_btn"></span></a>
                                                <?php endswitch ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
   <!--quest-fields close-->
</div>
<!--secondary close-->
<?php $this->load->view('footer_inner'); ?>
